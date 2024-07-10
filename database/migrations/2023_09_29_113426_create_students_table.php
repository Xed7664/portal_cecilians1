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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('StudentID')->unique();
            $table->string('FullName');
            $table->date('Birthday');
            $table->string('Gender');
            $table->string('Address');
            $table->string('Status');
            $table->string('Semester');
            $table->string('YearLevel');
            $table->string('Section')->nullable();
            $table->string('Major')->nullable();
            $table->string('Course');
            $table->string('Scholarship')->nullable();
            $table->string('SchoolYear');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
