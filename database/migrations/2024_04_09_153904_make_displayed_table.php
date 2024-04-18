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
        Schema::create('displayed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID of the user who created or shared the post
            $table->unsignedBigInteger('post_id'); // ID of the post itself
            $table->unsignedBigInteger('partage_id')->nullable()->default(0); // ID of the original post if this is a shared post, null if created directly by the user
            $table->timestamps();

            // Foreign key constraints (assuming users and posts tables exist)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            // Optional: If you want to enforce referential integrity for partage_id, ensure there's a posts table to reference
            $table->foreign('partage_id')->references('id')->on('post_partaged')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('displayed');
    }
};
