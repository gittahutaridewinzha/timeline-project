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
        Schema::table('pengerjaans', function (Blueprint $table) {
            $table->unsignedBigInteger('project_job_type_id')->nullable()->after('detail_fiturs_id');
            $table->foreign('project_job_type_id')->references('id')->on('project_job_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengerjaans', function (Blueprint $table) {
            //
        });
    }
};
