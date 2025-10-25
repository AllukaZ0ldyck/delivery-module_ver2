<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // customer
                $table->integer('quantity');
                $table->string('address');
                $table->enum('status', ['pending', 'approved', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
                $table->decimal('total_price', 10, 2);
                $table->timestamps();
            });
        }
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
