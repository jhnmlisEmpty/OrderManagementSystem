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
        Schema::table('incentives', function (Blueprint $table) {
            $table->string('status')->nullable()->after('market_incentive_percentage'); // Add status column
            $table->string('image')->nullable()->after('status'); // Add image column
            $table->date('release_date')->nullable()->after('image'); // Add release_date column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incentives', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('image');
            $table->dropColumn('release_date');
        });
    }
};
