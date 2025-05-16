<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->unsignedBigInteger('id_project_type')->after('id')->nullable(); // kolom bisa null
            $table->foreign('id_project_type')->references('id')->on('project_types')->onDelete('set null'); // foreign key
        });
    }

    public function down(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->dropForeign(['id_project_type']);
            $table->dropColumn('id_project_type');
        });
    }
};
