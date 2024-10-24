<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign keys to reference existing tables
            $table->foreignId('student_id')->constrained()->onDelete('restrict'); // References students
            $table->foreignId('subject_id')->constrained()->onDelete('restrict'); // References subjects
            $table->foreignId('section_id')->constrained()->onDelete('restrict'); // References sections
            $table->foreignId('teacher_id')->constrained('employees')->onDelete('restrict'); // References employees (instructor)
            $table->foreignId('semester_id')->constrained()->onDelete('restrict'); // References semesters
            $table->foreignId('school_year_id')->constrained()->onDelete('restrict'); // References school years

            // Class schedule details
            $table->string('room'); // Room name or number
            $table->string('days'); // E.g., "MWF" or "TTh"
            $table->time('start_time'); // Start time of the class
            $table->time('end_time'); // End time of the class
            $table->integer('lecture_units')->unsigned()->default(0); // Lecture units
            $table->integer('lab_units')->unsigned()->default(0); // Lab units

            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
