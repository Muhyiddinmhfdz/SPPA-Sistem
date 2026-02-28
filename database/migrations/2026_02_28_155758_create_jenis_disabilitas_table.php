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
        Schema::create('jenis_disabilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klasifikasi_disabilitas_id')->constrained('klasifikasi_disabilitas')->onDelete('cascade');
            $table->string('nama_jenis');
            $table->text('deskripsi')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_disabilitas');
    }
};
