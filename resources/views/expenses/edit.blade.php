<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Biaya Operasional') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md" x-data="expenseForm({{ old('amount', $expense->amount) }})" x-init="init()">

            <form action="{{ route('expenses.update', $expense) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-4">
                    <label for="expense_date" class="block text-sm font-medium text-gray-700">Tanggal Biaya</label>
                    <input type="date" name="expense_date" id="expense_date"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('expense_date') @enderror"
                        value="{{ old('expense_date', $expense->expense_date) }}" required>
                    @error('expense_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input type="text" name="description" id="description" placeholder="Misal: Bayar Listrik"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') @enderror"
                        value="{{ old('description', $expense->description) }}" required>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="expense_category_id" class="block text-sm font-medium text-gray-700">Kategori
                        Biaya</label>
                    <select name="expense_category_id" id="expense_category_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('expense_category_id') @enderror"
                        required>
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($expenseCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('expense_category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="amount_formatted" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>

                    <input type="text" id="amount_formatted" x-model="amountFormatted" placeholder="Misal: 300.000"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('amount') @enderror text-right"
                        required>

                    <input type="hidden" name="amount" x-model="amountRaw">

                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('expenses.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Update Biaya
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function expenseForm(initialAmount) {
                return {
                    amountRaw: initialAmount,
                    amountFormatted: new Intl.NumberFormat('id-ID').format(initialAmount),

                    init() {
                        // Watcher 1
                        this.$watch('amountRaw', value => {
                            if (value === 0 || !value) {
                                this.amountFormatted = '';
                            } else {
                                this.amountFormatted = new Intl.NumberFormat('id-ID').format(value);
                            }
                        });

                        // Watcher 2
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
