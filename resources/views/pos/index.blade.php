<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Point of Sale (Kasir)') }}
        </h2>
    </x-slot>

    <div class="py-0" x-data="pos()" x-init="init()">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 ">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Produk</h3>
                    <input type="text" x-model="search" placeholder="Cari produk berdasarkan nama atau SKU..."
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-[60vh] overflow-y-auto p-2">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="addToCart(product)"
                                class="border rounded-lg p-4 text-center cursor-pointer hover:bg-blue-50 hover:shadow-md transition-all">
                                <span class="font-semibold text-sm text-gray-800" x-text="product.name"></span>
                                <span class="text-xs text-gray-500 block mt-1">Stok: <span class="font-bold"
                                        x-text="product.total_stok"></span></span>
                                <span class="text-xs font-bold text-blue-600 block mt-1"
                                    x-text="formatCurrency(product.harga_jual)"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="border-b pb-4 mt-4 mb-4 ">
                    <label for="discount_formatted" class="block text-sm font-bold text-gray-700">Diskon
                        (Nominal)</label>
                    <input type="text" id="discount_formatted" x-model="discountFormatted" placeholder="Misal: 5.000"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right">
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Keranjang</h3>
                    <div class="min-h-[40vh] border-b pb-4 mb-4 overflow-y-auto">
                        <div x-show="cart.length === 0" class="text-gray-500 text-center pt-16">Keranjang masih kosong.
                        </div>
                        <div class="space-y-4">
                            <template x-for="item in cart" :key="item.id">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <span class="font-semibold text-gray-800" x-text="item.name"></span>
                                        <span class="text-sm text-gray-500 block"
                                            x-text="formatCurrency(item.price)"></span>
                                        <span class="font-bold text-blue-600 text-sm block mt-1"
                                            x-text="formatCurrency(item.quantity * item.price)"></span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <div class="flex items-center border rounded-lg">
                                            <button @click="decrementQuantity(item.id)"
                                                class="px-2 py-1 text-lg font-bold text-gray-600 hover:bg-gray-100 rounded-l-lg">-</button>
                                            <span class="px-3 py-1 text-sm font-medium" x-text="item.quantity"></span>
                                            <button @click="incrementQuantity(item.id)"
                                                class="px-2 py-1 text-lg font-bold text-gray-600 hover:bg-gray-100 rounded-r-lg">+</button>
                                        </div>
                                        <button @click="removeFromCart(item.id)"
                                            class="flex items-center justify-center text-xs text-red-600 bg-red-100 hover:bg-red-200 rounded p-1 mt-1 transition-colors duration-150">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Subtotal</span>
                            <span class="font-bold text-gray-900" x-text="formatCurrency(cartSubtotal)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Diskon</span>
                            <span class="font-bold text-red-600" x-text="'- ' + formatCurrency(discountRaw)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Total Pajak</span>
                            <span class="font-bold text-gray-900" x-text="formatCurrency(cartTaxTotal)"></span>
                        </div>
                        <div class="flex justify-between text-xl border-t pt-2 mt-2">
                            <span class="font-semibold text-gray-800">Total Akhir</span>
                            <span class="font-bold text-blue-600" x-text="formatCurrency(cartGrandTotal)"></span>
                        </div>
                    </div>
                    <button @click="openPaymentModal()"
                        class="w-full py-3 bg-blue-600 text-white rounded-lg text-lg font-bold hover:bg-blue-700"
                        :disabled="cart.length === 0" :class="{ 'opacity-50 cursor-not-allowed': cart.length === 0 }">
                        Bayar
                    </button>
                </div>
            </div>
        </div>

        <div x-show="paymentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">

            <div class="relative p-4 w-full max-w-md h-full md:h-auto" @click.away="closePaymentModal()">
                <div class="relative bg-white rounded-lg shadow">

                    <div class="flex justify-between items-center p-4 rounded-t border-b">
                        <h3 class="text-xl font-semibold text-gray-900">Proses Pembayaran</h3>
                        <button type="button" @click="closePaymentModal()"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="text-center mb-4">
                            <p class="text-sm text-gray-500">Total Belanja</p>
                            <p class="text-4xl font-bold text-blue-600" x-text="formatCurrency(cartGrandTotal)"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <button @click="paymentMethod = 'tunai'"
                                :class="{ 'bg-blue-600 text-white': paymentMethod === 'tunai', 'bg-gray-100 text-gray-700': paymentMethod !== 'tunai' }"
                                class="w-full py-3 rounded-lg font-semibold transition-all">
                                Tunai
                            </button>
                            <button @click="paymentMethod = 'qris'; cashReceivedRaw = cartTotal"
                                :class="{ 'bg-blue-600 text-white': paymentMethod === 'qris', 'bg-gray-100 text-gray-700': paymentMethod !== 'qris' }"
                                class="w-full py-3 rounded-lg font-semibold transition-all">
                                QRIS
                            </button>
                        </div>

                        <div x-show="paymentMethod === 'tunai'" x-transition>
                            <label for="cash" class="block text-sm font-medium text-gray-700">Uang
                                Diterima</label>
                            <input type="text" name="cash" id="cash" x-model="cashReceivedFormatted"
                                placeholder="0"
                                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg p-3 text-right">
                        </div>

                        <div x-show="paymentMethod === 'tunai'" class="grid grid-cols-4 gap-2 mt-2">
                            <button type="button" @click="cashReceivedRaw += 10000"
                                class="px-2 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">10rb</button>
                            <button type="button" @click="cashReceivedRaw += 20000"
                                class="px-2 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">20rb</button>
                            <button type="button" @click="cashReceivedRaw += 50000"
                                class="px-2 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">50rb</button>
                            <button type="button" @click="cashReceivedRaw += 100000"
                                class="px-2 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">100rb</button>
                            <button type="button" @click="cashReceivedRaw = cartGrandTotal"
                                class="col-span-2 px-2 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200">Uang
                                Pas</button>
                            <button type="button" @click="cashReceivedRaw = 0"
                                class="col-span-2 px-2 py-2 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200">Clear</button>
                        </div>

                        <div x-show="paymentMethod === 'tunai'" class="text-center mt-4">
                            <p class="text-sm text-gray-500">Kembalian</p>
                            <p class="text-2xl font-bold text-gray-800" x-text="formatCurrency(paymentChange)"></p>
                        </div>
                    </div>

                    <div class="p-6 border-t rounded-b">
                        <div x-show="errorMessage" class="text-red-500 text-sm text-center mb-2"
                            x-text="errorMessage"></div>

                        <button @click="checkout()"
                            class="w-full py-3 bg-green-600 text-white rounded-lg text-lg font-bold hover:bg-green-700"
                            :disabled="(paymentMethod === 'tunai' && cashReceivedRaw < cartGrandTotal) || isLoading"
                            :class="{
                                'opacity-50 cursor-not-allowed': (paymentMethod === 'tunai' && cashReceivedRaw <
                                    cartGrandTotal) || isLoading
                            }">

                            <span x-show="!isLoading">Konfirmasi Pembayaran</span>
                            <span x-show="isLoading">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pos() {
            return {
                cart: [],
                products: @json($productsForPos),
                search: '',
                paymentModalOpen: false,
                paymentMethod: 'tunai',

                cashReceivedRaw: 0,
                cashReceivedFormatted: '',

                // -- VARIABEL UNTUK DISKON --
                discountRaw: 0,
                discountFormatted: '',
                // --

                isLoading: false,
                errorMessage: '',

                init() {
                    // Watcher untuk format input Uang Tunai
                    this.$watch('cashReceivedRaw', value => this.formatInput('cashReceivedRaw', 'cashReceivedFormatted',
                        value));
                    this.$watch('cashReceivedFormatted', value => this.parseInput('cashReceivedFormatted',
                        'cashReceivedRaw', value));

                    // Watcher untuk format input Diskon
                    this.$watch('discountRaw', value => this.formatInput('discountRaw', 'discountFormatted', value));
                    this.$watch('discountFormatted', value => this.parseInput('discountFormatted', 'discountRaw', value));
                },

                // --- FUNGSI FORMATTING  ---
                formatInput(rawVar, formattedVar, value) {
                    if (value === 0 || !value) {
                        this[formattedVar] = '';
                    } else if (this[rawVar] === value) {
                        this[formattedVar] = new Intl.NumberFormat('id-ID').format(value);
                    }
                },
                parseInput(formattedVar, rawVar, value) {
                    const numberValue = parseInt(value.replace(/[^0-9]/g, '')) || 0;
                    this[rawVar] = numberValue;
                },
                // ---


                get filteredProducts() {
                    if (this.search === '') {
                        return this.products;
                    }
                    return this.products.filter(p => {
                        const searchLower = this.search.toLowerCase();
                        const nameMatch = p.name.toLowerCase().includes(searchLower);
                        const skuMatch = p.sku && p.sku.toLowerCase().includes(searchLower);
                        return nameMatch || skuMatch;
                    });
                },

                // --- GETTER KALKULASI ---
                get cartSubtotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },
                get cartTaxTotal() {
                    // Hitung total pajak dari semua item
                    return this.cart.reduce((total, item) => total + item.tax_amount, 0);
                },
                get cartGrandTotal() {
                    // (Subtotal - Diskon) + Pajak
                    // Pastikan diskon tidak lebih besar dari subtotal
                    let validDiscount = Math.min(this.discountRaw, this.cartSubtotal);
                    return (this.cartSubtotal - validDiscount) + this.cartTaxTotal;
                },
                // ---

                get paymentChange() {
                    if (this.paymentMethod !== 'tunai' || !this.cashReceivedRaw) {
                        return 0;
                    }
                    // Kembalian dihitung dari Grand Total
                    let change = this.cashReceivedRaw - this.cartGrandTotal;
                    return change < 0 ? 0 : change;
                },

                // --- FUNGSI KERANJANG ---
                updateCartItem(item) {
                    // Setiap kali kuantitas berubah, hitung ulang pajaknya
                    let subtotal = item.price * item.quantity;
                    // Diskon diterapkan per-item (nanti) atau global? Kita anggap global dulu.
                    // Untuk sekarang, pajak dihitung dari subtotal item
                    item.tax_amount = subtotal * item.tax_rate;
                },

                addToCart(product) {
                    let itemInCart = this.cart.find(i => i.id === product.id);
                    let currentQtyInCart = itemInCart ? itemInCart.quantity : 0;

                    if (currentQtyInCart >= product.total_stok) {
                        alert('Stok produk tidak mencukupi!');
                        return;
                    }

                    if (itemInCart) {
                        itemInCart.quantity++;
                        this.updateCartItem(itemInCart); // Hitung ulang pajak
                    } else {
                        // Item baru, tambahkan info pajak
                        let newItem = {
                            id: product.id,
                            name: product.name,
                            price: product.harga_jual,
                            quantity: 1,
                            tax_rate: product.tax_rate, // Simpan tarif pajak
                            tax_amount: (product.harga_jual * 1) * product.tax_rate // Hitung pajak awal
                        };
                        this.cart.push(newItem);
                    }
                },
                incrementQuantity(itemId) {
                    let itemInCart = this.cart.find(i => i.id === itemId);
                    if (!itemInCart) return;

                    let product = this.products.find(p => p.id === itemId);
                    if (itemInCart.quantity >= product.total_stok) {
                        alert('Stok produk tidak mencukupi!');
                        return;
                    }
                    itemInCart.quantity++;
                    this.updateCartItem(itemInCart); // Hitung ulang pajak
                },
                decrementQuantity(itemId) {
                    let itemInCart = this.cart.find(i => i.id === itemId);
                    if (!itemInCart) return;

                    if (itemInCart.quantity > 1) {
                        itemInCart.quantity--;
                        this.updateCartItem(itemInCart); // Hitung ulang pajak
                    } else {
                        this.removeFromCart(itemId);
                    }
                },
                removeFromCart(itemId) {
                    this.cart = this.cart.filter(i => i.id !== itemId);
                },
                // --- AKHIR FUNGSI KERANJANG ---

                formatCurrency(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                },

                openPaymentModal() {
                    if (this.cart.length === 0) return;
                    this.cashReceivedRaw = 0;
                    this.paymentMethod = 'tunai';
                    this.errorMessage = '';
                    this.paymentModalOpen = true;
                },
                closePaymentModal() {
                    this.paymentModalOpen = false;
                },

                resetCart() {
                    this.cart = [];
                    this.cashReceivedRaw = 0;
                    this.discountRaw = 0; // Reset diskon juga
                    this.paymentMethod = 'tunai';
                    this.errorMessage = '';
                },

                async checkout() {
                    if (this.isLoading) return;
                    this.isLoading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('{{ route('pos.checkout') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                cart: this.cart,
                                payment_method: this.paymentMethod,
                                cash_received: this.cashReceivedRaw,

                                // Data Baru
                                subtotal: this.cartSubtotal,
                                total_discount: this.discountRaw,
                                total_tax: this.cartTaxTotal,
                                total_belanja: this
                                    .cartGrandTotal // 'total_belanja' sekarang adalah Grand Total
                            })
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            this.errorMessage = data.message || 'Terjadi kesalahan.';
                            throw new Error(this.errorMessage);
                        }
                        this.closePaymentModal();
                        this.resetCart();
                        let receiptUrl = `/pos/receipt/${data.transaction_id}`;
                        window.location.href = receiptUrl;

                    } catch (error) {
                        console.error('Error checkout:', error);
                        if (!this.errorMessage) this.errorMessage = 'Koneksi ke server gagal.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
