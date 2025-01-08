<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure existing data is compatible with JSON
        DB::table('payment_methods')->whereNotNull('image')->update([
            'image' => DB::raw('CONCAT(\'"\', image, \'"\')')
        ]);

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->json('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });
    }
};
