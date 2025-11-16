<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Kita tambahkan kolom-kolom ini setelah 'harga_beli_satuan'

            // Diskon nominal per item (misal: Rp 1.000)
            $table->decimal('discount_amount', 15, 2)->default(0)->after('harga_beli_satuan');

            // Tarif pajak yang berlaku saat itu (misal: 0.11)
            $table->decimal('tax_rate', 5, 4)->default(0)->after('discount_amount');

            // Jumlah pajak nominal (harga - diskon) * tarif
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'tax_rate', 'tax_amount']);
        });
    }
};
