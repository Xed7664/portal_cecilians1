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
        Schema::table('grades', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->after('id'); // Allow null values

            // Set up the foreign key constraint with set null on delete
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('set null'); // Set to null on delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('your_table_name', function (Blueprint $table) {
            // Drop the foreign key constraint and the column
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
    }
};
