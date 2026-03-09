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
        Schema::create('training_type_component_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_type_component_id')->constrained('training_type_components', 'id', 'ttc_scores_fk')->onDelete('cascade');
            $table->double('min_value')->nullable();
            $table->double('max_value')->nullable();
            $table->string('label');
            $table->integer('score');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_type_component_scores');
    }
};
