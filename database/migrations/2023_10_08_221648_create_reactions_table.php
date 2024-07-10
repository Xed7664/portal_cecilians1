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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reactable_id');
            $table->string('reactable_type');
            $table->string('type'); // 'like', 'love', 'haha', 'wow', 'sad', 'angry', or any other reactions you want
            $table->timestamps();
    
            $table->unique(['user_id', 'reactable_id', 'reactable_type', 'type']);
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
