<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori biaya & formulir tambah.
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::withCount('expenses')->latest()->get();
        return view('expense_categories.index', [
            'expenseCategories' => $expenseCategories
        ]);
    }

    /**
     * Menyimpan kategori biaya baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('expense-categories.index')
                         ->with('success', 'Kategori biaya baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense_categories.edit', [
            'expenseCategory' => $expenseCategory
        ]);
    }

    /**
     * Memperbarui kategori biaya.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('expense_categories')->ignore($expenseCategory->id),
            ],
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('expense-categories.index')
                         ->with('success', 'Kategori biaya berhasil diperbarui.');
    }

    /**
     * Menghapus kategori biaya.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Cek dulu jika ada biaya yang terkait
        $expenseCount = $expenseCategory->expenses()->count();
        if ($expenseCount > 0) {
            return redirect()->route('expense-categories.index')
                                 ->with('error', "Kategori tidak bisa dihapus karena masih digunakan oleh {$expenseCount} catatan biaya.");
        }

        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')
                         ->with('success', 'Kategori biaya berhasil dihapus.');
    }
}
