<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who paid
                $table->decimal('amount', 10, 2);
                $table->enum('method', ['cash', 'gcash', 'card'])->default('cash');
                $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
                $table->timestamps();
            });
        }
    }


    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
