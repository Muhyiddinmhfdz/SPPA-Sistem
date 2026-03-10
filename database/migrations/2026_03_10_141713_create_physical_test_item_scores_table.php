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
        Schema::create('physical_test_item_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physical_test_item_id')->constrained()->onDelete('cascade');
            $table->double('min_value')->nullable();
            $table->double('max_value')->nullable();
            $table->string('label');
            $table->integer('score');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_test_item_scores');
    }
};
