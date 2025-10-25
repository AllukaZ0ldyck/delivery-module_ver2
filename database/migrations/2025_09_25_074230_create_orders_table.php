<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        // database/migrations/xxxx_xx_xx_create_orders_table.php
        public function up()
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('total_price', 10, 2);
                $table->string('delivery_address');
                $table->dateTime('delivery_date');
                $table->enum('status', ['pending', 'confirmed', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
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
