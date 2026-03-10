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
        Schema::create('physical_test_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physical_test_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->unsignedBigInteger('jenis_disabilitas_id')->nullable();
            $table->string('satuan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraint if you want, otherwise just the column is fine depending on the setup. 
            // In SPPA, sometimes just doing integer matches the legacy structure. But user says "dibuat bigint ya". 
            $table->foreign('jenis_disabilitas_id')->references('id')->on('jenis_disabilitas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_test_items');
    }
};
