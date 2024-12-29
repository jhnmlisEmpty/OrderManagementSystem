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
        DB::table('users')
        ->whereNotIn('role', ['admin', 'osr', 'affiliate', 'super_admin', 'production', 'logistics'])
        ->orWhereNull('role')
        ->update(['role' => 'affiliate']); // Default value

        // Modify the column to ENUM
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'osr', 'affiliate', 'super_admin', 'production', 'logistics'])
                ->default('affiliate')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to a string type in case you need to revert
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('affiliate')->change();
        });
    }
};
