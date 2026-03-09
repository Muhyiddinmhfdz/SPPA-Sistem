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
        Schema::create('pembinaan_prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atlet_id')->constrained('atlets')->onDelete('cascade');
            $table->string('periodesasi_latihan'); // harian, mingguan, bulanan
            $table->string('intensitas_latihan'); // ringan, sedang, berat
            $table->foreignId('training_type_component_id')->constrained('training_type_components')->onDelete('cascade');
            $table->text('target_performa')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembinaan_prestasis');
    }
};
