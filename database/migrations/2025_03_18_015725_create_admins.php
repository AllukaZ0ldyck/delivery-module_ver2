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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            // 👤 Basic Information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // 📞 Contact & Profile Info
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_picture')->nullable();

            // ⚙️ Role Management
            $table->enum('user_type', ['admin', 'delivery', 'staff'])->default('admin');

            // 🔒 Authentication
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token')->nullable();

            // 📆 Activity Tracking
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
