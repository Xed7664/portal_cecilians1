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
        Schema::create('section_year_level_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('year_level_id'); // Assuming year levels are represented by an integer
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        
            $table->unique(['section_id', 'year_level_id']); // Ensure only one lock status per section-year level
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_year_level_locks');
    }
};
