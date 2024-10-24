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
            $table->string('admission_status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->date('admission_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('admission_status'); // Remove the admission_status column
            $table->dropColumn('admission_date');   // Remove the admission_date column
        });
    }
};
