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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 👤 Basic Information
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('name'); // keep for backward compatibility

            // 📞 Contact Details
            $table->string('contact')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();

            // 💧 Gallon Details
            $table->string('gallon_type')->nullable();
            $table->integer('gallon_count')->default(0);

            // ⚙️ Role and Account Status
            $table->string('role')->default('customer');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');

            // 🔒 Authentication
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();

            // 🧾 Unique Identifiers
            $table->uuid('qr_token')->unique();
            $table->string('confirmation_code')->nullable();

            // 🚦 Misc
            $table->boolean('isActive')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
