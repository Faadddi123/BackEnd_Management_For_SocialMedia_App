<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('text'); // For the message content
            $table->unsignedBigInteger('sender_id'); // ID of the user sending the message
            $table->unsignedBigInteger('receiver_id'); // ID of the user receiving the message
            $table->timestamps(); // To track when the message was sent

            // Assuming you have a 'users' table for sender and receiver
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
