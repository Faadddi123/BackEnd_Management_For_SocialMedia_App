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
        Schema::create('reaction_displayed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reaction_id');
            $table->unsignedBigInteger('displayed_id');
            $table->unsignedBigInteger('user_id'); // Add this line to include a user_id column
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('reaction_id')->references('id')->on('reactions')->onDelete('cascade');
            $table->foreign('displayed_id')->references('id')->on('displayed')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Add this line for the foreign key constraint

            // Optional: Add an index to improve query performance
            $table->index(['reaction_id', 'displayed_id', 'user_id']); // Include user_id in the index for better performance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reaction_displayed');
    }
};
