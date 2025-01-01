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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('order_ship_date')->nullable()->after('full_amount');
            $table->date('order_reprocess_date')->nullable()->after('order_ship_date');
            $table->date('order_returned_date')->nullable()->after('order_reprocess_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_ship_date', 'order_reprocess_date', 'order_returned_date']);
        });
    }
};
