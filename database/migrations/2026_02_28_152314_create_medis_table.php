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
        Schema::create('medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('klasifikasi', ['dokter', 'perawat', 'masseur']);
            $table->string('nik', 50)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('religion', 50)->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->text('address')->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->string('last_education')->nullable();

            // Document Paths (PDF/JPEG)
            $table->string('education_certificate_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('ktp_path')->nullable();
            $table->string('competency_certificate_path')->nullable();
            $table->string('npwp_path')->nullable();
            $table->string('sk_appointment_path')->nullable();
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
        Schema::dropIfExists('medis');
    }
};
