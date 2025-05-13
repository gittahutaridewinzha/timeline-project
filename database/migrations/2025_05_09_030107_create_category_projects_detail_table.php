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
        Schema::create('category_projects_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_category_projects');
            $table->unsignedBigInteger('id_job_types');
            $table->timestamps();

            // Foreign key constraints (opsional, tapi disarankan)
            $table->foreign('id_category_projects')->references('id')->on('category_projects')->onDelete('cascade');
            $table->foreign('id_job_types')->references('id')->on('job_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_projects_detail');
    }
};
