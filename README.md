<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

# Aplikasi Kasir (Point of Sale) & Manajemen Inventaris

Ini adalah proyek aplikasi kasir (Point of Sale) berbasis web yang lengkap, dibangun menggunakan **Laravel 11**, **Tailwind CSS**, dan **Alpine.js**. 

Aplikasi ini dirancang sebagai solusi "all-in-one" untuk bisnis kecil dan menengah, yang tidak hanya berfungsi sebagai mesin kasir, tetapi juga sebagai sistem manajemen inventaris (gudang) dan pelaporan keuangan yang canggih.

**[Link Demo Live (Opsional)]** - *(Jika Anda mendeploy-nya, taruh link-nya di sini)*

---

## 📸 Pratinjau (Screenshots)

*(Sangat disarankan untuk menambahkan 3-4 screenshot terbaik Anda di sini. Anda bisa meng-upload gambar ke GitHub dan menautkannya.)*

| Halaman Kasir (POS) | Dashboard | Laporan Arus Kas |
| :---: | :---: | :---: |
| [Screenshot Halaman POS Anda] | [Screenshot Dashboard Anda] | [Screenshot Laporan Anda] |

---

## 🚀 Fitur Utama

Aplikasi ini dibagi menjadi tiga modul utama: **Kasir (POS)**, **Manajemen Inventaris (Gudang)**, dan **Pelaporan Keuangan**.

### 1. Modul Kasir (Point of Sale)
* **Keranjang Interaktif:** Halaman kasir *single-page* (dibuat dengan Alpine.js) tanpa *reload*.
* **Pencarian Produk Real-time:** Filter produk secara instan berdasarkan Nama atau SKU.
* **Manajemen Keranjang:** Tambah (+), Kurang (-), dan Hapus item langsung di keranjang.
* **Validasi Stok:** Kasir tidak bisa menjual barang melebihi stok yang tersedia.
* **Modal Pembayaran Lanjutan:**
    * Pilihan metode bayar (Tunai atau QRIS).
    * Input uang tunai dengan format "titik" (misal: 50.000).
    * Tombol "Uang Cepat" (misal: 10rb, 50rb, Uang Pas) yang bersifat menambah (`+=`).
    * Perhitungan kembalian otomatis.
* **Struk Transaksi:**
    * Otomatis redirect ke halaman struk setelah pembayaran sukses.
    * Desain struk profesional yang menampilkan rincian pajak & diskon.
    * Fungsi cetak (`window.print()`) dengan CSS `@media print` yang diformat untuk printer kasir.

### 2. Modul Inventaris (Gudang)
* **Logika Stok FEFO & FIFO:** Sistem manajemen *batch* (kelompok) yang canggih.
    * **FEFO (First-Expired, First-Out):** Saat checkout, sistem otomatis mengambil stok dari *batch* yang paling cepat kedaluwarsa.
    * **FIFO (First-In, First-Out):** Jika tidak ada tgl. kedaluwarsa, sistem otomatis mengambil dari *batch* yang paling lama masuk.
* **Manajemen Produk (CRUD):** Tambah, edit, hapus katalog produk.
* **Manajemen Stok (Barang Masuk):** Formulir khusus untuk mencatat *batch* pembelian baru, lengkap dengan `harga_beli` (modal), `tgl_masuk`, dan `tgl_expired`.
* **Manajemen Kategori:** CRUD untuk Kategori Produk.
* **Manajemen Pajak:** CRUD untuk "Master Pajak" (misal: PPN 11%, Bebas Pajak 0%) yang bisa dihubungkan ke setiap produk.

### 3. Modul Keuangan & Laporan
* **Dashboard Dinamis:**
    * 4 Kartu KPI (Penjualan, Profit, & Transaksi Hari Ini).
    * Grafik Garis (Chart.js) untuk tren profit & penjualan 7 hari terakhir.
    * Daftar "Stok Kritis" (stok <= 10).
    * Daftar "Hampir Kadaluarsa" (stok yang akan kedaluwarsa dalam 30 hari).
    * Grafik Pie (Chart.js) untuk "Produk Terlaris" bulan ini.
* **Manajemen Biaya (CRUD):**
    * Mencatat semua "Uang Keluar" (listrik, gaji, internet).
    * CRUD untuk "Kategori Biaya" (Operasional, Gaji, Pembelian Stok).
    * **Otomatisasi:** "Barang Masuk" (pembelian stok) otomatis tercatat sebagai "Biaya" di kategori "Pembelian Stok".
* **Laporan Riwayat Transaksi:**
    * Tabel terpaginasi dari semua struk penjualan.
    * Filter berdasarkan Tanggal, Metode Bayar, dan Kasir.
    * Tombol "Cetak" yang menampilkan laporan terfilter, lengkap dengan **rincian barang di setiap struk**.
* **Laporan Profit (Laba/Rugi):**
    * Laporan yang dikelompokkan per hari (`GROUP BY`).
    * Menampilkan `Total Penjualan`, `Total Modal (HPP)`, dan `Total Profit` bersih.
    * Filter berdasarkan rentang tanggal.
* **Laporan Arus Kas (Cashflow):**
    * Laporan keuangan paling akurat yang menggabungkan 3 tabel.
    * **Uang Masuk:** `(Total Penjualan) + (Total Modal Masuk)`
    * **Uang Keluar:** `(Total Biaya Operasional) + (Total Pembelian Stok)`
    * Menampilkan `Arus Kas Bersih` per hari.
    * Filter berdasarkan rentang tanggal dan fitur cetak.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

* **Backend:** Laravel
* **Frontend:** Tailwind CSS, Alpine.js
* **Database:** MySQL (dikelola via Laragon)
* **Libraries:** Chart.js (untuk grafik), Flowbite (untuk komponen UI)
* **DevTools:** Vite (untuk kompilasi aset), Git & GitHub

---

## 📦 Instalasi Lokal

1.  Clone repository ini:
    ```bash
    git clone [https://github.com/](https://github.com/)[NAMA-ANDA]/[NAMA-REPO-ANDA].git
    cd [NAMA-REPO-ANDA]
    ```

2.  Instal dependensi:
    ```bash
    composer install
    npm install
    ```

3.  Siapkan file `.env`:
    ```bash
    cp .env.example .env
    ```

4.  Buat kunci aplikasi dan jalankan migrasi:
    ```bash
    php artisan key:generate
    php artisan migrate
    ```
    *(Pastikan Anda sudah membuat database `aplikasi_kasir` dan mengatur kredensial Anda di file `.env`)*

5.  Jalankan server:
    ```bash
    # Di terminal 1
    npm run dev
    
    # Di terminal 2
    php artisan serve
    ```

6.  Buka `http://127.0.0.1:8000` di browser Anda.

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
