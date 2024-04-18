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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Automatically creates an auto-incrementing ID column
            $table->text('text'); // For the comment text
            $table->timestamp('created_at')->useCurrent(); // For the date of creation
            $table->unsignedBigInteger('user_id'); // Assuming user_id is a foreign key to a users table
            $table->unsignedBigInteger('displayed_id'); // For the displayed_id, assuming it's an integer

            // Optional: Define foreign key constraints if there's a users table
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
