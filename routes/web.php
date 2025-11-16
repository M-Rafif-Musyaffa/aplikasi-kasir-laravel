<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductBatchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CapitalInjectionController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\ExpenseCategoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //product
    Route::resource('products', ProductController::class);
    Route::get('stock-in/create', [ProductBatchController::class, 'create'])->name('stock.in.create');
    Route::post('stock-in', [ProductBatchController::class, 'store'])->name('stock.in.store');
    Route::get('products/{product}/stock', [ProductController::class, 'showStock'])->name('products.stock.show');
    Route::resource('categories', CategoryController::class);
    Route::get('products-print', [ProductController::class, 'print'])->name('products.print');
    //Transaction
    Route::get('pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('reports/profit', [TransactionController::class, 'profitReport'])->name('reports.profit');
    Route::get('pos/receipt/{transaction}', [PosController::class, 'receipt'])->name('pos.receipt.show');
    // cashflow
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('expenses/print', [ExpenseController::class, 'print'])->name('expenses.print');
    Route::get('reports/cashflow', [TransactionController::class, 'cashflowReport'])->name('reports.cashflow');
    Route::get('reports/cashflow/print', [TransactionController::class, 'cashflowPrint'])->name('reports.cashflow.print');
    Route::get('capital-injections', [CapitalInjectionController::class, 'index'])->name('capital-injections.index');
    Route::post('capital-injections', [CapitalInjectionController::class, 'store'])->name('capital-injections.store');
    Route::get('capital-injections/{capitalInjection}/edit', [CapitalInjectionController::class, 'edit'])->name('capital-injections.edit');
    Route::put('capital-injections/{capitalInjection}', [CapitalInjectionController::class, 'update'])->name('capital-injections.update');
    Route::delete('capital-injections/{capitalInjection}', [CapitalInjectionController::class, 'destroy'])->name('capital-injections.destroy');
    Route::get('capital-injections/print', [CapitalInjectionController::class, 'print'])->name('capital-injections.print');
    // Pajak
    Route::get('tax-rates', [TaxRateController::class, 'index'])->name('tax-rates.index');
    Route::post('tax-rates', [TaxRateController::class, 'store'])->name('tax-rates.store');
    Route::get('tax-rates/{taxRate}/edit', [TaxRateController::class, 'edit'])->name('tax-rates.edit');
    Route::put('tax-rates/{taxRate}', [TaxRateController::class, 'update'])->name('tax-rates.update');
    Route::delete('tax-rates/{taxRate}', [TaxRateController::class, 'destroy'])->name('tax-rates.destroy');
    // Kategori Biaya
    Route::get('expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
    Route::post('expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
    Route::get('expense-categories/{expenseCategory}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-categories.edit');
    Route::put('expense-categories/{expenseCategory}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
    Route::delete('expense-categories/{expenseCategory}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
});

require __DIR__ . '/auth.php';
