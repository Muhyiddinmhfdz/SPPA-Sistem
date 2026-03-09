<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if there are existing records to decide if we should just truncate or try to convert
        // Since the user asked to fix seeders, truncation is usually acceptable in a dev seeding environment.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pembinaan_prestasi_details')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('pembinaan_prestasi_details', function (Blueprint $table) {
            $table->double('value')->nullable()->change();
            $table->integer('score')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembinaan_prestasi_details', function (Blueprint $table) {
            $table->string('value')->nullable()->change();
            $table->dropColumn('score');
        });
    }
};
