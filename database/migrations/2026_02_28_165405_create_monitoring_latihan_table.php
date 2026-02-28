<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_latihan', function (Blueprint $table) {
            $table->id();
            $table->enum('person_type', ['atlet', 'pelatih']);
            $table->unsignedBigInteger('person_id');
            $table->foreignId('cabor_id')->constrained('cabors')->onDelete('cascade');
            $table->date('tanggal');                                        // Kehadiran (tanggal)
            $table->enum('kehadiran', ['hadir', 'tidak_hadir', 'izin', 'sakit'])->default('hadir');
            $table->string('durasi_latihan', 10);                          // Durasi: e.g. "02:30"
            $table->enum('beban_latihan', ['ringan', 'sedang', 'berat']);
            $table->string('denyut_nadi_rpe', 100)->nullable();            // Denyut Nadi/RPE
            $table->text('catatan_pelatih')->nullable();                    // Catatan Pelatih
            $table->enum('kesimpulan', ['ya', 'tidak']);                    // Ya=Lanjut, Tidak=Evaluasi
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_latihan');
    }
};
