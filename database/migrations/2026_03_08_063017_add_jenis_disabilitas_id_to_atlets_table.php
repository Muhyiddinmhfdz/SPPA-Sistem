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
        Schema::table('atlets', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_disabilitas_id')->nullable()->after('klasifikasi_disabilitas_id');
            $table->foreign('jenis_disabilitas_id')->references('id')->on('jenis_disabilitas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atlets', function (Blueprint $table) {
            $table->dropForeign(['jenis_disabilitas_id']);
            $table->dropColumn('jenis_disabilitas_id');
        });
    }
};
