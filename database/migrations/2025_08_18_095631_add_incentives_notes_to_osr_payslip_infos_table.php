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
            $table->text('incentives_notes')->nullable()->after('incentives'); // Add incentives_notes column after incentives
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('osr_payslip_infos', function (Blueprint $table) {
            $table->dropColumn('incentives_notes');
        });
    }
};
