<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('products.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <form action="{{ route('products.index') }}" method="GET" class="relative z-10">
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Filter Kategori</label>
                        <select name="category_id" id="category_id"
                                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Kategori --</option>
                            @foreach ($allCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 flex items-end justify-end space-x-3">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>

                        <button type="submit"
                                formaction="{{ route('products.print') }}"
                                formtarget="_blank"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m8 0v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m8 0h-2M9 17v-5m6 5v-4"></path></svg>
                            Cetak
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="tgl_masuk_from" class="block text-sm font-medium text-gray-700">Tgl. Masuk (Dari)</label>
                        <input type="date" name="tgl_masuk_from" id="tgl_masuk_from"
                               value="{{ request('tgl_masuk_from') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="tgl_masuk_to" class="block text-sm font-medium text-gray-700">Tgl. Masuk (Sampai)</label>
                        <input type="date" name="tgl_masuk_to" id="tgl_masuk_to"
                               value="{{ request('tgl_masuk_to') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="tgl_expired_from" class="block text-sm font-medium text-gray-700">Tgl. Kedaluwarsa (Dari)</label>
                        <input type="date" name="tgl_expired_from" id="tgl_expired_from"
                               value="{{ request('tgl_expired_from') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="tgl_expired_to" class="block text-sm font-medium text-gray-700">Tgl. Kedaluwarsa (Sampai)</label>
                        <input type="date" name="tgl_expired_to" id="tgl_expired_to"
                               value="{{ request('tgl_expired_to') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

            </div>
        </form>
        <div class="bg-white p-6 ...">

        <div class="bg-white p-6 rounded-lg shadow-md" x-data="{
            deleteModalOpen: false,
            deleteUrl: '',
            openDeleteModal(url) {
                this.deleteModalOpen = true;
                this.deleteUrl = url;
            }
        }">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Produk</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKU</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Jual</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Stok</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $product->sku ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $product->category->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">Rp
                                        {{ number_format($product->harga_jual, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $totalStok = $product->batches->sum('stok_sisa'); @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $totalStok > 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $totalStok }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('products.stock.show', $product) }}"
                                        class="inline-flex items-center px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>

                                    <button type="button"
                                        @click="openDeleteModal('{{ route('products.destroy', $product) }}')"
                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 ml-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data
                                    produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div x-show="deleteModalOpen" x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                style="display: none;">
                <div class="relative p-4 w-full max-w-md h-full md:h-auto" @click.away="deleteModalOpen = false">
                    <div class="relative bg-white rounded-lg shadow">
                        <button type="button" @click="deleteModalOpen = false"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Tutup modal</span>
                        </button>
                        <div class="p-6 text-center">
                            <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah Anda yakin ingin menghapus produk
                                ini?</h3>

                            <form x-bind:action="deleteUrl" method="POST"
                                class="inline-flex items-center justify-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                    Ya, saya yakin
                                </button>
                                <button type="button" @click="deleteModalOpen = false"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                    Batal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
