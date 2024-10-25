<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSubjectsEnrolledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_enrolled', function (Blueprint $table) {
            // Check if subject_id exists before attempting to drop it
            if (Schema::hasColumn('subjects_enrolled', 'subject_id')) {
                // Drop foreign key constraint
                $table->dropForeign(['subject_id']);
                // Drop the subject_id column
                $table->dropColumn('subject_id');
            }
            
            // Add prospectus_id referencing subject_prospectus table
            $table->foreignId('prospectus_id')->constrained('subjects_prospectus')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects_enrolled', function (Blueprint $table) {
            // Add subject_id back
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('restrict');
            
            // Drop the prospectus_id column
            $table->dropForeign(['prospectus_id']);
            $table->dropColumn('prospectus_id');
        });
    }
};

