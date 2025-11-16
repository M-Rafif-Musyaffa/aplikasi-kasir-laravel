<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Rincian Stok: {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    SKU: {{ $product->sku ?? '-' }} | Harga Jual: Rp
                    {{ number_format($product->harga_jual, 0, ',', '.') }}
                </p>
            </div>

            <a href="{{ route('products.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-150">

                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>

                Kembali ke Daftar Produk
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Daftar Batch Stok (Diurutkan berdasarkan FEFO / FIFO)
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tgl. Kedaluwarsa (FEFO)
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tgl. Masuk (FIFO)
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Beli (Modal)
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok Awal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok Sisa
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse ($batches as $batch)
                            <tr class="{{ $batch->tgl_expired && $batch->tgl_expired < now() ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($batch->tgl_expired)
                                        <span
                                            class="font-medium {{ $batch->tgl_expired < now()->addDays(30) ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ \Carbon\Carbon::parse($batch->tgl_expired)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($batch->tgl_masuk)->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    Rp {{ number_format($batch->harga_beli, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $batch->stok_awal }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $batch->stok_sisa }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada stok sisa untuk produk ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
