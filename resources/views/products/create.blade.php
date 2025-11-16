<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">

            <form action="{{ route('products.store') }}" method="POST">
                @csrf <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        (Opsional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori (Opsional)</label>
                    <select name="category_id" id="category_id"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Tidak Ada Kategori --</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU (Opsional)</label>
                        <input type="text" name="sku" id="sku"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual</label>
                        <input type="number" name="harga_jual" id="harga_jual"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required placeholder="Contoh: 15000">
                    </div>

                    <div>
                        <label for="tax_rate_id" class="block text-sm font-medium text-gray-700">Pajak
                            (Opsional)</label>
                        <select name="tax_rate_id" id="tax_rate_id"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Pajak --</option>
                            @foreach ($taxRates as $tax)
                                <option value="{{ $tax->id }}"
                                    {{ old('tax_rate_id') == $tax->id ? 'selected' : '' }}>
                                    {{ $tax->name }} ({{ $tax->rate * 100 }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('products.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Simpan Produk
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
