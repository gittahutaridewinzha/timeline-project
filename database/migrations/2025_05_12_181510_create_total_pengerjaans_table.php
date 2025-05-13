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
        Schema::create('total_pengerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_fiturs_id')->constrained('detail_fiturs')->onDelete('cascade');
            $table->decimal('total_pengerjaan', 5, 2)->default(0); // misalnya dalam persen, max 999.99
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_pengerjaans');
    }
};
