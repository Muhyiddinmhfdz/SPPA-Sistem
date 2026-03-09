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
        Schema::table('pembinaan_prestasis', function (Blueprint $table) {
            $table->dropForeign(['training_type_component_id']);
            $table->dropColumn('training_type_component_id');
        });

        Schema::create('pembinaan_prestasi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembinaan_prestasi_id')->constrained('pembinaan_prestasis')->onDelete('cascade');
            $table->foreignId('training_type_component_id')->constrained('training_type_components')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembinaan_prestasi_details');

        Schema::table('pembinaan_prestasis', function (Blueprint $table) {
            $table->foreignId('training_type_component_id')->nullable()->constrained('training_type_components')->onDelete('cascade');
        });
    }
};
