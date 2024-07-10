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
        Schema::table('students', function (Blueprint $table) {
            $table->string('BirthPlace')->nullable();
            $table->string('Religion')->nullable();
            $table->string('Citizenship')->nullable();
            $table->string('Type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['BirthPlace']);
            $table->dropColumn(['Religion']);
            $table->dropColumn('Citizenship');
            $table->dropColumn('Type');
        });
    }
};
