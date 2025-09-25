<?php
// database/migrations/xxxx_xx_xx_create_borrowed_gallons_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowedGallonsTable extends Migration
{
    public function up()
    {
        Schema::create('borrowed_gallons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Reference to the User who borrowed the gallon
            $table->integer('gallon_count'); // Number of gallons borrowed
            $table->timestamp('borrowed_at')->useCurrent(); // When the gallon was borrowed
            $table->timestamp('due_date'); // When the gallon should be returned
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed'); // Track status of the gallon
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowed_gallons');
    }
}
