<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'StudentID', 'FullName', 'Birthday', 'Gender', 'Address', 'Status',
        'Semester', 'YearLevel', 'Section', 'Major', 'Course', 'Scholarship', 'SchoolYear',
        'BirthPlace', 'Religion', 'Citizenship', 'Type',
    ];

    protected $hidden = [
        'Scholarship'
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->hasOne(User::class, 'student_id', 'StudentID');
    }

    // Check if the student is registered
    public function isRegistered()
    {
        return $this->user !== null;
    }

    // Define the relationship with SubjectsEnrolled
    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class);
    }

    // Define the relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'Course', 'id');
    }

    // Define the relationship with the Section (if needed)
    public function section()
    {
        return $this->belongsTo(Section::class, 'Section', 'id');
    }

    // You can also add a relationship for Semester if needed
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'Semester', 'id');
    }
    
    public function enrollments()
        {
            return $this->hasMany(SubjectEnrolled::class, 'student_id', 'id');
        }

}
