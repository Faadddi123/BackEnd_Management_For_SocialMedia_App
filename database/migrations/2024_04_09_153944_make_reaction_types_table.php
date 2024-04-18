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
        Schema::create('reactions', function (Blueprint $table) {
            $table->id(); // This creates an auto-incrementing `id` column as the primary key.
            $table->string('name'); // This creates a `name` column to store the name.
            $table->string('icon'); // This creates an `icon` column to store the icon.
            $table->timestamps(); // This adds `created_at` and `updated_at` columns.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
