<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('year_level_id');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('school_year_id');
            $table->enum('enrollment_status', ['active', 'completed', 'dropped'])->default('active');
            $table->timestamps();

            // Foreign key constraints
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('restrict');
            // $table->foreign('program_id')->references('id')->on('programs')->onDelete('restrict');
            // $table->foreign('year_level_id')->references('id')->on('year_levels')->onDelete('restrict');
            // $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('restrict');
            // $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
