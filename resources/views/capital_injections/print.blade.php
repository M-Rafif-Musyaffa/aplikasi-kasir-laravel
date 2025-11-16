<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Modal Masuk</title>
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
            <h1 class="text-3xl font-bold">Laporan Modal Masuk</h1>
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
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dicatat Oleh</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $grandTotal = 0;
                @endphp

                @forelse ($capitalInjections as $capital)
                    @php
                        $grandTotal += $capital->amount;
                    @endphp
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($capital->date)->format('d M Y') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $capital->description }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $capital->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 text-right">Rp{{ number_format($capital->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">Tidak ada data modal masuk untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right text-sm uppercase">Total Modal Masuk</td>
                    <td class="px-4 py-2 whitespace-nowrap text-lg text-green-600 text-right">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    </div>

</body>
</html>
