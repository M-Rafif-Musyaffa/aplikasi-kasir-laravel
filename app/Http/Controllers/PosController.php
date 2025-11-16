<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PosController extends Controller
{
    /**
     * Menampilkan halaman utama Point of Sale (Kasir).
     */
    public function index()
    {
        // 1. Ambil semua produk yang stoknya > 0
        $productsWithBatches = Product::with(['batches' => function($query) {
                $query->where('stok_sisa', '>', 0);
            },'taxRate'])
            ->whereHas('batches', function($query) {
                $query->where('stok_sisa', '>', 0);
            })
            ->orderBy('name')
            ->get();

        // 2. Transformasi data di sini (DI DALAM CONTROLLER)
        $productsForPos = $productsWithBatches->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'harga_jual' => (float) $product->harga_jual,
                'total_stok' => $product->batches->sum('stok_sisa'),'tax_rate' => (float) ($product->taxRate->rate ?? 0)
            ];
        });

        // 3. Kirim data yang sudah bersih dan sederhana ke view
        return view('pos.index', [
            'productsForPos' => $productsForPos
        ]);
    }

    /**
     * Memproses checkout dan menyimpan transaksi.
     */
    public function checkout(Request $request)
    {
        //Validasi data yang masuk dari Alpine.js
        $data = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0','cart.*.tax_rate' => 'required|numeric', 'cart.*.tax_amount' => 'required|numeric',
            'payment_method' => 'required|string|in:tunai,qris',
            'cash_received' => 'required|numeric|min:0',
            'total_belanja' => 'required|numeric|min:0','subtotal' => 'required|numeric|min:0','total_discount' => 'required|numeric|min:0','total_tax' => 'required|numeric|min:0',
        ]);

        // Validasi Stok
        foreach ($data['cart'] as $item) {
            $product = Product::find($item['id']);
            $totalStokTersedia = $product->batches->sum('stok_sisa');

            if ($totalStokTersedia < $item['quantity']) {
                return response()->json([
                    'message' => "Stok untuk produk '{$product->name}' tidak mencukupi (sisa: {$totalStokTersedia})."
                ], 422);
            }
        }

        // --- MULAI LOGIKA UTAMA ---
        try {
            $transactionResult = DB::transaction(function () use ($data) {

                $totalModal = 0;
                $detailsToSave = [];

                //Loop setiap item di keranjang
                foreach ($data['cart'] as $item) {
                    $jumlahDibutuhkan = $item['quantity'];
                    $product = Product::find($item['id']);

                    // Ambil batch (Logika FEFO/FIFO)
                    $batches = $product->batches()
                        ->where('stok_sisa', '>', 0)
                        ->orderByRaw('CASE WHEN tgl_expired IS NULL THEN 1 ELSE 0 END, tgl_expired ASC, tgl_masuk ASC')
                        ->get();

                    //Loop batch untuk mengurangi stok
                    foreach ($batches as $batch) {
                        if ($jumlahDibutuhkan <= 0) break;

                        $jumlahDiambil = min($batch->stok_sisa, $jumlahDibutuhkan);

                        $batch->stok_sisa -= $jumlahDiambil;
                        $batch->save();

                        $jumlahDibutuhkan -= $jumlahDiambil;
                        $totalModal += $jumlahDiambil * $batch->harga_beli;

                        // Siapkan data untuk transaction_details
                        $detailsToSave[] = [
                            'product_id' => $item['id'],
                            'product_batch_id' => $batch->id,
                            'jumlah' => $jumlahDiambil,
                            'harga_jual_satuan' => (float) $item['price'],
                            'harga_beli_satuan' => $batch->harga_beli,'tax_rate' => (float) ($item['tax_rate'] ?? 0),'tax_amount' => (float) ($item['tax_amount'] ?? 0),'discount_amount' => 0,
                        ];
                    }
                }

                // Buat "Struk" (Tabel Transactions)
                $kembalian = ($data['payment_method'] === 'qris') ? 0 : $data['cash_received'] - $data['total_belanja'];

                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'payment_method' => $data['payment_method'],'subtotal' => $data['subtotal'],'total_discount' => $data['total_discount'],'total_tax' => $data['total_tax'],
                    'total_harga_jual' => $data['total_belanja'],
                    'total_harga_beli' => $totalModal,
                    'total_bayar' => $data['payment_method'] === 'qris' ? $data['total_belanja'] : $data['cash_received'],
                    'kembalian' => $kembalian,
                ]);

                // Simpan "Isi Struk" (Tabel TransactionDetails)
                foreach ($detailsToSave as $detail) {
                    $detail['transaction_id'] = $transaction->id; // Hubungkan ke struk
                    TransactionDetail::create($detail);
                }

                return $transaction;
            });

            // Kirim respon sukses ke frontend
            return response()->json([
                'message' => 'Transaksi berhasil disimpan!',
                'transaction_id' => $transactionResult->id // KIRIM ID ASLI
            ]);

        } catch (Throwable $e) {
            // Jika terjadi error
            return response()->json([
                'message' => 'Checkout Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman struk (receipt) setelah checkout.
     */
    public function receipt(Transaction $transaction)
    {
        // $transaction otomatis diambil dari ID di URL
        // Kita muat relasi yang dibutuhkan untuk struk
        $transaction->load(['user', 'details.product']);

        return view('pos.receipt', [
            'transaction' => $transaction
        ]);
    }
}
