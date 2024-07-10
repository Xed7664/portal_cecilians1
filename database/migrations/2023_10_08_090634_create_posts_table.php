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
            // Use ULID instead of auto-incrementing integer for the 'id' column
            $table->ulid('id')->primary();
            
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            
            // Define 'status' as an enum column
            $table->enum('status', ['publish', 'pending', 'deleted'])->default('publish');
            
            $table->timestamps();

            // Define foreign key constraint
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
