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
        Schema::table('osr_payslip_infos', function (Blueprint $table) {
            $table->string('account_name')->nullable()->after('note_for_deduction'); // Account name
            $table->string('account_number')->nullable()->after('account_name'); // Account number
            $table->string('payment_method')->nullable()->after('account_number'); // Payment method
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('osr_payslip_infos', function (Blueprint $table) {
            $table->dropColumn(['account_name', 'account_number', 'payment_method']);
        });
    }
};
