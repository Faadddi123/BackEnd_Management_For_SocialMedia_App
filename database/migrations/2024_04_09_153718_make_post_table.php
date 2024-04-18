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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('content_text'); // For storing text content
            $table->string('element_type'); // To specify the type (video, image, GIF, etc.)
            $table->string('element_path'); // To store the file path or identifier for the element
            $table->unsignedBigInteger('user_id'); // Reference to the user who created the post
            $table->timestamps();
            // Assuming you have a 'users' table and 'user_id' is a foreign key referencing 'id' in the 'users' table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
