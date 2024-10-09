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
            $table->timestamp('release_date')->nullable(); // Adds the release_date column
        });
    }
    
    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('release_date'); // Drops the release_date column
        });
    }
    
};
