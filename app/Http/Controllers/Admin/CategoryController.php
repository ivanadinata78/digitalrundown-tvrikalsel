<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProgramDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori acara.
     */
    public function index(): View
    {
        // Pastikan jika tabel masih kosong, diisi dengan kategori awal
        if (Category::count() === 0) {
            (new \Database\Seeders\CategorySeeder())->run();
        }

        // Pastikan semua data program siaran sinkron di database
        ProgramDetail::syncDefaultPrograms();

        $categories = Category::with(['programs'])->withCount('programs')->orderBy('id', 'asc')->get();

        // Periksa data tayangan dari rundown
        $programs = ProgramDetail::getProgramsWithDetails();
        $totalPrograms = count($programs);
        $mappedCount = 0;
        $unmappedPrograms = [];

        foreach ($programs as $prog) {
            if (!empty($prog['kategori'])) {
                $mappedCount++;
            } else {
                $unmappedPrograms[] = $prog['acara'];
            }
        }

        $programsByCategory = collect($programs)->groupBy('kategori');

        $categories->each(function ($cat) use ($programsByCategory) {
            $catPrograms = collect($programsByCategory->get($cat->name, []));
            $knownTitles = $catPrograms->pluck('acara')->toArray();

            foreach ($cat->programs as $p) {
                if (!in_array($p->program_title, $knownTitles)) {
                    $catPrograms->push([
                        'acara'       => $p->program_title,
                        'kategori'    => $cat->name,
                        'waktu'       => '-',
                        'durasi'      => '-',
                        'status'      => 'REGULER',
                        'status_type' => 'default',
                        'description' => $p->description ?? '',
                    ]);
                }
            }

            $cat->rundown_programs = $catPrograms->values()->toArray();
            $cat->programs_count = count($cat->rundown_programs);
        });

        return view('admin.categories.index', compact('categories', 'programs', 'totalPrograms', 'mappedCount', 'unmappedPrograms'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100', 'unique:categories,name'],
            'color_hex' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Kategori ini sudah ada.',
        ]);

        Category::create([
            'name'       => trim($validated['name']),
            'color_hex'  => $validated['color_hex'] ?? '#2563eb',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('status', "Kategori '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Perbarui kategori acara.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'color_hex' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Kategori ini sudah ada.',
        ]);

        $oldName = $category->name;
        $newName = trim($validated['name']);

        $category->update([
            'name'      => $newName,
            'color_hex' => $validated['color_hex'] ?? $category->color_hex ?? '#2563eb',
        ]);

        // Jika nama kategori berubah, perbarui juga string category di program_details
        if ($oldName !== $newName) {
            ProgramDetail::where('category', $oldName)->update(['category' => $newName]);
        }

        return redirect()->route('admin.categories.index')
            ->with('status', "Kategori '{$newName}' berhasil diperbarui.");
    }

    /**
     * Hapus kategori acara.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        // Update semua data ProgramDetail yang menggunakan kategori ini agar jadi null (kosong)
        ProgramDetail::where('category', $name)->update(['category' => null]);

        return redirect()->route('admin.categories.index')
            ->with('status', "Kategori '{$name}' berhasil dihapus.");
    }
}
