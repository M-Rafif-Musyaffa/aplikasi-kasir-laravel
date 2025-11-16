<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori DAN formulir tambah.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit kategori.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Memperbarui kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        if ($category->products_count > 0) {
            return redirect()->route('categories.index')
                             ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
