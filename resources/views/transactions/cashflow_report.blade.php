<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Laporan Arus Kas (Cashflow)') }}
        </h2>
    </x-slot>

    <div class="py-0">

        <form action="{{ route('reports.cashflow') }}" method="GET" class="relative z-10">
            <div class="bg-white p-4 rounded-lg shadow-md mb-6 border">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <div class="flex items-end space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Filter
                        </button>
                        <button type="submit" formaction="{{ route('reports.cashflow.print') }}" formtarget="_blank"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="bg-white p-6 rounded-lg shadow-md border">
            <div class="overflow-x-auto ">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Uang Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Uang Keluar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Arus Kas Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($laporanArusKas as $laporan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-right">
                                    Rp {{ number_format($laporan->total_uang_masuk, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">
                                    Rp {{ number_format($laporan->total_uang_keluar, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $laporan->arus_kas_bersih >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">
                                    Rp {{ number_format($laporan->arus_kas_bersih, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Belum ada data transaksi atau biaya.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $laporanArusKas->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
