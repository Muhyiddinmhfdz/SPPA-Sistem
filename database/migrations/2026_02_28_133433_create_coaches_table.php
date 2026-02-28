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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('cabor_id')->constrained('cabors')->onDelete('cascade');
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('religion');
            $table->enum('gender', ['L', 'P']);
            $table->text('address');
            $table->string('blood_type')->nullable();
            $table->string('last_education');

            // Dokumen Pendukung
            $table->string('photo_path')->nullable();
            $table->string('ktp_path')->nullable();
            $table->string('certificate_path')->nullable();
            $table->string('npwp_path')->nullable();
            $table->string('sk_path')->nullable();
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
        Schema::dropIfExists('coaches');
    }
};
