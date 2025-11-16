<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body class="bg-white" onload="window.print()">

    <div class="p-8 max-w-4xl mx-auto">

        <button onclick="window.print()"
            class="no-print mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Cetak Laporan
        </button>

        <div class="border-b pb-4 mb-6">
            <h1 class="text-3xl font-bold">Laporan Riwayat Transaksi</h1>
            <p class="text-gray-600">Dicetak pada: <span class="font-semibold">{{ $printDate }}</span></p>

            <h2 class="text-lg font-semibold mt-4">Filter Aktif:</h2>
            <div class="text-sm text-gray-600 grid grid-cols-2">
                <p>Tgl. Transaksi:
                    <span class="font-semibold">
                        {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') : '...' }}
                    </span> -
                    <span class="font-semibold">
                        {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') : '...' }}
                    </span>
                </p>
                <p>Metode Bayar: <span class="font-semibold">{{ $filters['payment_method'] ?? 'Semua' }}</span></p>
                <p>Kasir: <span class="font-semibold">{{ $filterUserName }}</span></p>
            </div>
        </div>


        <div class="space-y-6">
            @php
                // Siapkan variabel untuk total keseluruhan
                $grandTotal = 0;
                $grandModal = 0;
                $grandProfit = 0;
            @endphp

            @forelse ($transactions as $transaction)
                @php
                    // Akumulasi total
                    $profit = $transaction->total_harga_jual - $transaction->total_harga_beli;
                    $grandTotal += $transaction->total_harga_jual;
                    $grandModal += $transaction->total_harga_beli;
                    $grandProfit += $profit;
                @endphp

                <div class="border rounded-lg break-inside-avoid-page">

                    <div class="bg-gray-100 p-4 rounded-t-lg flex flex-col md:flex-row justify-between md:items-center">
                        <div>
                            <h3 class="text-lg font-bold">ID Struk: TRX-{{ $transaction->id }}</h3>
                            <p class="text-sm text-gray-600">Kasir: <span
                                    class="font-medium">{{ $transaction->user->name ?? 'N/A' }}</span></p>
                        </div>
                        <div class="text-left md:text-right mt-2 md:mt-0">
                            <p class="text-sm text-gray-600">Tanggal: <span
                                    class="font-medium">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}</span>
                                </j>
                            <p class="text-sm text-gray-600">Metode: <span
                                    class="font-medium">{{ ucfirst($transaction->payment_method) }}</span></p>
                        </div>
                    </div>

                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga
                                    Satuan</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                    Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transaction->details as $detail)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $detail->jumlah }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        Rp{{ number_format($detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right">
                                        Rp{{ number_format($detail->jumlah * $detail->harga_jual_satuan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="bg-gray-100 p-3 rounded-b-lg text-right">
                        <div class="text-sm">Total Modal:
                            Rp{{ number_format($transaction->total_harga_beli, 0, ',', '.') }}</div>
                        <div class="text-md font-semibold">Total Belanja:
                            Rp{{ number_format($transaction->total_harga_jual, 0, ',', '.') }}</div>
                        <div class="text-lg font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Profit: Rp{{ number_format($profit, 0, ',', '.') }}
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center text-gray-500">
                    Tidak ada data transaksi untuk filter ini.
                </div>
            @endforelse

            <div class="pt-6 border-t-2 border-gray-300 mt-8">
                <h2 class="text-xl font-bold mb-2">Total Keseluruhan (Laporan)</h2>
                <div class="grid grid-cols-3 gap-4 font-medium">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="text-sm uppercase text-gray-500">Total Penjualan</div>
                        <div class="text-2xl">Rp{{ number_format($grandTotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="text-sm uppercase text-gray-500">Total Modal</div>
                        <div class="text-2xl">Rp{{ number_format($grandModal, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <div class="text-sm uppercase text-green-700">Total Profit</div>
                        <div class="text-2xl font-bold text-green-700">
                            Rp{{ number_format($grandProfit, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
