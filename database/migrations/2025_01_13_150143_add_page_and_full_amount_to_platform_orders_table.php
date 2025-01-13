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
        Schema::table('platform_orders', function (Blueprint $table) {
            $table->string('page')->nullable()->after('platform');
            $table->decimal('full_amount', 10, 2)->default(0)->after('page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_orders', function (Blueprint $table) {
            $table->dropColumn('page');
            $table->dropColumn('full_amount');
        });
    }
};
