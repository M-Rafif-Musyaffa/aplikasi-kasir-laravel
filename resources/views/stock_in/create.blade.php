<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Formulir Barang Masuk (Stock In)') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">

            <form action="{{ route('stock.in.store') }}" method="POST">
                @csrf <div class="mb-4">
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Pilih Produk</label>
                    <select name="product_id" id="product_id"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">-- Pilih salah satu produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Masuk</label>
                        <input type="number" name="jumlah" id="jumlah"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required placeholder="Contoh: 100">
                    </div>

                    <div>
                        <label for="harga_beli" class="block text-sm font-medium text-gray-700">Harga Beli (per unit)</label>
                        <input type="number" name="harga_beli" id="harga_beli"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required placeholder="Contoh: 4000">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="tgl_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                        <input type="date" name="tgl_masuk" id="tgl_masuk"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required value="{{ date('Y-m-d') }}"> </div>

                    <div>
                        <label for="tgl_expired" class="block text-sm font-medium text-gray-700">Tanggal Kedaluwarsa (Opsional)</label>
                        <input type="date" name="tgl_expired" id="tgl_expired"
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Simpan Stok
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
