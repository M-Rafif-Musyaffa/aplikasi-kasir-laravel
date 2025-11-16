<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Detail Transaksi (Struk)
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    ID Struk: <span class="font-medium">TRX-{{ $transaction->id }}</span>
                </p>
            </div>
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Riwayat Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Barang yang di-Checkout</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($transaction->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $detail->product->name ?? 'Produk Dihapus' }}</div>
                                            <div class="text-xs text-gray-500">Modal: Rp {{ number_format($detail->harga_beli_satuan, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->jumlah }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($detail->jumlah * $detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md space-y-4 border">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 ">Rangkuman Struk</h3>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tanggal:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kasir:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Metode Bayar:</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->payment_method) }}</span>
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Belanja:</span>
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total_harga_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Modal (Beli):</span>
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total_harga_beli, 0, ',', '.') }}</span>
                        </div>

                        @if($transaction->payment_method == 'tunai')
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tunai Diterima:</span>
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Kembalian:</span>
                                <span class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-green-600">Total Profit:</span>
                                <span class="text-lg font-semibold text-green-600">Rp {{ number_format($transaction->total_harga_jual - $transaction->total_harga_beli, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
