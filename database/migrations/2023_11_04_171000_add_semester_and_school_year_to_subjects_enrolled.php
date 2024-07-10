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
        Schema::table('subjects_enrolled', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('school_year_id');

            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->foreign('school_year_id')->references('id')->on('school_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects_enrolled', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('semester_id');
            $table->dropColumn('school_year_id');
        });
    }
};
