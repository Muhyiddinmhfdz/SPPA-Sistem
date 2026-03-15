<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_test_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('performance_test_id');
            $table->unsignedBigInteger('physical_test_item_id');
            $table->decimal('nilai', 10, 2)->nullable()->comment('Nilai mentah yang diukur');
            $table->unsignedBigInteger('physical_test_item_score_id')->nullable()->comment('Kriteria penilaian yang cocok');
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('performance_test_id')->references('id')->on('performance_tests')->cascadeOnDelete();
            $table->foreign('physical_test_item_id')->references('id')->on('physical_test_items')->cascadeOnDelete();
            $table->foreign('physical_test_item_score_id')->references('id')->on('physical_test_item_scores')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_test_results');
    }
};
