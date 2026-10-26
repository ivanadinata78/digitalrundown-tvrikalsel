<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $categories = Category::orderBy('id', 'asc')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Kategori ini sudah ada.',
        ]);

        Category::create([
            'name'       => $validated['name'],
            'color_hex'  => '#2563eb',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('status', "Kategori '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Hapus kategori acara.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('status', "Kategori '{$name}' berhasil dihapus.");
    }
}
