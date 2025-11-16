<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Expense;
use App\Models\User;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Menampilkan daftar biaya DAN formulir tambah (dengan filter).
     */
    public function index(Request $request)
    {
        // Ambil data untuk filter
        $users = User::orderBy('name')->get();
        // Ambil data untuk dropdown formulir
        $expenseCategories = ExpenseCategory::orderBy('name')->get();

        // Mulai kueri
        $query = Expense::with(['user', 'category'])->latest('expense_date');

        // Terapkan filter tanggal (Dari)
        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('expense_date', '>=', Carbon::parse($request->date_from));
        });

        // Terapkan filter tanggal (Sampai)
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('expense_date', '<=', Carbon::parse($request->date_to));
        });

        // Terapkan filter kasir (user_id)
        $query->when($request->filled('user_id'), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        // Terapkan filter kategori biaya
        $query->when($request->filled('expense_category_id'), function ($q) use ($request) {
            $q->where('expense_category_id', $request->expense_category_id);
        });

        $expenses = $query->paginate(20)->withQueryString();

        return view('expenses.index', [
            'expenses' => $expenses,
            'users' => $users,
            'expenseCategories' => $expenseCategories,
        ]);
    }

    /**
     * Menyimpan biaya baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_category_id' => 'required|exists:expense_categories,id',
        ]);

        $dataToSave = array_merge($validated, [
            'user_id' => Auth::id(),
        ]);

        Expense::create($dataToSave);

        return redirect()->route('expenses.index')
                         ->with('success', 'Biaya berhasil dicatat.');
    }

    /**
     * Menampilkan formulir untuk mengedit biaya.
     */
    public function edit(Expense $expense)
    {
        // Kita juga perlu mengirim daftar kategori ke halaman edit
        $expenseCategories = ExpenseCategory::orderBy('name')->get();

        return view('expenses.edit', [
            'expense' => $expense,
            'expenseCategories' => $expenseCategories
        ]);
    }

    /**
     * Memperbarui biaya di database.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_category_id' => 'required|exists:expense_categories,id', // Validasi kategori
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
                         ->with('success', 'Biaya berhasil diperbarui.');
    }

    /**
     * Menghapus biaya dari database.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
                         ->with('success', 'Biaya berhasil dihapus.');
    }

    /**
     * Menampilkan halaman cetak biaya operasional.
     */
    public function print(Request $request)
    {
        // --- LOGIKA FILTER ---
        $query = Expense::with(['user', 'category'])->latest('expense_date');

        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('expense_date', '>=', Carbon::parse($request->date_from));
        });
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('expense_date', '<=', Carbon::parse($request->date_to));
        });
        $query->when($request->filled('user_id'), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });
        $query->when($request->filled('expense_category_id'), function ($q) use ($request) {
            $q->where('expense_category_id', $request->expense_category_id);
        });
        // --- AKHIR LOGIKA FILTER ---

        $expenses = $query->get();

        // Ambil data untuk judul laporan
        $filterUser = $request->filled('user_id') ? User::find($request->user_id) : null;
        $filterCategory = $request->filled('expense_category_id') ? ExpenseCategory::find($request->expense_category_id) : null;


        return view('expenses.print', [
            'expenses' => $expenses,
            'filters' => $request->all(),
            'filterUserName' => $filterUser ? $filterUser->name : 'Semua Kasir',
            'filterCategoryName' => $filterCategory ? $filterCategory->name : 'Semua Kategori',
            'printDate' => now()->format('d M Y, H:i:s'),
        ]);
    }
}
