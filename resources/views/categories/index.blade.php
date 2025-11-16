<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori Produk') }}
        </h2>
    </x-slot>

    <div class="py-0">

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Kategori Baru</h3>

                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') @enderror"
                                value="{{ old('name') }}" required>

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                Simpan Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2" x-data="{
                deleteModalOpen: false,
                deleteUrl: '',
                openDeleteModal(url) {
                    this.deleteModalOpen = true;
                    this.deleteUrl = url;
                }
            }">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Kategori</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kategori</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Produk</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">{{ $category->products_count }}
                                                Produk</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('categories.edit', $category) }}"
                                                class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </a>
                                            <button type="button"
                                                @click="openDeleteModal('{{ route('categories.destroy', $category) }}')"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 ml-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data kategori.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                                <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah Anda yakin ingin menghapus
                                    kategori ini?</h3>

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
    </div>
</x-app-layout>
