<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSubjectProspectusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_prospectus', function (Blueprint $table) {
            // Add missing foreign key constraints and attributes
            
            // Foreign key to programs/departments table
            $table->foreignId('program_id')->constrained('departments')->onDelete('restrict');
            
            // Foreign key to semesters table
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('restrict');
            
            // Foreign key to year_levels table
            $table->foreignId('year_level_id')->constrained('year_levels')->onDelete('restrict');
            
            // Additional attributes for the prospectus
            $table->integer('units'); // Number of units
            $table->string('prerequisite')->nullable(); // Pre-requisites for the subject
            $table->string('corequisite')->nullable(); // Co-requisites for the subject
            $table->integer('lec_hours'); // Lecture hours per week
            $table->integer('lab_hours'); // Lab hours per week
            $table->integer('total_hours_per_week'); // Total hours per week
            $table->decimal('final_grade', 3, 2)->nullable(); // Final grade of the subject in the prospectus
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects_prospectus', function (Blueprint $table) {
            // Dropping the columns in case of rollback
            $table->dropForeign(['program_id']);
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['year_level_id']);
            
            $table->dropColumn([
                'program_id', 
                'semester_id', 
                'year_level_id', 
                'units', 
                'prerequisite', 
                'corequisite', 
                'lec_hours', 
                'lab_hours', 
                'total_hours_per_week', 
                'final_grade'
            ]);
        });
    }
};
