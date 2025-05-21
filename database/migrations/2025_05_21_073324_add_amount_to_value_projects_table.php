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
        Schema::table('value_projects', function (Blueprint $table) {
            $table->enum('payment_category', ['full_payment', 'dp', 'pelunasan'])->default('dp')->after('value_project');
            $table->decimal('amount', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('value_projects', function (Blueprint $table) {
            $table->dropColumn(['payment_category', 'amount']);
        });
    }
};
