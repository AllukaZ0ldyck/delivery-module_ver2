<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('contact')->nullable();
        //     $table->string('address')->nullable();
        //     $table->string('gallon_type')->nullable();
        //     $table->integer('gallon_count')->nullable();
        //     $table->string('approval_status')->default('pending'); // pending | approved | rejected
        //     $table->string('confirmation_code')->nullable();
        // });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
