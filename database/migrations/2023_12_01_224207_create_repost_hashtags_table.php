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
        Schema::create('repost_hashtags', function (Blueprint $table) {
            $table->id();
            $table->ulid('repost_id'); // Adjust the type based on your needs
            $table->unsignedBigInteger('hashtag_id');
            $table->timestamps();

            $table->foreign('repost_id')->references('id')->on('reposts')->onDelete('cascade');
            $table->foreign('hashtag_id')->references('id')->on('hashtags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repost_hashtags');
    }
};
