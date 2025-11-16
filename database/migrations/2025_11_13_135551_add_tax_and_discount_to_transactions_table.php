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
        Schema::table('transactions', function (Blueprint $table) {

            // Subtotal (Total sebelum diskon dan pajak)
            $table->decimal('subtotal', 15, 2)->after('payment_method');

            // Total Diskon (Nominal)
            $table->decimal('total_discount', 15, 2)->default(0)->after('subtotal');

            // Total Pajak (Nominal)
            $table->decimal('total_tax', 15, 2)->default(0)->after('total_discount');

            /* CATATAN:
        - 'total_harga_jual' sekarang akan menjadi 'Grand Total' (subtotal - diskon + pajak)
        - 'total_harga_beli' (modal) tetap ada
        - 'total_bayar' & 'kembalian' tetap ada
        */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'total_discount', 'total_tax']);
        });
    }
};
