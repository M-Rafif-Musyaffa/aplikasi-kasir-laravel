<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ProductBatchController extends Controller
{

    public function create()
    {
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('stock_in.create', [
            'products' => $products
        ]);
    }
    /**
 * Menyimpan batch (barang masuk) baru ke database.
 */
public function store(Request $request)
{
    // Validasi data
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'jumlah' => 'required|integer|min:1',
        'harga_beli' => 'required|numeric|min:0',
        'tgl_masuk' => 'required|date',
        'tgl_expired' => 'nullable|date|after_or_equal:tgl_masuk',
    ]);

    // Siapkan data untuk batch
    $dataToSave = [
        'product_id' => $validated['product_id'],
        'harga_beli' => $validated['harga_beli'],
        'stok_awal' => $validated['jumlah'],
        'stok_sisa' => $validated['jumlah'],
        'tgl_masuk' => $validated['tgl_masuk'],
        'tgl_expired' => $validated['tgl_expired'],
    ];

    // 3. Simpan data batch
    ProductBatch::create($dataToSave);

    // Catat pembelian ini sebagai "Uang Keluar" di tabel Expenses
    // Ambil nama produk untuk deskripsi
    $product = Product::find($validated['product_id']);
    $totalPembelian = $validated['jumlah'] * $validated['harga_beli'];

    Expense::create([
        'expense_date' => $validated['tgl_masuk'],
        'description' => "Pembelian Stok: {$validated['jumlah']} x {$product->name}",
        'amount' => $totalPembelian,
        'user_id' => Auth::id(),
    ]);

    // Alihkan pengguna kembali
    return redirect()->route('products.index')
                     ->with('success', 'Stok baru berhasil ditambahkan DAN dicatat sebagai biaya.');
}
}
