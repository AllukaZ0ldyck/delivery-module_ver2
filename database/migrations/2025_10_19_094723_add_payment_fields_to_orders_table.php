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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('COD')->after('total_price');
            $table->string('payment_receipt')->nullable()->after('payment_method');
            $table->string('payment_status')->default('unpaid')->after('payment_receipt');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_receipt', 'payment_status']);
        });
    }

};
