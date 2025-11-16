<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Transaksi Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-xl mx-auto" id="receipt-container">

            <div class="mb-6 flex justify-between no-print ">
                <a href="{{ route('pos.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Transaksi Baru
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m8 0v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m8 0h-2M9 17v-5m6 5v-4">
                        </path>
                    </svg>
                    Cetak Struk
                </button>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-md border" id="receipt">
                <div class="text-center mb-6 ">
                    <h3 class="text-2xl font-bold">KasirApp</h3>
                    <p class="text-gray-500 text-sm">Jalan Aplikasi No. 123, Purwakarta</p>
                </div>

                <div class="text-sm text-gray-700 mb-4 space-y-1 ">
                    <div class="flex justify-between">
                        <span>ID Struk:</span>
                        <span class="font-medium">TRX-{{ $transaction->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal:</span>
                        <span class="font-medium">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kasir:</span>
                        <span class="font-medium">{{ $transaction->user->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <hr class="my-4 border-dashed">

                <div class="space-y-2">
                    @foreach ($transaction->details as $detail)
                        <div class="text-sm">
                            <p class="font-medium text-gray-800">{{ $detail->product->name ?? 'Produk Dihapus' }}</p>
                            <div class="flex justify-between text-gray-600">
                                <span>{{ $detail->jumlah }} x Rp
                                    {{ number_format($detail->harga_jual_satuan, 0, ',', '.') }}</span>
                                <span class="font-medium">Rp
                                    {{ number_format($detail->jumlah * $detail->harga_jual_satuan, 0, ',', '.') }}</span>
                            </div>
                            @if ($detail->tax_amount > 0)
                                <div class="flex justify-between text-gray-500 text-xs pl-4">
                                    <span>(Pajak {{ $detail->tax_rate * 100 }}%)</span>
                                    <span class="font-medium">Rp
                                        {{ number_format($detail->tax_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <hr class="my-4 border-dashed">

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-medium">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Diskon:</span>
                        <span class="font-medium">- Rp
                            {{ number_format($transaction->total_discount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Total Pajak:</span>
                        <span class="font-medium">Rp {{ number_format($transaction->total_tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-black border-t pt-2 mt-2">
                        <span>Total Akhir:</span>
                        <span>Rp {{ number_format($transaction->total_harga_jual, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between text-gray-700">
                            <span>Metode:</span>
                            <span class="font-medium">{{ ucfirst($transaction->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Bayar:</span>
                            <span class="font-medium">Rp
                                {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Kembalian:</span>
                            <span class="font-medium">Rp
                                {{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <p class="text-center text-gray-500 text-xs mt-6">
                    Terima kasih telah berbelanja!
                </p>
            </div>

        </div>
    </div>

    <style>
        @media print {

            /* Sembunyikan tombol aksi */
            .no-print {
                display: none;
            }

            /* Sembunyikan SEMUA elemen di body secara default */
            body * {
                visibility: hidden;
            }

            /* Tampilkan HANYA elemen #receipt dan semua elemen di DALAMNYA */
            #receipt,
            #receipt * {
                visibility: visible;
            }

            /* Atur posisi dan lebar struk untuk printer */
            #receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
                /* Sesuaikan (bisa juga 58mm jika printer Anda kecil) */
                margin: 0;
                padding: 10px;
                font-size: 10pt;
                box-shadow: none;
                border-radius: 0;
                ;
            }
        }
    </style>
</x-app-layout>
