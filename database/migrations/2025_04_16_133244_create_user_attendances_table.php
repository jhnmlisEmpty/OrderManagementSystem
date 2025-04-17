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
        Schema::create('user_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date')->index(); // Date of attendance
            $table->time('morning_time_in')->nullable(); // Morning time in
            $table->time('morning_time_out')->nullable(); // Morning time out
            $table->time('afternoon_time_in')->nullable(); // Afternoon time in
            $table->time('afternoon_time_out')->nullable(); // Afternoon time out
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_attendances');
    }
};
