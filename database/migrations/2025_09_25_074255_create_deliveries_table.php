<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_deliveries_table.php
        public function up()
        {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['assigned', 'on_the_way', 'delivered', 'failed'])->default('assigned');
                $table->dateTime('delivery_time')->nullable();
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
