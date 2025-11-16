<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arus Kas (Cashflow)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white" onload="window.print()">

    <div class="p-8 max-w-4xl mx-auto">

        <button onclick="window.print()" class="no-print mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Cetak Laporan
        </button>

        <div class="border-b pb-4 mb-6">
            <h1 class="text-3xl font-bold">Laporan Arus Kas (Cashflow)</h1>
            <p class="text-gray-600">Dicetak pada: <span class="font-semibold">{{ $printDate }}</span></p>

            <h2 class="text-lg font-semibold mt-4">Filter Aktif:</h2>
            <div class="text-sm text-gray-600">
                <p>Periode Tanggal:
                    <span class="font-semibold">
                        {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') : '...' }}
                    </span> -
                    <span class="font-semibold">
                        {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') : '...' }}
                    </span>
                </p>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Uang Masuk</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Uang Keluar</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Arus Kas Bersih</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $totalMasuk = 0;
                    $totalKeluar = 0;
                    $totalBersih = 0;
                @endphp

                @forelse ($laporanArusKas as $laporan)
                    @php
                        $totalMasuk += $laporan->total_uang_masuk;
                        $totalKeluar += $laporan->total_uang_keluar;
                        $totalBersih += $laporan->arus_kas_bersih;
                    @endphp
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-green-600 text-right">Rp{{ number_format($laporan->total_uang_masuk, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-red-600 text-right">Rp{{ number_format($laporan->total_uang_keluar, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-bold {{ $laporan->arus_kas_bersih >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">Rp{{ number_format($laporan->arus_kas_bersih, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">Tidak ada data untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td class="px-4 py-2 text-right text-sm uppercase">Total Keseluruhan</td>
                    <td class="px-4 py-2 whitespace-nowrap text-lg text-green-600 text-right">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-lg text-red-600 text-right">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-lg {{ $totalBersih >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">Rp{{ number_format($totalBersih, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    </div>

</body>
</html>
