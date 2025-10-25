<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('deliveries')) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // delivery person
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->enum('status', ['pending', 'assigned', 'in_transit', 'completed', 'failed'])->default('pending');
                $table->timestamps();
            });
        }
    }


    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
