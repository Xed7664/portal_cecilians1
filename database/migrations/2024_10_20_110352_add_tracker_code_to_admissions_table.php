<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Step 1: Add the tracker_code column without the unique constraint
        Schema::table('admissions', function (Blueprint $table) {
            $table->string('tracker_code')->nullable()->after('email'); // Adding the column as nullable first
        });

        // Step 2: Update existing rows to ensure unique values for tracker_code
        DB::statement('UPDATE admissions SET tracker_code = CONCAT("TRACK_", id) WHERE tracker_code IS NULL');

        // Step 3: Add the unique constraint after ensuring no duplicate entries
        Schema::table('admissions', function (Blueprint $table) {
            $table->unique('tracker_code', 'admissions_tracker_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropUnique('admissions_tracker_code_unique'); // Drop the unique constraint
            $table->dropColumn('tracker_code'); // Drop the column
        });
    }
};
