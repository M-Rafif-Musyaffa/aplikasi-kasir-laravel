<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Modal Masuk') }}
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

            <div class="lg:col-span-1" x-data="financeForm({{ old('amount', 0) }})" x-init="init()">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Catat Modal Masuk</h3>

                    <form action="{{ route('capital-injections.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="date" id="date"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date') border-red-500 @enderror"
                                value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <input type="text" name="description" id="description"
                                placeholder="Misal: Modal Awal Usaha"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                value="{{ old('description') }}" required>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="amount_formatted" class="block text-sm font-medium text-gray-700">Jumlah
                                (Rp)</label>
                            <input type="text" id="amount_formatted" x-model="amountFormatted"
                                placeholder="Misal: 10.000.000"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('amount') border-red-500 @enderror text-right"
                                required>
                            <input type="hidden" name="amount" x-model="amountRaw">
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                Simpan Modal
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
                    <form action="{{ route('capital-injections.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Tgl
                                    (Dari)</label>
                                <input type="date" name="date_from" id="date_from"
                                    value="{{ request('date_from') }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Tgl
                                    (Sampai)</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-end space-x-3">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                    Filter
                                </button>
                                <button type="submit" formaction="{{ route('capital-injections.print') }}"
                                    formtarget="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                                    Cetak
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dicatat Oleh</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($capitalInjections as $capital)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($capital->date)->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $capital->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">Rp
                                            {{ number_format($capital->amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $capital->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('capital-injections.edit', $capital) }}"
                                                class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Edit</a>
                                            <button type="button"
                                                @click="openDeleteModal('{{ route('capital-injections.destroy', $capital) }}')"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 ml-2">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada
                                            data modal masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $capitalInjections->links() }}</div>
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
                            </button>
                            <div class="p-6 text-center">
                                <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mb-5 text-lg font-normal text-gray-500">Yakin ingin menghapus catatan modal
                                    ini?</h3>
                                <form x-bind:action="deleteUrl" method="POST" class="inline-flex">
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
