# 📋 LAPORAN TEKNIS LENGKAP PENGEMBANGAN WEBSITE RUNDOWN DIGITAL TVRI KALIMANTAN SELATAN

> **Sistem Informasi Jadwal Siaran Real-Time Terintegrasi Google Spreadsheet**  
> *Stasiun TVRI Kalimantan Selatan — Banjarbaru/Banjarmasin*

---

## 📑 DAFTAR ISI
1. [BAB I — PERENCANAAN SISTEM](#bab-i--perencanaan-sistem)
2. [BAB II — SDLC (SOFTWARE DEVELOPMENT LIFE CYCLE)](#bab-ii--sdlc-software-development-life-cycle)
3. [BAB III — TEKNOLOGI & ARSITEKTUR YANG DIGUNAKAN](#bab-iii--teknologi--arsitektur-yang-digunakan)
4. [BAB IV — USE CASE DIAGRAM](#bab-iv--use-case-diagram)
5. [BAB V — FLOWCHART SISTEM (KESELURUHAN & SUB-SISTEM)](#bab-v--flowchart-sistem-keseluruhan--sub-sistem)
6. [BAB VI — DAFTAR FITUR FUNGSIONAL & NON-FUNGSIONAL](#bab-vi--daftar-fitur-fungsional--non-fungsional)
7. [BAB VII — PERANCANGAN BASIS DATA (CDM, LDM, PDM)](#bab-vii--perancangan-basis-data-cdm-ldm-pdm)
8. [BAB VIII — MASALAH, ANALISIS, DAN SOLUSI TEKNIS](#bab-viii--masalah-analisis-dan-solusi-teknis)
9. [BAB IX — PENGUJIAN BLACK BOX TESTING (PER PAGE)](#bab-ix--pengujian-black-box-testing-per-page)
10. [BAB X — KESIMPULAN & RIWAYAT VERSI](#bab-x--kesimpulan--riwayat-versi)

---

## 📌 BAB I — PERENCANAAN SISTEM

### 1.1 Latar Belakang
Stasiun Lembaga Penyiaran Publik Televisi Republik Indonesia (LPP TVRI) Stasiun Kalimantan Selatan memproduksi dan menyiarkan berbagai program berita, kebudayaan, religi, dan hiburan daerah maupun nasional. Dalam operasional sehari-hari, jadwal siaran (rundown) disusun oleh bagian Master Control Room (MCR) dan penyiaran menggunakan media **Google Spreadsheet**.

Sebelum adanya sistem ini, masyarakat dan pemirsa umum tidak memiliki akses digital yang praktis, responsif, dan real-time untuk mengetahui rundown program harian. Rundown yang beredar terbatas pada dokumen cetak atau internal. Oleh karena itu, dibangun **Website Rundown Digital TVRI Kalimantan Selatan** yang menjembatani data dinamis operasional Google Spreadsheet dengan antarmuka web modern yang dapat diakses publik kapan saja tanpa perlu login, sekaligus menyediakan antarmuka administrasi internal yang aman bagi karyawan dan admin stasiun.

### 1.2 Tujuan Proyek
1. **Transparansi Informasi**: Menyajikan jadwal siaran harian (Senin s/d Minggu) yang transparan, akurat, dan mudah dipahami publik.
2. **Sinkronisasi Otomatis**: Menghubungkan Google Spreadsheet operasional stasiun secara langsung dengan sistem web tanpa perlu input manual berulang.
3. **Peningkatan User Experience (UX)**: Menyediakan informasi status siaran real-time (*ON AIR / LIVE*, *PLAYBACK*, *RELAY*, *RERUN*, *SUDAH TAYANG*, dan *MENUNGGU*) berbasis jam lokal WITA (Asia/Makassar).
4. **Pemberian Detail Program**: Menampilkan modal pop-up interaktif berisi gambar thumbnail resolusi tinggi, deskripsi edukatif acara, jam tayang, dan kategori siaran.
5. **Panel Manajemen Terpadu**: Memberikan fasilitas bagi pengelola (karyawan & admin) untuk memperbarui thumbnail, deskripsi, klasifikasi kategori, serta pengelolaan hak akses operator.

### 1.3 Ruang Lingkup Sistem (Scope & Boundary)
- **Pengguna Publik**: Akses bebas tanpa login ke halaman beranda rundown, filter hari, filter kategori, pencarian program real-time, melihat modal pop-up detail acara, melihat program berikutnya, dan memantau siaran langsung.
- **Karyawan / Operator MCR**: Login menggunakan *username* unik, mengakses dashboard manajemen rundown, mengedit deskripsi acara, mengunggah thumbnail, dan melakukan paksa sinkronisasi (*force refresh*) data spreadsheet.
- **Super Administrator**: Seluruh hak akses karyawan ditambah kendali penuh atas manajemen akun pengguna (tambah, edit role, nonaktifkan/aktifkan kembali) dan manajemen master kategori program.

### 1.4 Jadwal & Rencana Kerja (Sprint Timeline)
Proyek diselesaikan dalam 5 sprint bertahap:
- **Sprint 1 (Perancangan & Setup Awal)**: Analisis kebutuhan, konfigurasi Laravel 12 + Breeze, desain skema database, migrasi tabel.
- **Sprint 2 (Integrasi Google Sheets & Tampilan Publik)**: Pembangunan `RundownSheetService` (HTTP client CSV parser), caching layer 15 menit, filter STING/abu-abu, antarmuka publik responsif.
- **Sprint 3 (Dashboard Admin & CRUD Master)**: Panel editor interaktif, AJAX form submit, upload file thumbnail, manajemen kategori dan user.
- **Sprint 4 (Profil & Penyempurnaan UI/UX)**: Halaman profil, ganti password, avatar foto, responsivitas slider, perbaikan badge hari ini, lokalisasi tanggal Indonesia.
- **Sprint 5 (Fix Varian Program & Pengujian Menyeluruh)**: Algoritma `resolveBaseProgramName()` untuk pewarisan otomatis varian `(RERUN)`, `(LANJUTAN)`, `(KALBAR)` dari program induk, pengujian black box lengkap.

---

## 🔄 BAB II — SDLC (SOFTWARE DEVELOPMENT LIFE CYCLE)

Metode pengembangan perangkat lunak yang diterapkan adalah **Iterative & Incremental Model (Agile-Lite)**. Pendekatan ini memungkinkan fungsionalitas inti dirilis lebih awal, kemudian disempurnakan berdasarkan pengujian dan feedback pengguna secara berkelanjutan.

![SDLC Diagram](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/00_sdlc.png)

### Tahapan Pelaksanaan SDLC:
1. **Planning (Perencanaan)**: Mengidentifikasi pemangku kepentingan (MCR, pemirsa, humas TVRI), mendefinisikan batasan teknis, menetapkan spreadsheet Google sebagai *single source of truth*, serta memilih stack Laravel 12 dan SQLite/MySQL.
2. **Design (Perancangan)**:
   - Perancangan basis data berjenjang: *Conceptual Data Model* (CDM), *Logical Data Model* (LDM), dan *Physical Data Model* (PDM).
   - Pemodelan fungsional: *Use Case Diagram* dan *Flowchart* sistem operasional.
   - Perancangan UI/UX berbasis standar TailwindCSS.
3. **Development (Pengembangan/Coding)**:
   - Backend: Laravel Service Layer (`RundownSheetService.php`), Controller modular, Middleware hak akses berganda.
   - Frontend: Blade templating engine, Alpine.js untuk reaktivitas ringan modal/dropdown, Axios untuk asynchronous update.
4. **Testing (Pengujian)**:
   - Black Box Testing pada setiap fungsi halaman untuk memvalidasi input-output.
   - Unit verification logika parser dan normalisasi nama program.
5. **Deployment & Maintenance (Penyebaran)**:
   - Version Control via Git dan kolaborasi cabang fitur (`feature/ui-public`).
   - Pelacakan perubahan dan catatan rilis melalui GitHub Issues.

---

## ⚙️ BAB III — TEKNOLOGI & ARSITEKTUR YANG DIGUNAKAN

### 3.1 Stack Teknologi
| Layer | Komponen / Library | Versi | Peran & Alasan Pemilihan |
|---|---|---|---|
| **Core Runtime** | PHP | ^8.2 | Bahasa server-side modern dengan performa tinggi dan type safety. |
| **Backend Framework** | Laravel Framework | ^12.0 | Framework PHP berarsitektur MVC dengan ekosistem routing, auth, dan caching yang tangguh. |
| **Auth Scaffolding** | Laravel Breeze | Modern | Sistem otentikasi berbasis session web yang ringan, aman, dan mudah dimodifikasi ke sistem login *username*. |
| **Database** | SQLite / MySQL | Compatible | Database relasional ACID-compliant untuk penyimpanan metadata program, pengguna, dan audit log. |
| **Caching Engine** | Laravel Cache (DB Driver) | — | Menahan hasil parsing data spreadsheet selama 900 detik (15 menit) guna mengeliminasi latensi dan menghindari rate limit Google. |
| **Frontend Styling** | TailwindCSS | ^3.x | Utility-first styling modern untuk antarmuka yang clean, responsif, dan konsisten. |
| **Frontend Behavior** | Alpine.js | ^3.x | Framework JS reaktif minimalis untuk mengontrol modal, popup, dan dropdown tanpa dependensi berat. |
| **HTTP Client Frontend**| Axios | ^1.x | Melakukan AJAX Form Submission saat mengupdate detail acara tanpa perlu memuat ulang (*reload*) halaman. |
| **Iconography** | Iconify (Heroicons/Lucide)| Web Component | Pustaka ikon vektor berbasis SVG yang tajam pada semua resolusi layar. |
| **Asset Bundler** | Vite | ^6.x | Build tool super cepat untuk kompilasi CSS dan JavaScript. |
| **External Service** | Google Spreadsheet API (CSV) | Web Export | Sumber data jadwal aktual operasional harian TVRI Kalsel. |

### 3.2 Arsitektur Sistem (MVC + Service Layer)
Aplikasi memisahkan logika bisnis dari kontrol alur menggunakan pola **Service Layer**:
```
[Browser Client]
       │
       ▼
[Web Routing (routes/web.php)]
       │
       ▼ (Middleware: auth, karyawan, admin)
[Controllers (RundownController, DashboardController)]
       │
       ▼
[Service Layer: RundownSheetService] ◄────► [Database Cache / Local CSV Fallback]
       │                                     ▲
       ▼                                     │
[Google Spreadsheet CSV Export API] ─────────┘
       │
       ▼
[Eloquent Models (User, ProgramDetail, Category, ActivityLog)]
       │
       ▼
[Blade View Templates (UI Rendering)]
```

---

## 👤 BAB IV — USE CASE DIAGRAM

Diagram use case memetakan interaksi fungsional antara tiga aktor (*Pengguna Publik*, *Karyawan/Operator*, dan *Super Administrator*) dengan sistem.

![Use Case Diagram](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/08_usecase.png)

### Tabel Rincian Fungsionalitas Aktor:
| ID | Use Case | Aktor Terkait | Deskripsi Aksi |
|---|---|---|---|
| **UC-01** | Lihat Rundown Acara Harian | Publik, Karyawan, Admin | Menampilkan daftar seluruh program siaran hari aktif beserta jam tayang dan status. |
| **UC-02** | Filter Jadwal Hari (Senin-Minggu)| Publik, Karyawan, Admin | Beralih antar tab hari untuk melihat arsip rundown sepanjang pekan. |
| **UC-03** | Filter Kategori Program | Publik, Karyawan, Admin | Memfilter program berdasar kategori: Berita, Kalsel, Nasional, Religi, Dokumenter, dll. |
| **UC-04** | Pencarian Acara Real-Time | Publik, Karyawan, Admin | Menemukan acara secara instan melalui input pengetikan judul acara. |
| **UC-05** | Lihat Pop-up Detail Acara | Publik, Karyawan, Admin | Membuka modal interaktif yang menampilkan thumbnail acara, deskripsi lengkap, durasi, dan tema. |
| **UC-06** | Login Autentikasi Sistem | Karyawan, Admin | Memasukkan username dan password untuk memperoleh akses hak kelola internal. |
| **UC-07** | Kelola Detail Acara & Thumbnail | Karyawan, Admin | Mengubah deskripsi, mengupload foto thumbnail, dan mengatur kategori via dashboard. |
| **UC-08** | Sinkronisasi Manual Spreadsheet | Karyawan, Admin | Menghapus seluruh cache dan memaksa sistem mengambil data termutakhir dari Google Sheets. |
| **UC-09** | Mengubah Profil & Avatar | Karyawan, Admin | Memperbarui nama lengkap, username, password pribadi, dan foto avatar. |
| **UC-10** | Kelola Kategori Program | Super Administrator | Menambahkan, mengedit warna hex, ikon, atau menghapus kategori program siaran. |
| **UC-11** | Kelola Pengguna (User Management)| Super Administrator | Membuat user baru, mengatur role (admin/karyawan), dan menonaktifkan akun operator. |

---

## 📊 BAB V — FLOWCHART SISTEM

### 5.1 Flowchart Keseluruhan Sistem (Global Flowchart)
Menggambarkan siklus hidup data mulai dari request pengunjung, pengecekan multi-tier cache, fetch spreadsheet, penguraian varian program, hingga pembaruan data oleh admin:

![Flowchart Sistem Keseluruhan](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/05_flowchart_system.png)

---

### 5.2 Flowchart Sub-Sistem 1: Autentikasi & Keamanan Login
Menjelaskan mekanisme keamanan saat operator melakukan login, termasuk pembatasan percobaan login (Rate Limiter) dan pengecekan flag akun aktif:

![Flowchart Login](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/04_flowchart_login.png)

---

### 5.3 Flowchart Sub-Sistem 2: Scraping Google Sheets & Multi-Layer Caching
Menjelaskan tahapan pengambilan data CSV dari Google Docs Spreadsheet, pembersihan data baris non-siaran (*STING / PSA / Filler*), hingga penyimpanan cache lokal:

![Flowchart Sheets](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/05_flowchart_sheets.png)

---

### 5.4 Flowchart Sub-Sistem 3: Kalkulasi Status Siaran Real-Time (WITA)
Menjelaskan logika 3 lapis untuk mendeteksi acara yang sedang live (*ON AIR*), menunggu tayang (*MENUNGGU*), maupun yang telah usai (*SUDAH TAYANG*):

![Flowchart Status](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/06_flowchart_status.png)

---

### 5.5 Flowchart Sub-Sistem 4: Pengelolaan Detail Acara & Propagasi Varian
Menjelaskan proses saat admin mengupdate detail acara, di mana perubahan pada program induk secara otomatis diteruskan ke seluruh varian turunannya (*RERUN, LANJUTAN, REGIONAL*):

![Flowchart Edit Program](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/07_flowchart_edit.png)

---

## ✅ BAB VI — DAFTAR FITUR FUNGSIONAL & NON-FUNGSIONAL

### 6.1 Fitur Fungsional
| Kode | Nama Fitur | Deskripsi Fungsional | Hak Akses |
|---|---|---|---|
| **FF-01** | Visualisasi Rundown Siaran | Menampilkan data siaran dalam bentuk tabel informatif yang mencakup jam, judul acara, durasi, saluran, dan status penayangan. | Publik, Karyawan, Admin |
| **FF-02** | Navigasi Hari Terpadu | Tab bar 7 hari (Senin - Minggu) dengan indikator dinamis penunjuk hari ini (*badge hijau/putih*). | Publik, Karyawan, Admin |
| **FF-03** | Slider Filter Kategori | Slider kategori acara horizontal tanpa scrollbar kasar dengan tombol navigasi geser kiri/kanan. | Publik, Karyawan, Admin |
| **FF-04** | Pencarian Instan (Live Search) | Filter baris tabel secara real-time via client-side DOM filtering saat kata kunci diketik. | Publik, Karyawan, Admin |
| **FF-05** | Modal Pop-Up Detail Acara | Jendela modal detail yang menampilkan gambar thumbnail, deskripsi lengkap, durasi, dan tema episode. | Publik, Karyawan, Admin |
| **FF-06** | Deteksi Status Penayangan Otomatis | Perhitungan komparasi jam lokal WITA untuk menentukan status *LIVE / SUDAH TAYANG / MENUNGGU*. | Sistem Otomatis |
| **FF-07** | Sidebar Program Berikutnya | Widget sidebar yang menampilkan urutan acara yang akan datang setelah jam saat ini. | Publik |
| **FF-08** | Kartu Ringkasan Statistik | Counter visual yang menghitung total acara, jumlah acara live, jumlah selesai, dan jumlah menunggu. | Publik, Karyawan, Admin |
| **FF-09** | Otentikasi Berbasis Username | Login sistem yang aman tanpa memerlukan email, lengkap dengan pesan peringatan berbahasa Indonesia. | Karyawan, Admin |
| **FF-10** | Editor Detail Program Interaktif | Form edit di sidebar dashboard untuk mengisi deskripsi kustom dan menetapkan kategori acara. | Karyawan, Admin |
| **FF-11** | Manajemen Unggah Thumbnail | Upload gambar thumbnail program dengan validasi ekstensi (JPG/PNG/WEBP) dan ukuran maksimal 2MB. | Karyawan, Admin |
| **FF-12** | Pewarisan Otomatis Varian Program | Otomatisasi sinkronisasi thumbnail & deskripsi untuk acara berlabel `(RERUN)`, `(LANJUTAN)`, `(KALBAR)`. | Sistem Otomatis |
| **FF-13** | Tombol Paksa Sinkronisasi | Kemampuan menghapus cache aplikasi secara instan guna memuat ulang revisi spreadsheet terbaru. | Karyawan, Admin |
| **FF-14** | Manajemen Kategori Acara | Menambah, mengedit, dan menghapus master kategori dengan warna tema dan ikon khusus. | Super Admin |
| **FF-15** | Manajemen Akun Pengguna | Menambah operator baru, mengubah peran kerja (*Admin/Karyawan*), dan mengunci akses akun nonaktif. | Super Admin |
| **FF-16** | Manajemen Profil & Avatar | Mengganti nama pengguna, kata sandi, dan mengunggah foto profil personal karyawan. | Karyawan, Admin |

### 6.2 Fitur Non-Fungsional
| Kode | Parameter | Spesifikasi & Implementasi |
|---|---|---|
| **FNF-01** | **Performance (Performa)** | Menggunakan cache database selama 900 detik; response time halaman publik < 200ms saat cache hit. |
| **FNF-02** | **Security (Keamanan)** | Enkripsi kata sandi menggunakan algoritma Bcrypt; proteksi CSRF di seluruh form; sanitasi string dari CSV. |
| **FNF-03** | **Reliability (Keandalan)** | Dilengkapi *Local Fallback CSV Parser* di `storage/app/` jika server Google Spreadsheet mengalami gangguan jaringan. |
| **FNF-04** | **Usability (Kemudahan Pakai)** | Antarmuka adaptif berbasis TailwindCSS, tombol slider sentuh ramah seluler, teks kontras tinggi, dan lokalisasi tanggal Indonesia. |
| **FNF-05** | **Maintainability (Perawatan)**| Struktur kode terisolasi rapi (*Separation of Concerns*): Service, Controller, Model, dan Blade Template independen. |
| **FNF-06** | **Auditability (Pencatatan)** | Seluruh aksi mutasi data kritis (update acara, login, perubahan user) dicatat di tabel `activity_logs`. |

---

## 🗃️ BAB VII — PERANCANGAN BASIS DATA (CDM, LDM, PDM)

### 7.1 CDM (Conceptual Data Model)
Menggambarkan entitas bisnis utama dan hubungan konseptual (*cardinality*) tanpa memandang implementasi fisik database:

![CDM Diagram](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/01_cdm.png)

---

### 7.2 LDM (Logical Data Model)
Menjelaskan struktur data logis, tipe atribut abstrak, kunci primer (PK), kunci unik (UQ), dan kunci tamu (FK):

![LDM Diagram](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/02_ldm.png)

---

### 7.3 PDM (Physical Data Model)
Model data fisik yang telah dinormalisasi secara presisi sesuai dengan tabel database SQLite / MySQL pada aplikasi:

![PDM Diagram](https://raw.githubusercontent.com/ivanadinata78/digitalrundown-tvrikalsel/feature/ui-public/docs/diagrams/03_pdm.png)

#### Skrip SQL Physical Data Model (DDL):
```sql
-- 1. Tabel users: Menyimpan kredensial dan hak akses
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'karyawan',
    avatar_path VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 2. Tabel categories: Master klasifikasi kategori siaran
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    color_hex VARCHAR(7) NULL,
    icon VARCHAR(100) NULL,
    created_by INTEGER NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 3. Tabel program_details: Metadata tambahan acara (thumbnail & deskripsi)
CREATE TABLE program_details (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    program_title VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(100) NULL,
    thumbnail_url VARCHAR(255) NULL,
    description TEXT NULL,
    updated_by INTEGER NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 4. Tabel broadcast_statuses: Pencatatan status tayang aktual harian
CREATE TABLE broadcast_statuses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    broadcast_date DATE NOT NULL,
    no_urut INTEGER NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'menunggu',
    updated_by INTEGER NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE (broadcast_date, no_urut)
);

-- 5. Tabel channels: Daftar kanal penyiaran TVRI
CREATE TABLE channels (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    logo_url VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 6. Tabel activity_logs: Audit trail pencatatan aktivitas pengguna
CREATE TABLE activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(100) NULL,
    entity_id INTEGER NULL,
    description TEXT NULL,
    old_value TEXT NULL,
    new_value TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 🐛 BAB VIII — MASALAH, ANALISIS, DAN SOLUSI TEKNIS

Sepanjang siklus pengembangan, ditemukan beberapa tantangan teknis yang berhasil diselesaikan secara komprehensif:

| No | Gejala Permasalahan | Akar Penyebab (Root Cause) | Solusi & Penanganan Teknis | Status |
|---|---|---|---|---|
| **M-01** | Program varian seperti `KALSEL HARI INI (RERUN)` atau `INI BORNEO (KALBAR)` memiliki deskripsi/thumbnail yang kosong atau salah. | Parser CSV menggunakan exact match judul acara terhadap database. Varian dianggap judul yang sepenuhnya berbeda dari program induknya. | Mengembangkan algoritma pembersih string `resolveBaseProgramName()` yang membuang sufiks `(RERUN)`, `(LANJUTAN)`, `(KALBAR)`, dll. Mengutamakan pencarian basis data berdasar nama program dasar terlebih dahulu. | **TERATASI (SOLVED)** |
| **M-02** | Slider filter kategori (Semua, Nasional, Kalsel, dll.) pada dashboard meluap (*overflow*) melewati batas kartu kontainer. | Nilai CSS `max-width` statis pada kontainer dan hilangnya properti `overflow: hidden` pada wrapper slider, menyebabkan tombol panah navigasi terdorong ke luar. | Memasang properti `overflow: hidden`, `padding: 0 26px`, dan class Tailwind `min-w-0 flex-1` sehingga wadah slider lentur dan tombol navigasi tetap berada di dalam kartu. | **TERATASI (SOLVED)** |
| **M-03** | Teks badge penunjuk "Hari Ini" pada tab hari aktif nyaris tidak terlihat karena warna latar transparan. | Kelas CSS menggunakan `bg-white/25 text-white`, sehingga saat tab aktif berwarna biru terang, tulisan putih menjadi kabur/nyaru. | Mengubah style badge menjadi `bg-white text-[#0066ff]` pada saat tab aktif (kontras maksimal) dan `bg-emerald-500 text-white` pada saat tidak aktif. | **TERATASI (SOLVED)** |
| **M-04** | Tanggal di bawah judul halaman publik tampil dalam format bahasa Inggris (`Saturday, 31 October 2026`). | Penggunaan method Carbon tanpa menyetel pengaturan locale lokal Bahasa Indonesia. | Memperbarui kode menjadi `Carbon::now('Asia/Makassar')->locale('id')->isoFormat('dddd, D MMMM YYYY')`. | **TERATASI (SOLVED)** |
| **M-05** | Perubahan deskripsi atau thumbnail oleh admin tidak langsung muncul pada halaman publik. | Data lama tersimpan di dalam cache database selama 15 menit dan belum dibersihkan saat proses update terjadi. | Menambahkan eksekusi `clearAllCache()` di dalam method controller `updateProgramDetail()` agar seluruh 7 cache hari segera dibersihkan saat data disimpan. | **TERATASI (SOLVED)** |
| **M-06** | Baris non-siaran seperti `STING`, `PSA`, atau baris kosong spreadsheet ikut tampil di tabel publik. | Logika pemrosesan baris mentah CSV memasukkan semua baris tanpa memfilter penanda internal stasiun. | Menambahkan aturan filter di `parseCsvContent()` yang mengabaikan baris dengan kata kunci STING, baris berkode warna abu-abu, dan baris tanpa judul acara. | **TERATASI (SOLVED)** |

---

## 🧪 BAB IX — PENGUJIAN BLACK BOX TESTING (PER PAGE)

Pengujian dilakukan menggunakan metode **Black Box Testing** pada setiap halaman web untuk memverifikasi fungsionalitas sistem berjalan sesuai rancangan tanpa memperhatikan struktur internal kode.

### 9.1 Halaman Publik — Beranda Rundown (`/`)
| ID Uji | Skenario Pengujian | Aksi & Input Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| **TC-PUB-01** | Memuat halaman utama | Akses URL `http://127.0.0.1:8000/` | Halaman memuat jadwal hari ini, tab hari aktif sesuai hari lokal, badge "Hari Ini" menyala jelas. | **PASS** |
| **TC-PUB-02** | Navigasi pergantian hari | Klik tab "Senin" s/d "Minggu" | URL berganti dengan parameter query `?hari=...`, data tabel berubah sesuai hari yang dipilih. | **PASS** |
| **TC-PUB-03** | Filter kategori acara | Klik chip filter "Berita" | Baris tabel yang ditampilkan hanya acara yang memiliki kategori "Berita". | **PASS** |
| **TC-PUB-04** | Reset filter kategori | Klik chip filter "Semua" | Seluruh baris program siaran ditampilkan kembali secara utuh. | **PASS** |
| **TC-PUB-05** | Pencarian instan (ditemukan) | Ketik "Kalsel" pada kotak search | Tabel secara instan hanya menampilkan acara dengan kata "Kalsel". | **PASS** |
| **TC-PUB-06** | Pencarian instan (tidak ada) | Ketik "AcaraNonExistent123" | Tampil pesan state kosong: "Tidak ada acara ditemukan". | **PASS** |
| **TC-PUB-07** | Buka pop-up modal detail | Klik salah satu baris acara siaran | Jendela modal muncul memuat judul acara, durasi, jam tayang, thumbnail, dan deskripsi acara. | **PASS** |
| **TC-PUB-08** | Pewarisan varian pada modal | Klik baris `KALSEL HARI INI (RERUN)` | Modal menampilkan thumbnail dan deskripsi yang SAMA PERSIS dengan `KALSEL HARI INI` (induk). | **PASS** |
| **TC-PUB-09** | Tutup modal detail acara | Klik tombol "Tutup" atau area overlay | Jendela modal tertutup kembali secara halus tanpa reload. | **PASS** |
| **TC-PUB-10** | Verifikasi badge status tayang | Amati kolom STATUS pada tabel | Menampilkan badge berwarna sesuai kondisi: LIVE (merah), PLAYBACK (hijau), RELAY (biru), RERUN (kuning). | **PASS** |
| **TC-PUB-11** | Lokalisasi teks tanggal | Amati teks tanggal di bawah judul | Menampilkan penanggalan berbahasa Indonesia resmi (contoh: "Sabtu, 31 Oktober 2026"). | **PASS** |

---

### 9.2 Halaman Autentikasi — Login Sistem (`/login`)
| ID Uji | Skenario Pengujian | Aksi & Input Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| **TC-LOG-01** | Login dengan data valid | Masukkan username `admin` dan password benar | Berhasil masuk sistem, session terbentuk, diarahkan ke `/admin/dashboard`. | **PASS** |
| **TC-LOG-02** | Login username salah | Masukkan username salah dan sembarang password | Muncul pesan error peringatan berbahasa Indonesia bahwa kredensial tidak sesuai. | **PASS** |
| **TC-LOG-03** | Login password salah | Masukkan username valid dan password keliru | Muncul pesan error bahwa kredensial salah, form dikembalikan tanpa membuka dashboard. | **PASS** |
| **TC-LOG-04** | Validasi kolom kosong | Klik tombol Masuk tanpa mengisi field | Validasi form browser dan Laravel menandai field wajib diisi (*required*). | **PASS** |
| **TC-LOG-05** | Proteksi akun nonaktif | Login menggunakan akun dengan status `is_active = 0` | Sistem menolak login dan menampilkan pesan: "Akun Anda telah dinonaktifkan." | **PASS** |
| **TC-LOG-06** | Pengujian Rate Limiting | Melakukan percobaan salah berturut-turut > 5 kali | Sistem mengunci form login sementara dan meminta menunggu sesuai jeda detik throttle. | **PASS** |
| **TC-LOG-07** | Akses halaman saat login | Akses URL `/login` saat telah memiliki session | Otomatis di-redirect kembali ke halaman `/admin/dashboard`. | **PASS** |

---

### 9.3 Halaman Admin — Dashboard Manajemen (`/admin/dashboard`)
| ID Uji | Skenario Pengujian | Aksi & Input Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| **TC-DSH-01** | Akses tanpa otentikasi | Akses `/admin/dashboard` langsung via URL | Middleware menolak dan mengalihkan (*redirect*) ke `/login`. | **PASS** |
| **TC-DSH-02** | Tampilan data dan statistik | Buka dashboard sebagai admin | Kartu statistik menampilkan kalkulasi total acara, jumlah live, selesai, dan menunggu secara akurat. | **PASS** |
| **TC-DSH-03** | Membuka editor detail acara | Klik baris acara pada tabel dashboard | Panel form di sebelah kanan terisi data acara yang diklik secara instan. | **PASS** |
| **TC-DSH-04** | Update deskripsi acara | Ubah teks deskripsi dan klik Simpan | Muncul notifikasi toast sukses, baris tabel terupdate via AJAX tanpa refresh layar. | **PASS** |
| **TC-DSH-05** | Upload thumbnail baru | Pilih file gambar JPG ukuran 500 KB | Gambar berhasil terunggah ke storage publik, preview foto langsung tampil di panel. | **PASS** |
| **TC-DSH-06** | Validasi ukuran file gambar | Pilih file gambar ukuran > 2 MB | Sistem menolak unggahan dan menampilkan pesan error validasi batasan ukuran. | **PASS** |
| **TC-DSH-07** | Tombol hapus thumbnail | Klik tombol "Hapus Gambar" | File thumbnail lama dihapus dari database dan preview kembali ke gambar default. | **PASS** |
| **TC-DSH-08** | Sinkronisasi manual Sheets | Klik tombol "Sync dari Spreadsheet" | Cache aplikasi di-reset, data terbaru langsung ditarik ulang dari Google Sheets. | **PASS** |
| **TC-DSH-09** | Verifikasi slider kategori | Geser slider kategori menggunakan tombol (‹ / ›) | Slider bergeser halus tanpa meluap keluar dari batas kartu antarmuka. | **PASS** |

---

### 9.4 Halaman Admin — Kelola Kategori (`/admin/kategori`)
| ID Uji | Skenario Pengujian | Aksi & Input Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| **TC-CAT-01** | Hak akses halaman | Karyawan/Admin mengakses `/admin/kategori` | Halaman daftar kategori terbuka menampilkan nama, warna, dan total program terkait. | **PASS** |
| **TC-CAT-02** | Tambah kategori baru | Input nama: "Olahraga", Warna: `#00aa00` | Kategori berhasil disimpan, muncul di tabel daftar kategori dan pilihan dropdown editor. | **PASS** |
| **TC-CAT-03** | Validasi nama duplikat | Input nama yang telah ada (contoh: "Berita") | Sistem menolak pendaftaran dan memunculkan error nama kategori sudah digunakan. | **PASS** |
| **TC-CAT-04** | Edit nama kategori (Cascade) | Ubah nama kategori "Olahraga" ke "Sport" | Nama terupdate di database, sekaligus memperbarui kolom kategori di tabel `program_details`. | **PASS** |
| **TC-CAT-05** | Hapus kategori | Hapus salah satu kategori uji coba | Kategori terhapus, program yang sebelumnya masuk kategori tersebut di-set menjadi null/tanpa kategori. | **PASS** |

---

### 9.5 Halaman Super Admin — Kelola Pengguna (`/admin/users`)
| ID Uji | Skenario Pengujian | Aksi & Input Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| **TC-USR-01** | Proteksi hak akses role | User role `karyawan` mengakses `/admin/users` | Akses ditolak oleh middleware `admin` (Error 403 / dialihkan). | **PASS** |
| **TC-USR-02** | Tambah user baru | Isi nama, username unik, password, role | Akun baru tersimpan di database dengan password ter-hash bcrypt. | **PASS** |
| **TC-USR-03** | Validasi username unik | Input username yang sudah terdaftar | Sistem menampilkan pesan validasi bahwa username tersebut telah digunakan. | **PASS** |
| **TC-USR-04** | Toggle status akun pengguna | Klik tombol saklar Nonaktifkan akun | Nilai `is_active` menjadi 0, user tersebut langsung ditolak saat mencoba login kembali. | **PASS** |
| **TC-USR-05** | Pengaktifan kembali akun | Klik tombol saklar Aktifkan akun | Nilai `is_active` kembali bernilai 1, user dapat melakukan login normal. | **PASS** |

---

### 9.6 Halaman Pengguna — Edit Profil (`/profile`)
| ID Uji | Skenario Pengujian | Aksi & Input Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| **TC-PRF-01** | Muat data profil login | Akses halaman `/profile` saat login | Data nama lengkap dan username operator saat ini terisi otomatis pada form. | **PASS** |
| **TC-PRF-02** | Perbarui nama personal | Ubah nama lengkap dan simpan | Nama di navbar dan sesi dashboard langsung berubah sesuai input baru. | **PASS** |
| **TC-PRF-03** | Perbarui password akun | Masukkan password lama valid & password baru | Password berhasil diupdate dengan hash baru; session tetap terjaga aman. | **PASS** |
| **TC-PRF-04** | Validasi password lama salah | Masukkan password lama yang keliru | Sistem menolak perubahan dan memunculkan error ketidakcocokan password saat ini. | **PASS** |
| **TC-PRF-05** | Unggah foto profil avatar | Unggah gambar avatar PNG ukuran 300 KB | Avatar foto berhasil diperbarui di pojok kanan atas navbar dan dropdown akun. | **PASS** |

---

## 🏁 BAB X — KESIMPULAN & RIWAYAT VERSI

### 10.1 Kesimpulan
Pengembangan Website Rundown Digital TVRI Kalimantan Selatan telah mencapai seluruh target fungsional dan kriteria penerimaan yang ditentukan. Sistem ini berhasil mengintegrasikan jadwal operasional Google Spreadsheet dengan antarmuka penonton publik secara real-time, stabil, dan ramah pengguna. Permasalahan warisan program turunan (*rerun / regional*) telah tuntas diatasi, format penanggalan telah dilokalisasi, serta desain arsitektur database (CDM, LDM, PDM) terdokumentasi secara standar industri.

### 10.2 Riwayat Commit Kunci (Git Version History)
| Commit Hash | Tipe & Deskripsi Perubahan |
|---|---|
| `51c2b80` | `docs: perbarui visual sub-flowcharts sesuai standar bentuk Flowchart` |
| `5cbdc91` | `docs: perbarui diagram UML, ERD (CDM, LDM, PDM), Flowchart, dan Use Case sesuai format visual standar` |
| `f660849` | `fix(ui): perbaikan slider, badge Hari Ini, dropdown user, dan format tanggal Indonesia` |
| `3caa698` | `fix: varian program (rerun/lanjutan/regional) mewarisi desc & thumbnail dari program induknya` |
| `8e8f6c2` | `feat(profile): redesign antarmuka profil, upload foto avatar, dropdown menu modern, dan role management` |
| `01c9706` | `refactor(auth): sederhanakan UI form login dan perbarui pesan error ke bahasa Indonesia` |
| `5abff11` | `feat(rundown): implementasi pop-up detail deskripsi & jam tayang acara serta styling modal` |
| `7c9d9eb` | `fix(category): setarakan posisi container statistik dan tombol dengan tabel kategori` |

*Dokumentasi ini disusun secara komprehensif untuk keperluan laporan teknis resmi dan arsip pemeliharaan sistem.*
