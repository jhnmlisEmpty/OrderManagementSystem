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
        Schema::table('user_attendances', function (Blueprint $table) {
            $table->dropColumn(['morning_time_in', 'morning_time_out', 'afternoon_time_in', 'afternoon_time_out']); // Remove old columns
            $table->time('time_in')->nullable(); // Add time_in column
            $table->time('time_out')->nullable(); // Add time_out column
            $table->integer('seconds_elapsed')->nullable(); // Add seconds_elapsed column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendances', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'time_out', 'seconds_elapsed']);
            $table->time('morning_time_in')->nullable();
            $table->time('morning_time_out')->nullable();
            $table->time('afternoon_time_in')->nullable();
            $table->time('afternoon_time_out')->nullable();
        });
    }
};
