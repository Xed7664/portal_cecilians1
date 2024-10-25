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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->date('birthday');
            $table->string('gender'); // Ensure this can store Male/Female
            $table->text('address');
            $table->enum('student_type', ['new', 'transferee', 'returnee']); // Student type: New, Transferee, or Returnee
            $table->string('picture')->nullable(); // Store the path to the picture
            $table->string('formcard')->nullable(); // Path to Form 138
            $table->string('certifications')->nullable(); // Path to Birth Certificate
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of admission
            $table->timestamps(); // created_at and updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
