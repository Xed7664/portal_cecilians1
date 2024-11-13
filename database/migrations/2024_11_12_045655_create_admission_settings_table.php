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
        Schema::create('admission_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('school_year_id');
            $table->boolean('is_open')->default(false);
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('semesters');
            $table->foreign('school_year_id')->references('id')->on('school_years');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admission_settings');
    }
};
