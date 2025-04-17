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
            $table->text('note_for_deduction')->nullable()->after('other_deductions'); // Add the new column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('osr_payslip_infos', function (Blueprint $table) {
            $table->dropColumn('note_for_deduction');
        });
    }
};
