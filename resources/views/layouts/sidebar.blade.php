<aside
    class="w-64 p-6 bg-white border-r border-gray-200
              flex-col flex-shrink-0
              absolute md:relative z-20
              h-full md:h-auto
              transition-transform duration-300 transform"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    @click.away="if (window.innerWidth < 768) sidebarOpen = false">
    <div class="h-full overflow-y-auto">

        <a href="{{ route('dashboard') }}" class="flex items-center ps-2.5 mb-8">
            <x-application-logo class="h-8 w-8 text-blue-600" />
            <span class="self-center text-xl font-bold whitespace-nowrap ml-2 text-gray-900">KasirApp</span>
        </a>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('pos.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all text-gray-900 hover:bg-blue-600 hover:text-white
                  {{ request()->routeIs('pos.index')
                      ? 'bg-blue-600 text-white shadow-lg font-semibold'
                      : 'text-gray-600 hover:bg-gray-100' }}">

                    <svg class="w-6 h-6
                       {{ request()->routeIs('pos.index') ? 'text-white' : 'text-gray-500 group-hover:text-white' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H7a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span class="ms-3 text-lg">Kasir (POS)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-3 rounded-lg group transition-all
                  {{ request()->routeIs('dashboard')
                      ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-semibold'
                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">

                    <svg class="w-5 h-5 ..."> </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            <li>
                <button type="button"
                    class="flex items-center w-full p-3 text-base rounded-lg group transition duration-75
                       {{ request()->routeIs('products.*') || request()->routeIs('stock.in.create')
                           ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                    aria-controls="inventaris-dropdown" data-collapse-toggle="inventaris-dropdown">

                    <svg class="w-5 h-5
                        {{ request()->routeIs('products.*') || request()->routeIs('stock.in.create') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-900' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Inventaris</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <ul id="inventaris-dropdown"
                    class="py-2 space-y-2 {{ request()->routeIs('products.*') || request()->routeIs('stock.in.create') ? '' : 'hidden' }}">

                    <li>
                        <a href="{{ route('products.index') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
                         {{ request()->routeIs('products.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Daftar Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.in.create') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
                         {{ request()->routeIs('stock.in.create') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Barang Masuk
                        </a>
                    </li>
                </ul>

            </li>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 text-base rounded-lg group transition duration-75
               {{ request()->routeIs('transactions.*') ||
               request()->routeIs('reports.profit') ||
               request()->routeIs('reports.cashflow')
                   ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-semibold'
                   : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                    aria-controls="reports-dropdown" data-collapse-toggle="reports-dropdown">

                    <svg class="w-5 h-5
                            {{ request()->routeIs('transactions.*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-900' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0h6m6 0v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z">
                        </path>
                    </svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Laporan</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <ul id="reports-dropdown"
                    class="py-2 space-y-2 {{ request()->routeIs('transactions.*') || request()->routeIs('reports.profit') || request()->routeIs('reports.cashflow') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('transactions.index') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
                 {{ request()->routeIs('transactions.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Riwayat Transaksi
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('reports.profit') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
                 {{ request()->routeIs('reports.profit') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Laporan Profit
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.cashflow') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
             {{ request()->routeIs('reports.cashflow') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Laporan Arus Kas
                        </a>
                    </li>
                </ul>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 text-base rounded-lg group transition duration-75
                           {{ request()->routeIs('expenses.*') || request()->routeIs('capital-injections.*')
                               ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-semibold'
                               : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                    aria-controls="finance-dropdown" data-collapse-toggle="finance-dropdown">

                    <svg class="w-5 h-5
                            {{ request()->routeIs('expenses.*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-900' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H7a3 3 0 00-3 3v8a3 3 0 003 3zM1_10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H7a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Keuangan</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <ul id="finance-dropdown" <ul id="finance-dropdown"
                    class="py-2 space-y-2 {{ request()->routeIs('expenses.*') || request()->routeIs('capital-injections.*') ? '' : 'hidden' }}">

                    <li>
                        <a href="{{ route('expenses.index') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
                             {{ request()->routeIs('expenses.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Biaya Operasional
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('capital-injections.index') }}"
                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
             {{ request()->routeIs('capital-injections.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Modal Masuk
                        </a>
                    </li>
                </ul>
            </li>
            </li>

        </ul>
        <li>
            <button type="button"
                class="flex items-center w-full p-3 text-base rounded-lg group transition duration-75
                       {{ (request()->routeIs('categories.*') || request()->routeIs('tax-rates.*') || request()->routeIs('expense-categories.*'))
                           ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                aria-controls="settings-dropdown" data-collapse-toggle="settings-dropdown">

                <svg class="w-5 h-5
                        {{ request()->routeIs('categories.*') ? 'text-blue-700' : 'text-gray-500 group-hover:text-gray-900' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Pengaturan</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>

            <ul id="settings-dropdown"
                class="py-2 space-y-2 {{ request()->routeIs('categories.*') || request()->routeIs('tax-rates.*') || request()->routeIs('expense-categories.*') ? '' : 'hidden' }}">

                <li>
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
                         {{ request()->routeIs('categories.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                        Kategori Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('tax-rates.index') }}"
                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
             {{ request()->routeIs('tax-rates.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                        Opsi Pajak
                    </a>
                </li>
                <li>
   <a href="{{ route('expense-categories.index') }}"
      class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100
             {{ request()->routeIs('expense-categories.*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
      Kategori Biaya
   </a>
</li>
            </ul>
        </li>
        </ul>

    </div>
</aside>
