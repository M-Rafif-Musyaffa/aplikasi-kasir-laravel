<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\CapitalInjection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Menampilkan halaman riwayat transaksi (dengan filter).
     */
    public function index(Request $request)
    {
        // Ambil semua kasir untuk dropdown filter
        $users = User::orderBy('name')->get();

        // Mulai kueri
        $query = Transaction::with('user')->latest();

        // Terapkan filter tanggal (Dari)
        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        });

        // Terapkan filter tanggal (Sampai)
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        });

        // Terapkan filter metode pembayaran
        $query->when($request->filled('payment_method'), function ($q) use ($request) {
            $q->where('payment_method', $request->payment_method);
        });

        // Terapkan filter kasir (user_id)
        $query->when($request->filled('user_id'), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        // Ambil hasil dengan paginasi
        $transactions = $query->paginate(20)->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'users' => $users,
        ]);
    }

    /**
     * Menampilkan halaman cetak riwayat transaksi.
     */
    public function print(Request $request)
    {
        // --- LOGIKA FILTER ---
        $query = Transaction::with(['user', 'details.product'])->latest();

        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        });
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        });
        $query->when($request->filled('payment_method'), function ($q) use ($request) {
            $q->where('payment_method', $request->payment_method);
        });
        $query->when($request->filled('user_id'), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        $transactions = $query->get();

        // Ambil data untuk judul laporan
        $filterUser = $request->filled('user_id') ? User::find($request->user_id) : null;

        return view('transactions.print', [
            'transactions' => $transactions,
            'filters' => $request->all(),
            'filterUserName' => $filterUser ? $filterUser->name : 'Semua Kasir',
            'printDate' => now()->format('d M Y, H:i:s'),
        ]);
    }

    /**
     * Menampilkan detail spesifik dari satu transaksi.
     */
    public function show(Transaction $transaction)
    {

        $transaction->load(['user', 'details.product']);

        return view('transactions.show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Menampilkan laporan profit yang dikelompokkan.
     */
    public function profitReport(Request $request)
    {
        //Mulai kueri, kita akan pilih kolom secara spesifik
        $query = Transaction::select(
            // Pilih tanggal (tanpa jam)
            DB::raw('DATE(created_at) as tanggal'),
            // Hitung jumlah transaksi
            DB::raw('COUNT(id) as jumlah_transaksi'),
            // Jumlahkan total penjualan
            DB::raw('SUM(total_harga_jual) as total_penjualan'),
            // Jumlahkan total modal
            DB::raw('SUM(total_harga_beli) as total_modal'),
            // Hitung total profit
            DB::raw('SUM(total_harga_jual - total_harga_beli) as total_profit')
        )
        ->groupBy('tanggal');

        // Terapkan filter tanggal
        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        });
        $query->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        });

        //Ambil data, urutkan dari tanggal terbaru, dan paginasi
        $laporanProfit = $query->orderBy('tanggal', 'desc')->paginate(30)->withQueryString();

        // Kirim data ke view
        return view('transactions.profit_report', [
            'laporanProfit' => $laporanProfit
        ]);
    }

    /**
     * Menampilkan laporan cashflow (arus kas).
     */
    public function cashflowReport(Request $request)
    {
        // Kueri untuk UANG MASUK (dari tabel transactions)
        $uangMasuk = DB::table('transactions')
                        ->select(
                            DB::raw('DATE(created_at) as tanggal'),
                            DB::raw('SUM(total_bayar) as uang_masuk'),
                            DB::raw('0 as uang_keluar') // Kolom palsu
                        )
                        ->groupBy('tanggal');

        //Kueri untuk UANG KELUAR (dari tabel expenses)
        $uangKeluar = DB::table('expenses')
                        ->select(
                            DB::raw('DATE(expense_date) as tanggal'),
                            DB::raw('0 as uang_masuk'), // Kolom palsu
                            DB::raw('SUM(amount) as uang_keluar')
                        )
                        ->groupBy(DB::raw('DATE(expense_date)')); // <-- PERBAIKAN BUG

        // Kueri BARU untuk MODAL MASUK (dari tabel capital_injections)
        $modalMasuk = DB::table('capital_injections')
                        ->select(
                            DB::raw('DATE(date) as tanggal'),
                            DB::raw('SUM(amount) as uang_masuk'), // Ini adalah Uang Masuk
                            DB::raw('0 as uang_keluar') // Kolom palsu
                        )
                        ->groupBy('tanggal');

        //Terapkan Filter Tanggal (jika ada) ke KETIGA kueri
        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from);
            $uangMasuk->where('created_at', '>=', $dateFrom);
            $uangKeluar->where('expense_date', '>=', $dateFrom);
            $modalMasuk->where('date', '>=', $dateFrom);
        }
        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to);
            $uangMasuk->where('created_at', '<=', $dateTo);
            $uangKeluar->where('expense_date', '<=', $dateTo);
            $modalMasuk->where('date', '<=', $dateTo);
        }

        // Gabungkan KETIGA kueri (UNION ALL)
        $combined = $uangMasuk->unionAll($uangKeluar)
                             ->unionAll($modalMasuk);

        // Kelompokkan lagi data yang sudah digabung
        $laporanArusKas = DB::table($combined, 'cashflow')
            ->select(
                'tanggal',
                DB::raw('SUM(uang_masuk) as total_uang_masuk'),
                DB::raw('SUM(uang_keluar) as total_uang_keluar'),
                DB::raw('SUM(uang_masuk) - SUM(uang_keluar) as arus_kas_bersih')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->paginate(30)
            ->withQueryString();

        // Kirim data ke view
        return view('transactions.cashflow_report', [
            'laporanArusKas' => $laporanArusKas
        ]);
    }

    /**
     * Menampilkan halaman cetak laporan cashflow.
     */
    public function cashflowPrint(Request $request)
    {
        // 1. Kueri Uang Masuk
        $uangMasuk = DB::table('transactions')
                        ->select(
                            DB::raw('DATE(created_at) as tanggal'),
                            DB::raw('SUM(total_bayar) as uang_masuk'),
                            DB::raw('0 as uang_keluar')
                        )
                        ->groupBy('tanggal');

        // Kueri Uang Keluar
        $uangKeluar = DB::table('expenses')
                        ->select(
                            DB::raw('DATE(expense_date) as tanggal'),
                            DB::raw('0 as uang_masuk'),
                            DB::raw('SUM(amount) as uang_keluar')
                        )
                        ->groupBy(DB::raw('DATE(expense_date)'));

        //Kueri BARU untuk MODAL MASUK
        $modalMasuk = DB::table('capital_injections')
                        ->select(
                            DB::raw('DATE(date) as tanggal'),
                            DB::raw('SUM(amount) as uang_masuk'),
                            DB::raw('0 as uang_keluar')
                        )
                        ->groupBy('tanggal');

        //Terapkan Filter Tanggal
        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from);
            $uangMasuk->where('created_at', '>=', $dateFrom);
            $uangKeluar->where('expense_date', '>=', $dateFrom);
            $modalMasuk->where('date', '>=', $dateFrom);
        }
        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to);
            $uangMasuk->where('created_at', '<=', $dateTo);
            $uangKeluar->where('expense_date', '<=', $dateTo);
            $modalMasuk->where('date', '<=', $dateTo);
        }

        //Gabungkan (UNION)
        $combined = $uangMasuk->unionAll($uangKeluar)
                             ->unionAll($modalMasuk);

        //Kelompokkan lagi (get() bukan paginate())
        $laporanArusKas = DB::table($combined, 'cashflow')
            ->select(
                'tanggal',
                DB::raw('SUM(uang_masuk) as total_uang_masuk'),
                DB::raw('SUM(uang_keluar) as total_uang_keluar'),
                DB::raw('SUM(uang_masuk) - SUM(uang_keluar) as arus_kas_bersih')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        //Kirim data ke view cetak
        return view('transactions.cashflow_print', [
            'laporanArusKas' => $laporanArusKas,
            'filters' => $request->all(),
            'printDate' => now()->format('d M Y, H:i:s'),
        ]);
    }
}
