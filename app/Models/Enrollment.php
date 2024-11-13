<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id',
        'school_year_id',
        'year_level_id',
        'status',
    ];

    // Relationship to Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relationship to Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Relationship to Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Relationship to School Year
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
