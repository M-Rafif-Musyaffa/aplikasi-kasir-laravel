<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-0 ">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM12 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM3 10h18M3 14h18M3 6h18"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Penjualan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $transaksiHariIni }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-emerald-100 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Profit Hari Ini</p>
                        <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($profitHariIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Stok Kritis (< 10)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stokKritis }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 border">
            <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Tren Penjualan & Profit (7 Hari Terakhir)</h3>
                <div>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 ">

            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md border">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Stok Kritis (Segera Habis)</h3>
                <div class="space-y-4 max-h-[300px] overflow-y-auto">
                    @forelse ($stokKritisList as $product)
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">SKU: {{ $product->sku ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-red-600">{{ $product->batches_sum_stok_sisa }}</span>
                                <span class="text-sm text-gray-500 block">pcs</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            <p>Stok aman!</p>
                            <p class="text-sm">Tidak ada produk dengan stok kritis.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md border">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Hampir Kadaluarsa (30 Hari)</h3>
                <div class="space-y-4 max-h-[300px] overflow-y-auto">
                    @forelse ($expiringSoonList as $batch)
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $batch->product->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-sm text-gray-500">Stok Batch: <span class="font-medium">{{ $batch->stok_sisa }}</span> pcs</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-orange-600">{{ \Carbon\Carbon::parse($batch->tgl_expired)->format('d M Y') }}</span>
                                <span class="text-sm text-gray-500 block">Kadaluarsa</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            <p>Bagus!</p>
                            <p class="text-sm">Tidak ada stok yang akan kadaluarsa.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md border">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Produk Terlaris (Bulan Ini)</h3>
                <div class="max-h-full">
                    <canvas id="topSellingChart"></canvas>
                </div>
            </div>

        </div>
    </div>


    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // --- Chart 1: Tren Penjualan--
            const labels = JSON.parse('{!! $chartLabels !!}');
            const salesData = JSON.parse('{!! $chartSalesData !!}');
            const profitData = JSON.parse('{!! $chartProfitData !!}');
            const ctx = document.getElementById('salesChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Penjualan (Rp)',
                            data: salesData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.1
                        },
                        {
                            label: 'Total Profit (Rp)',
                            data: profitData,
                            borderColor: 'rgb(22, 163, 74)',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            fill: true,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // --- Chart 2: Barang Terlaris---
            const tsLabels = JSON.parse('{!! $topSellingChartLabels !!}');
            const tsData = JSON.parse('{!! $topSellingChartData !!}');
            const tsCtx = document.getElementById('topSellingChart').getContext('2d');

            new Chart(tsCtx, {
                type: 'pie', // Tipe chart: Pie
                data: {
                    labels: tsLabels, // Nama Produk
                    datasets: [{
                        label: 'Total Terjual',
                        data: tsData, // Jumlah terjual
                        backgroundColor: [ // Warna-warni
                            'rgb(59, 130, 246)',
                            'rgb(22, 163, 74)',
                            'rgb(239, 68, 68)',
                            'rgb(245, 158, 11)',
                            'rgb(107, 114, 128)',
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
