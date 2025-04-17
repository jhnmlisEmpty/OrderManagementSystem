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
            Schema::rename('osr_payslips', 'osr_payslip_infos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('osr_payslip_infos', function (Blueprint $table) {
            Schema::rename('osr_payslip_infos', 'osr_payslips');
        });
    }
};
