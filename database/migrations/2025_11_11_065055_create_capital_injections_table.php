<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // ... file: database/migrations/..._create_capital_injections_table.php

    public function up(): void
    {
        Schema::create('capital_injections', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_injections');
    }
};
