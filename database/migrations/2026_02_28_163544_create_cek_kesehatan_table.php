<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cek_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->enum('person_type', ['atlet', 'pelatih']); // Tab selector
            $table->unsignedBigInteger('person_id');           // atlet_id or coach_id
            $table->foreignId('cabor_id')->constrained('cabors')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('kondisi_harian', ['sehat', 'lelah', 'cidera']);
            $table->enum('tingkat_cedera', ['tidak_cidera', 'ringan', 'sedang', 'berat'])->default('tidak_cidera');
            $table->text('riwayat_medis')->nullable();
            $table->enum('kesimpulan', ['baik', 'sedang', 'berat']);
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cek_kesehatan');
    }
};
