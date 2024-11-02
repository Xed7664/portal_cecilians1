<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 

class Student extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = [
        'StudentID', 'FullName', 'Birthday', 'Gender', 'Address', 'Status',
        'semester_id', 'year_level_id', 'section_id', 'program_id', 'Major',
        'Scholarship', 'school_year_id', 'BirthPlace', 'Religion', 'Citizenship',
        'Type', 'student_type', 'category', 'contact', 'father_name', 
        'father_occupation', 'mother_occupation', 'mother_name', 'previous_school', 
        'previous_school_adress', 'admission_status', 'admission_date', 
        'pre_enrollment_completed'
    ];

    protected $hidden = [
        'Scholarship'
    ];

    // Relationship with User (if StudentID is the identifier in users table)
    public function user()
    {
        return $this->belongsTo(User::class, 'StudentID', 'student_id');
    }
    
    // Check if the student is registered
    public function isRegistered()
    {
        return $this->user !== null;
    }

    // Relationship with SubjectsEnrolled
    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class, 'student_id', 'id');
    }

    // Relationship with Program (Department)
    public function program()
    {
        return $this->belongsTo(Department::class, 'program_id', 'id');
    }

    // Relationship with Section
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    
    // Relationship with YearLevel
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
    }

    // Relationship with Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    // Relationship with SchoolYear
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
    }
}
