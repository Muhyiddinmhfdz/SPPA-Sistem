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
        Schema::create('kompetisis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cabor_id');
            $table->unsignedBigInteger('atlet_id');
            $table->enum('tingkatan', ['Internasional', 'Nasional', 'Daerah']);
            $table->string('nama_kompetisi');
            $table->date('waktu_pelaksanaan');
            $table->string('tempat_pelaksanaan');
            $table->integer('jumlah_peserta')->nullable();
            $table->string('hasil_peringkat')->nullable();
            $table->enum('hasil_medali', ['emas', 'perak', 'perunggu', 'tanpa_medali'])->nullable()->default('tanpa_medali');
            $table->text('kesimpulan_evaluasi')->nullable();
            $table->unsignedBigInteger('dicatat_oleh')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cabor_id')->references('id')->on('cabors')->onDelete('cascade');
            $table->foreign('atlet_id')->references('id')->on('atlets')->onDelete('cascade');
            $table->foreign('dicatat_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompetisis');
    }
};
