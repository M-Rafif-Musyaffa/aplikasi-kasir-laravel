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
        Schema::create('product_batches', function (Blueprint $table) {
        $table->id();


        $table->foreignId('product_id')->constrained()->onDelete('cascade');

        $table->decimal('harga_beli', 10, 2);
        $table->integer('stok_awal');
        $table->integer('stok_sisa');

        $table->date('tgl_masuk');
        $table->date('tgl_expired')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
