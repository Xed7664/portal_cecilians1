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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code');
            $table->string('description');
            $table->string('room_name');
            $table->string('day');
            $table->string('corrected_day')->nullable(); // Added correction column for day
            $table->string('time');
            $table->string('corrected_time')->nullable(); // Added correction column for time
            $table->string('units');
            $table->string('instructor_name');
            $table->decimal('amount', 8, 2);
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('semester_id');
            $table->timestamps();
    
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
