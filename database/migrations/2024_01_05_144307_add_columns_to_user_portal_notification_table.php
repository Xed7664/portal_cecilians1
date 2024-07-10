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
        Schema::table('user_portal_notification', function (Blueprint $table) {
            // Add new columns
            $table->string('type'); // Type of notification (e.g., 'post', 'comment', 'event')
            $table->string('related_id')->nullable(); // ID related to the type
            $table->string('value_1')->nullable(); // Additional value 1
            $table->string('value_2')->nullable(); // Additional value 2
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_portal_notification', function (Blueprint $table) {
            // Reverse the changes (drop columns)
            $table->dropColumn(['type', 'related_id', 'value_1', 'value_2']);
        });
    }
};
