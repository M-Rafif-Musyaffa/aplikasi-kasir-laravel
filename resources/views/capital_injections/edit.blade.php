<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Catatan Modal') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md"
             x-data="financeForm({{ old('amount', $capital->amount) }})" x-init="init()">

            <form action="{{ route('capital-injections.update', $capital) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date" id="date"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date') border-red-500 @enderror"
                           value="{{ old('date', $capital->date) }}" required>
                    @error('date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input type="text" name="description" id="description"
                           placeholder="Misal: Modal Awal Usaha"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                           value="{{ old('description', $capital->description) }}" required>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="amount_formatted" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                    <input type="text" id="amount_formatted"
                           x-model="amountFormatted"
                           placeholder="Misal: 10.000.000"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('amount') border-red-500 @enderror text-right"
                           required>
                    <input type="hidden" name="amount" x-model="amountRaw">
                    @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('capital-injections.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Update Modal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function financeForm(initialAmount) {
            return {
                amountRaw: initialAmount,
                amountFormatted: new Intl.NumberFormat('id-ID').format(initialAmount),

                init() {
                    this.$watch('amountRaw', value => {
                        if (value === 0 || !value) {
                            this.amountFormatted = '';
                        } else {
                            this.amountFormatted = new Intl.NumberFormat('id-ID').format(value);
                        }
                    });
                    this.$watch('amountFormatted', value => {
                        const numberValue = parseInt(value.replace(/[^0-9]/g, '')) || 0;
                        this.amountRaw = numberValue;
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
