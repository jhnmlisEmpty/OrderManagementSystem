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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->decimal('shipping_price', 10, 2)->nullable();
            $table->string('platform');
            $table->string('tracking')->nullable();
            $table->enum('status', ['pending', 'on_prod', 'created', 'shipped', 'delivered', 'canceled', 'returned', 'reprocess']);
            $table->timestamp('date_received')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total_from_items', 10, 2);
            $table->decimal('full_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
