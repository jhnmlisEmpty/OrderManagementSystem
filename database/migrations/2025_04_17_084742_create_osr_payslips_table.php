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
        Schema::create('osr_payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->decimal('base_salary', 10, 2); // Base salary
            $table->decimal('sss', 10, 2)->nullable(); // SSS deduction
            $table->decimal('philhealth', 10, 2)->nullable(); // PhilHealth deduction
            $table->decimal('pagibig', 10, 2)->nullable(); // Pag-IBIG deduction
            $table->decimal('other_deductions', 10, 2)->nullable(); // Other deductions
            $table->string('qrcode')->nullable(); // QR code for the payslip
            $table->date('release_date')->nullable(); // Release date of the payslip
            $table->string('payment_proof')->nullable(); // Payment proof (e.g., file path)
            $table->enum('status', ['pending', 'released', 'cancelled'])->default('pending'); // Status of the payslip
            $table->decimal('incentives', 10, 2)->nullable(); // Incentives
            $table->date('start_date'); // Start date of the pay period
            $table->date('end_date'); // End date of the pay period
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('osr_payslips');
    }
};
