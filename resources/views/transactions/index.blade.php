<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <form action="{{ route('transactions.index') }}" method="GET" class="relative z-10 ">
            <div class="bg-white p-4 rounded-lg shadow-md mb-6 border">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Tgl (Dari)</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Tgl (Sampai)</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Bayar</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua --</option>
                            <option value="tunai" {{ request('payment_method') == 'tunai' ? 'selected' : '' }}>Tunai
                            </option>
                            <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Kasir</label>
                        <select name="user_id" id="user_id"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Kasir --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Filter
                        </button>

                        <button type="submit" formaction="{{ route('transactions.print') }}" formtarget="_blank"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="bg-white p-6 rounded-lg shadow-md border">

            <div class="overflow-x-auto ">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Struk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Belanja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Modal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Profit</th>
                            <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $transaction)
                            @php
                                // Hitung profit di sini
                                $profit = $transaction->total_harga_jual - $transaction->total_harga_beli;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    TRX-{{ $transaction->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                 {{ $transaction->payment_method == 'tunai' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($transaction->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                    Rp {{ number_format($transaction->total_harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                                    Rp {{ number_format($transaction->total_harga_beli, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">
                                    Rp {{ number_format($profit, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $transactions->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
