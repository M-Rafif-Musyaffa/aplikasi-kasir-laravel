<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact
            }

            .no-print {
                display: none;
            }
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
            <h1 class="text-3xl font-bold">Laporan Stok Produk</h1>
            <p class="text-gray-600">Dicetak pada: <span class="font-semibold">{{ $printDate }}</span></p>

            <h2 class="text-lg font-semibold mt-4">Filter Aktif:</h2>
            <div class="text-sm text-gray-600 grid grid-cols-2">
                <p>Kategori: <span class="font-semibold">{{ $filteredCategoryName }}</span></p>

                <p>Tgl. Masuk:
                    <span class="font-semibold">
                        {{ $filters['tgl_masuk_from'] ? \Carbon\Carbon::parse($filters['tgl_masuk_from'])->format('d M Y') : '...' }}
                    </span>
                    -
                    <span class="font-semibold">
                        {{ $filters['tgl_masuk_to'] ? \Carbon\Carbon::parse($filters['tgl_masuk_to'])->format('d M Y') : '...' }}
                    </span>
                </p>

                <p>Tgl. Kedaluwarsa:
                    <span class="font-semibold">
                        {{ $filters['tgl_expired_from'] ? \Carbon\Carbon::parse($filters['tgl_expired_from'])->format('d M Y') : '...' }}
                    </span>
                    -
                    <span class="font-semibold">
                        {{ $filters['tgl_expired_to'] ? \Carbon\Carbon::parse($filters['tgl_expired_to'])->format('d M Y') : '...' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="space-y-6">

            @forelse ($products as $product)
                <div class="p-4 border rounded-lg break-inside-avoid-page">
                    <div class="mb-4">
                        <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                        <div class="flex space-x-4 text-sm text-gray-700">
                            <span>SKU: <span class="font-medium">{{ $product->sku ?? '-' }}</span></span>
                            <span>|</span>
                            <span>Harga Jual: <span class="font-medium">Rp
                                    {{ number_format($product->harga_jual, 0, ',', '.') }}</span></span>
                            <span>|</span>
                            <span>Total Stok: <span
                                    class="font-bold text-lg text-black">{{ $product->batches->sum('stok_sisa') }}</span></span>
                        </div>
                    </div>

                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Rincian Batch (Diurutkan FEFO/FIFO):</h4>
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl.
                                    Kedaluwarsa</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl. Masuk
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga Beli
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stok Sisa
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($product->batches->where('stok_sisa', '>', 0)->sortBy(fn($batch) => [$batch->tgl_expired ?? '9999-12-31', $batch->tgl_masuk]) as $batch)
                                <tr>
                                    <td
                                        class="px-4 py-2 whitespace-nowrap text-sm {{ $batch->tgl_expired && $batch->tgl_expired < now()->addDays(30) ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                        {{ $batch->tgl_expired ? \Carbon\Carbon::parse($batch->tgl_expired)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($batch->tgl_masuk)->format('d M Y') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">Rp
                                        {{ number_format($batch->harga_beli, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $batch->stok_sisa }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">Tidak ada
                                        stok sisa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="text-center text-gray-500">
                    Tidak ada produk yang ditemukan untuk filter ini.
                </div>
            @endforelse

        </div>
    </div>

</body>

</html>
