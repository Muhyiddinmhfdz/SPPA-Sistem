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
        Schema::create('cabors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('sk_start_date')->nullable();
            $table->date('sk_end_date')->nullable();
            $table->string('chairman_name')->nullable();
            $table->string('secretary_name')->nullable();
            $table->string('treasurer_name')->nullable();
            $table->text('secretariat_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->integer('active_athletes_count')->nullable();
            $table->integer('active_coaches_count')->nullable();
            $table->integer('active_medics_count')->nullable();
            $table->string('sk_file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabors');
    }
};
