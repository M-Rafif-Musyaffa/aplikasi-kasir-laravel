<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">

            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="name"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') @enderror"
                           value="{{ old('name', $category->name) }}" required>

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('categories.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Update Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
