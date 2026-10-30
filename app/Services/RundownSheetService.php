<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ProgramDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RundownSheetService
{
    /**
     * ID Spreadsheet Google Docs TVRI Kalsel
     */
    public const SPREADSHEET_ID = '10oD1E2PCXxhcfNqHAoJybdWNDO31vGAy6zk29FmnFww';

    /**
     * Pemetaan Sheet per Hari dan GID masing-masing
     */
    public const SHEET_DAYS = [
        'senin'  => ['name' => 'Senin',  'gid' => '149819451'],
        'selasa' => ['name' => 'Selasa', 'gid' => '442796712'],
        'rabu'   => ['name' => 'Rabu',   'gid' => '1070305520'],
        'kamis'  => ['name' => 'Kamis',  'gid' => '4462342'],
        'jumat'  => ['name' => 'Jumat',  'gid' => '627953764'],
        'sabtu'  => ['name' => 'Sabtu',  'gid' => '1694667949'],
        'minggu' => ['name' => 'Minggu', 'gid' => '1126991124'],
    ];

    /**
     * Dapatkan daftar hari yang tersedia.
     */
    public function getAvailableDays(): array
    {
        return self::SHEET_DAYS;
    }

    /**
     * Dapatkan hari ini dalam format key (senin..minggu).
     * Waktu disesuaikan dengan zona waktu WITA (Asia/Makassar).
     */
    public function getTodayKey(): string
    {
        $dayOfWeek = Carbon::now('Asia/Makassar')->dayOfWeekIso; // 1 = Senin, 7 = Minggu
        $map = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        return $map[$dayOfWeek] ?? 'senin';
    }

    /**
     * Ambil data rundown untuk hari tertentu (dengan cache).
     */
    public function getProgramsByDay(string $day = null, bool $forceRefresh = false): array
    {
        $dayKey = strtolower($day ?? $this->getTodayKey());
        if (!isset(self::SHEET_DAYS[$dayKey])) {
            $dayKey = $this->getTodayKey();
        }

        $cacheKey = "rundown_sheet_programs_{$dayKey}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, 900, function () use ($dayKey) {
            return $this->fetchAndParseDay($dayKey);
        });
    }

    /**
     * Fetch CSV langsung dari Google Sheets dan parse menjadi array terstruktur.
     */
    public function fetchAndParseDay(string $dayKey): array
    {
        if (!isset(self::SHEET_DAYS[$dayKey])) {
            return [];
        }

        $gid = self::SHEET_DAYS[$dayKey]['gid'];
        $spreadsheetId = config('services.google_sheets.spreadsheet_id', self::SPREADSHEET_ID);
        $url = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv&gid={$gid}";

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) RundownTVRIKalsel/2.0',
                ])
                ->get($url);

            if ($response->successful()) {
                $csv = $response->body();
                return $this->parseCsvContent($csv, $dayKey);
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal mengambil data sheet {$dayKey} dari Google: " . $e->getMessage());
        }

        // Fallback: Jika gagal fetch internet atau timeout, gunakan cache lokal atau default
        return $this->getLocalFallback($dayKey);
    }

    /**
     * Cek apakah baris merupakan kolom warna abu-abu (STING, PSA, TINJAUAN ACARA, FILLER) yang harus diabaikan.
     */
    public function isGreyOrFillerRow(string $acara, string $judulAcara = '', string $durasi = ''): bool
    {
        $upperAcara = strtoupper(trim(preg_replace('/\s+/', ' ', $acara)));
        $upperJudul = strtoupper(trim(preg_replace('/\s+/', ' ', $judulAcara)));

        if (empty($upperAcara)) {
            return true;
        }

        // 1. Bumper / Grey patterns pada nama acara
        if (
            str_contains($upperAcara, 'STING') ||
            str_contains($upperAcara, 'PSA') ||
            str_contains($upperAcara, 'TINJAUAN ACARA') ||
            str_starts_with($upperAcara, 'FILLER')
        ) {
            return true;
        }

        // 2. Bumper pada judul acara dengan durasi pendek (contoh: RELAY NASIONAL / FILLER GARUDA PANCASILA durasi <= 120s)
        if (str_contains($upperJudul, 'FILLER') || str_contains($upperJudul, 'STING') || str_contains($upperJudul, 'PSA')) {
            $parts = explode(':', $durasi);
            $secs = 0;
            if (count($parts) === 3) {
                $secs = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
            } elseif (count($parts) === 2) {
                $secs = ((int)$parts[0] * 60) + (int)$parts[1];
            }
            if ($secs <= 120) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse isi CSV menjadi array data acara yang siap ditampilkan di blade & controller.
     */
    public function parseCsvContent(string $csvContent, string $dayKey): array
    {
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $csvContent));
        if (empty($lines)) {
            return [];
        }

        // Simpan cache file lokal untuk backup offline
        $backupDir = storage_path('app/rundown_sheets');
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0755, true);
        }
        @file_put_contents("{$backupDir}/{$dayKey}.csv", $csvContent);

        // Ambil header
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csvContent);
        rewind($stream);

        $header = fgetcsv($stream);
        if (!$header) {
            fclose($stream);
            return [];
        }

        // Map kolom header secara dinamis
        $colIndex = [];
        foreach ($header as $idx => $name) {
            $cleanName = strtoupper(trim(preg_replace('/\s+/', ' ', $name)));
            $colIndex[$cleanName] = $idx;
        }

        // Ambil saved details dari database untuk merge (thumbnail, category, description)
        $savedDetails = ProgramDetail::all()->keyBy('program_title');
        $validCategories = Category::pluck('name')->toArray();

        $programs = [];
        $counter = 1;

        while (($row = fgetcsv($stream)) !== false) {
            // Lewati baris kosong
            if (empty(array_filter($row))) {
                continue;
            }

            $waktuRaw   = $this->getColumnValue($row, $colIndex, ['WAKTU'], 4);
            $mataAcara  = $this->getColumnValue($row, $colIndex, ['MATA ACARA', 'ACARA'], 5);
            $judulAcara = $this->getColumnValue($row, $colIndex, ['JUDUL ACARA'], 6);
            $channelRaw = $this->getColumnValue($row, $colIndex, ['CHANNEL'], 2);
            $durasiRaw  = $this->getColumnValue($row, $colIndex, ['DURASI'], 7);
            $statusRaw  = $this->getColumnValue($row, $colIndex, ['LIVE/PB/PREMIERE', 'LIVE/PB', 'STATUS'], 9);
            $infoRaw    = $this->getColumnValue($row, $colIndex, ['INFO'], 12);
            $lokasiRaw  = $this->getColumnValue($row, $colIndex, ['LOKASI'], 8);
            $produser   = $this->getColumnValue($row, $colIndex, ['PRODUSER'], 14);
            $host       = $this->getColumnValue($row, $colIndex, ['PEMBAWA ACARA', 'HOST'], 15);
            $narasumber = $this->getColumnValue($row, $colIndex, ['NARASUMBER'], 16);
            $tema       = $this->getColumnValue($row, $colIndex, ['TEMA'], 17);

            $acaraClean  = trim($mataAcara);
            $waktuClean  = trim($waktuRaw);
            $judulClean  = trim($judulAcara);
            $durasiClean = trim($durasiRaw);

            // 1. Validasi baris: lewati yang kosong (tidak ada mata acara atau tidak ada jam tayang)
            if (empty($acaraClean) || empty($waktuClean)) {
                continue;
            }

            // 2. Lewati baris formula/sum durasi di bagian bawah yang tidak memiliki channel dan status
            if (empty(trim($channelRaw)) && empty(trim($statusRaw))) {
                continue;
            }

            // 3. Lewati baris abu-abu (STING, PSA, TINJAUAN ACARA, FILLER) sesuai instruksi user
            if ($this->isGreyOrFillerRow($acaraClean, $judulClean, $durasiClean)) {
                continue;
            }

            // Normalisasi Channel
            $channelUpper = strtoupper(trim($channelRaw));
            if (str_contains($channelUpper, 'KALSEL')) {
                $channel = 'Kalsel';
            } elseif (str_contains($channelUpper, 'NASIONAL')) {
                $channel = 'Nasional';
            } elseif (str_contains($channelUpper, 'WORLD')) {
                $channel = 'World';
            } else {
                $channel = !empty($channelRaw) ? ucfirst(strtolower(trim($channelRaw))) : 'Kalsel';
            }

            // Normalisasi Status (LIVE, PLAYBACK, RELAY, RERUN, PREMIERE)
            $statusUpper = strtoupper(trim($statusRaw));
            if (str_contains($statusUpper, 'LIVE')) {
                $status = 'LIVE';
                $statusType = 'live';
            } elseif (str_contains($statusUpper, 'RERUN')) {
                $status = 'RERUN';
                $statusType = 'rerun';
            } elseif (str_contains($statusUpper, 'RELAY')) {
                $status = 'RELAY';
                $statusType = 'relay';
            } elseif (str_contains($statusUpper, 'PREMIERE')) {
                $status = 'PREMIERE';
                $statusType = 'premiere';
            } else {
                $status = 'PLAYBACK';
                $statusType = 'playback';
            }

            // Normalisasi Info
            $infoUpper = strtoupper(trim($infoRaw));
            if (str_contains($infoUpper, 'ON AIR') || str_contains($infoUpper, 'LIVE')) {
                $info = 'ON AIR';
            } elseif (str_contains($infoUpper, 'SUDAH') || str_contains($infoUpper, 'SELESAI')) {
                $info = 'SUDAH TAYANG';
            } else {
                $info = 'MENUNGGU';
            }

            // Normalisasi nama dasar: 'KALSEL HARI INI (RERUN)' -> 'KALSEL HARI INI'
            $baseName = $this->resolveBaseProgramName($acaraClean);
            $isVariant = ($baseName !== $acaraClean);

            // Thumbnail & deskripsi SELALU dari baseName agar varian mewarisi tampilan program asli
            $thumbnail = $this->resolveDefaultThumbnail($baseName);
            $deskripsi = $this->resolveDefaultDescription($baseName, $tema);

            // Kategori DIKOSONGKAN secara default sesuai instruksi user (akan diklasifikasikan manual oleh user)
            $kategori = '';

            // Merge dengan database ProgramDetail jika ada kustomisasi admin
            // PRIORITAS: baseName dulu (agar varian mewarisi data program induk),
            // baru exact match jika memang tidak ada record baseName di DB
            if (isset($savedDetails[$baseName])) {
                $detail = $savedDetails[$baseName];
            } elseif (isset($savedDetails[$acaraClean])) {
                $detail = $savedDetails[$acaraClean];
            } else {
                $detail = null;
            }

            if ($detail) {
                if (!empty($detail->thumbnail_url)) {
                    $thumbnail = $detail->thumbnail_url;
                }
                // Hanya gunakan kategori jika admin sudah mengklasifikasikan di database
                if (!empty($detail->category) && in_array($detail->category, $validCategories)) {
                    $kategori = $detail->category;
                }
                if (!empty($detail->description)) {
                    $deskripsi = $detail->description;
                }
            }

            $programs[] = [
                'id'            => $counter,
                'no'            => $counter,
                'waktu'         => $waktuClean,
                'acara'         => $acaraClean,
                'channel'       => $channel,
                'durasi'        => trim($durasiRaw),
                'status'        => $status,
                'status_type'   => $statusType,
                'info'          => $info,
                'kategori'      => $kategori, // Kosong secara default sampai user mengklasifikasikannya
                'thumbnail'     => $thumbnail,
                'description'   => $deskripsi,
                'lokasi'        => trim($lokasiRaw),
                'produser'      => trim($produser),
                'host'          => trim($host),
                'narasumber'    => trim($narasumber),
                'tema'          => trim($tema),
                'day_key'       => $dayKey,
                'hari'          => self::SHEET_DAYS[$dayKey]['name'] ?? ucfirst($dayKey),
            ];

            $counter++;
        }

        fclose($stream);

        // Sinkronisasi otomatis judul-judul acara baru ke database ProgramDetail
        $this->syncProgramsToDatabase($programs);

        return $programs;
    }

    /**
     * Dapatkan nilai kolom dari baris CSV berdasarkan prioritas nama kolom.
     */
    protected function getColumnValue(array $row, array $colIndex, array $names, int $fallbackIndex): string
    {
        foreach ($names as $name) {
            if (isset($colIndex[$name]) && isset($row[$colIndex[$name]])) {
                return (string) $row[$colIndex[$name]];
            }
        }

        return (string) ($row[$fallbackIndex] ?? '');
    }

    /**
     * Ambil program yang sedang tayang (ON AIR) saat ini secara realtime (WITA).
     */
    public function getCurrentOnAir(array $programs): array
    {
        if (empty($programs)) {
            return [
                'id' => 1,
                'no' => 1,
                'waktu' => '09:00:00',
                'acara' => 'TVRI Kalsel',
                'channel' => 'Kalsel',
                'durasi' => '01:00:00',
                'status' => 'LIVE',
                'status_type' => 'live',
                'info' => 'ON AIR',
                'kategori' => '',
                'thumbnail' => 'habarbanua.jpg',
                'description' => 'Siaran TVRI Kalimantan Selatan menyajikan tayangan berkualitas.',
            ];
        }

        // 1. Cek apakah ada acara yang ditandai ON AIR di spreadsheet
        $onAir = collect($programs)->firstWhere('info', 'ON AIR');
        if ($onAir) {
            return $onAir;
        }

        // 2. Jika tidak ada tag ON AIR, kalkulasi berdasarkan jam waktu sekarang (WITA)
        $nowWita = Carbon::now('Asia/Makassar');
        $currentTime = $nowWita->format('H:i:s');

        $matched = null;
        for ($i = 0; $i < count($programs); $i++) {
            $progTime = $this->normalizeTimeFormat($programs[$i]['waktu'] ?? '');
            if (!$progTime) continue;

            $nextTime = isset($programs[$i + 1])
                ? $this->normalizeTimeFormat($programs[$i + 1]['waktu'] ?? '')
                : '23:59:59';

            if ($currentTime >= $progTime && $currentTime < $nextTime) {
                $matched = $programs[$i];
                $matched['info'] = 'ON AIR';
                break;
            }
        }

        if ($matched) {
            return $matched;
        }

        // 3. Fallback ke acara live atau acara pertama
        return collect($programs)->firstWhere('status_type', 'live') ?? $programs[0];
    }

    /**
     * Normalisasi string waktu menjadi format HH:mm:ss.
     */
    protected function normalizeTimeFormat(string $timeStr): ?string
    {
        $timeStr = trim($timeStr);
        if (empty($timeStr)) return null;

        $parts = explode(':', $timeStr);
        if (count($parts) === 3) {
            return sprintf('%02d:%02d:%02d', (int)$parts[0], (int)$parts[1], (int)$parts[2]);
        } elseif (count($parts) === 2) {
            return sprintf('%02d:%02d:00', (int)$parts[0], (int)$parts[1]);
        }

        return null;
    }

    /**
     * Resolusi nama dasar program dari varian yang memiliki sufiks seperti (RERUN), (LANJUTAN), (KALBAR), dll.
     * Contoh:
     *   "KALSEL HARI INI (RERUN)"         → "KALSEL HARI INI"
     *   "INI BORNEO (KALBAR)"             → "INI BORNEO"
     *   "FIQIH WANITA (LANJUTAN)"         → "FIQIH WANITA"
     *   "PESONA INDONESIA - KALSEL"       → "PESONA INDONESIA"
     *   "INI BORNEO KALSEL (RERUN)"       → "INI BORNEO"
     *   "CAHAYA QOLBU\n(RERUN)"           → "CAHAYA QOLBU"
     *   "INSPIRASI INDONESIA - NUSANTARA / BORNEO" → "INSPIRASI INDONESIA"
     */
    public function resolveBaseProgramName(string $acara): string
    {
        // Normalisasi whitespace (newlines dari CSV kadang masuk ke nama acara)
        $base = trim(preg_replace('/\s+/', ' ', $acara));

        // 1. Hapus isi dalam tanda kurung di akhir: (RERUN), (KALBAR), (LANJUTAN), (KALTARA), (MATERI NASIONAL), dll.
        $base = preg_replace('/\s*\([^)]*\)\s*$/', '', $base);

        // 2. Hapus sufiks " - REGION" seperti " - KALSEL", " - NUSANTARA / BORNEO"
        $base = preg_replace('/\s*-\s*(?:KALSEL|KALTIM|KALBAR|KALTENG|KALTARA|NUSANTARA(?:\s*\/\s*BORNEO)?|NASIONAL)\s*$/i', '', $base);

        // 3. Hapus nama region yang berdiri sendiri di akhir (tanpa tanda kurung), misal: "INI BORNEO KALSEL" → "INI BORNEO"
        $base = preg_replace('/\s+(?:KALSEL|KALTIM|KALBAR|KALTENG|KALTARA)\s*$/i', '', $base);

        return trim($base);
    }

    /**
     * Tentukan default thumbnail berdasarkan judul program.
     */
    protected function resolveDefaultThumbnail(string $acara): string
    {
        $map = [
            'HABAR BANUA'          => 'habarbanua.jpg',
            'INI BORNEO'           => 'ini_borneo_thumb.jpg',
            'PERSPEKTIF'           => 'perspektif_thumb.jpg',
            'KALSEL HARI INI'      => 'kalseltoday_thumb.jpg',
            'INSPIRASI INDONESIA'  => 'inspirasiindo_thumb.PNG',
            'KAJIAN TAUHID'        => 'kajian_thumb.jpg',
            'ADZAN MAGHRIB'        => 'adzan_thumb.jpg',
            'PESONA INDONESIA'     => 'pesonaindo_thumb.PNG',
            'MAWARUNG'             => 'mawarung_thumb.jpg',
        ];

        $upper = strtoupper($acara);
        foreach ($map as $key => $file) {
            if (str_contains($upper, $key)) {
                return $file;
            }
        }

        return '';
    }

    /**
     * Tentukan deskripsi default yang informatif jika belum diisi admin.
     */
    protected function resolveDefaultDescription(string $acara, string $tema): string
    {
        if (!empty($tema) && $tema !== '//' && $tema !== '-') {
            return "Tema: {$tema}. Program siaran TVRI Kalimantan Selatan menyajikan tayangan berkualitas untuk masyarakat Banua.";
        }

        return "Program acara siaran TVRI Kalimantan Selatan menyajikan tayangan informatif, edukatif, dan menghibur bagi seluruh masyarakat Banua.";
    }

    /**
     * Daftarkan judul-judul acara baru ke tabel program_details agar admin bisa mengelola detailnya.
     * Kategori dikosongkan agar admin bisa mengklasifikasikannya secara manual.
     */
    protected function syncProgramsToDatabase(array $programs): void
    {
        $existingTitles = ProgramDetail::pluck('program_title')->flip()->toArray();

        foreach ($programs as $prog) {
            $title = $prog['acara'];
            // Lewati baris abu-abu (station bumpers / promo / filler)
            if (empty($title) || $this->isGreyOrFillerRow($title)) {
                continue;
            }

            if (!isset($existingTitles[$title])) {
                ProgramDetail::firstOrCreate(
                    ['program_title' => $title],
                    [
                        'category'      => null, // Dikosongkan agar diklasifikasikan sendiri oleh admin
                        'description'   => $prog['description'] ?? null,
                        'thumbnail_url' => !empty($prog['thumbnail']) ? $prog['thumbnail'] : null,
                    ]
                );
                $existingTitles[$title] = true;
            }
        }
    }

    /**
     * Ambil data backup lokal jika koneksi ke spreadsheet mengalami gangguan.
     */
    protected function getLocalFallback(string $dayKey): array
    {
        $backupFile = storage_path("app/rundown_sheets/{$dayKey}.csv");
        if (file_exists($backupFile)) {
            $csv = file_get_contents($backupFile);
            return $this->parseCsvContent($csv, $dayKey);
        }

        return [];
    }

    /**
     * Bersihkan semua cache rundown spreadsheet (manual refresh).
     */
    public function clearAllCache(): void
    {
        foreach (array_keys(self::SHEET_DAYS) as $day) {
            Cache::forget("rundown_sheet_programs_{$day}");
        }
    }
}
