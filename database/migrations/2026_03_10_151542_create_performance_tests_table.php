<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('atlet_id');
            $table->unsignedBigInteger('cabor_id');
            $table->unsignedBigInteger('klasifikasi_disabilitas_id')->nullable();
            $table->unsignedBigInteger('jenis_disabilitas_id')->nullable();
            $table->string('alat_bantu')->nullable()->comment('prothesis/orthosis/wheelchair_manual/wheelchair_racing');
            $table->enum('status_kesehatan', ['fit', 'cidera', 'rehabilitasi'])->default('fit');
            $table->date('tanggal_pelaksanaan');
            $table->string('spesialisasi')->nullable()->comment('Nomor / Kelas Atlet');
            $table->string('penguji')->nullable();
            $table->boolean('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('atlet_id')->references('id')->on('atlets')->cascadeOnDelete();
            $table->foreign('cabor_id')->references('id')->on('cabors')->onDelete('restrict');
            $table->foreign('klasifikasi_disabilitas_id')->references('id')->on('klasifikasi_disabilitas')->nullOnDelete();
            $table->foreign('jenis_disabilitas_id')->references('id')->on('jenis_disabilitas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_tests');
    }
};
