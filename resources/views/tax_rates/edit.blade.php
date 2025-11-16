<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Opsi Pajak') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">

            <form action="{{ route('tax-rates.update', $taxRate) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Pajak</label>
                    <input type="text" name="name" id="name"
                           placeholder="Misal: PPN, Bebas Pajak"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') @enderror"
                           value="{{ old('name', $taxRate->name) }}" required>
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="rate_percent" class="block text-sm font-medium text-gray-700">Tarif (%)</label>
                    <input type="number" name="rate_percent" id="rate_percent"
                           step="0.01"
                           placeholder="Misal: 11 (untuk 11%)"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('rate_percent') @enderror"
                           value="{{ old('rate_percent', $taxRate->rate * 100) }}" required>
                    @error('rate_percent') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('tax-rates.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Update Pajak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
