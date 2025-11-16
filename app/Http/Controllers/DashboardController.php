<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\ProductBatch;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data dinamis.
     */
    public function index()
    {
        // === 1. LOGIKA UNTUK 4 KARTU KPI ===
        $today = Carbon::today();

        $penjualanHariIni = Transaction::whereDate('created_at', $today)
                                    ->sum('total_harga_jual');

        $transaksiHariIni = Transaction::whereDate('created_at', $today)
                                     ->count();

        $profitHariIni = Transaction::whereDate('created_at', $today)
                                  ->sum(DB::raw('total_harga_jual - total_harga_beli'));

        // === 2. LOGIKA UNTUK STOK KRITIS ===
        $productsWithStock = Product::withSum('batches', 'stok_sisa')->get();
        $stokKritisList = $productsWithStock->filter(function ($product) {
            $totalStok = $product->batches_sum_stok_sisa ?? 0;
            return $totalStok > 0 && $totalStok <= 10;
        })
        ->sortBy('batches_sum_stok_sisa')
        ->take(5);
        $stokKritisCount = $stokKritisList->count();


        // === 3. LOGIKA CHART 7 HARI ===
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        $salesData = Transaction::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total_harga_jual) as total_penjualan'),
            DB::raw('SUM(total_harga_jual - total_harga_beli) as total_profit')
        )
        ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get()
        ->keyBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = Carbon::today()->subDays($i)->format('Y-m-d');
        }
        $dates = array_reverse($dates);
        $chartLabels = [];
        $chartSalesData = [];
        $chartProfitData = [];

        foreach ($dates as $date) {
            $chartLabels[] = Carbon::parse($date)->format('d M');
            if (isset($salesData[$date])) {
                $chartSalesData[] = $salesData[$date]->total_penjualan;
                $chartProfitData[] = $salesData[$date]->total_profit;
            } else {
                $chartSalesData[] = 0;
                $chartProfitData[] = 0;
            }
        }

        // === 4. DATA BARU: BARANG HAMPIR KADALUWARSA ===
        $expiringSoonList = ProductBatch::with('product')
            ->where('stok_sisa', '>', 0)
            ->whereNotNull('tgl_expired')
            ->whereBetween('tgl_expired', [Carbon::today(), Carbon::today()->addDays(30)]) // 30 hari dari sekarang
            ->orderBy('tgl_expired', 'asc') // Paling cepat expired di atas
            ->take(5) // Ambil 5 teratas
            ->get();

        // === 5. DATA BARU: CHART BARANG TERLARIS (BULAN INI) ===
        $topSellingData = TransactionDetail::select(
                'product_id',
                DB::raw('SUM(jumlah) as total_terjual')
            )
            ->with('product') // Muat info produk
            ->whereHas('transaction', function ($query) {
                // Filter hanya transaksi bulan ini
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            })
            ->groupBy('product_id')
            ->orderBy('total_terjual', 'desc')
            ->take(5) // Ambil 5 teratas
            ->get();

        $topSellingChartLabels = json_encode($topSellingData->pluck('product.name'));
        $topSellingChartData = json_encode($topSellingData->pluck('total_terjual'));


        // === 6. KIRIM SEMUA DATA KE VIEW ===
        return view('dashboard', [
            // Data KPI
            'penjualanHariIni' => $penjualanHariIni,
            'transaksiHariIni' => $transaksiHariIni,
            'profitHariIni' => $profitHariIni,
            'stokKritis' => $stokKritisCount,
            'stokKritisList' => $stokKritisList,

            // Data Grafik 7-Hari
            'chartLabels' => json_encode($chartLabels),
            'chartSalesData' => json_encode($chartSalesData),
            'chartProfitData' => json_encode($chartProfitData),

            // Data Widget Baru
            'expiringSoonList' => $expiringSoonList,
            'topSellingChartLabels' => $topSellingChartLabels,
            'topSellingChartData' => $topSellingChartData,
        ]);
    }
}
