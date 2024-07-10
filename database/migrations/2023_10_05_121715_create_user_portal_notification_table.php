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
        Schema::create('user_portal_notification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The receiver of the notification
            $table->unsignedBigInteger('portal_notification_id');
            $table->unsignedBigInteger('sender_id'); // The sender of the notification
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
    
            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('portal_notification_id')->references('id')->on('portal_notifications')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_portal_notification');
    }
};
