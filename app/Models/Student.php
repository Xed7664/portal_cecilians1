<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 

class Student extends Model
{
    use HasFactory;
    use Notifiable;
    protected $table = 'students';

    protected $fillable = [
        'StudentID', 'FullName', 'Birthday', 'Gender', 'Address', 'Status',
        'Semester', 'YearLevel', 'Section', 'Major', 'Course', 'Scholarship', 'SchoolYear',
        'BirthPlace', 'Religion', 'Citizenship', 'Type','father_name','father_occupation','mother_occupation' , 'mother_name','previous_school','previous_school_adress' , 'contact','admission_status','admission_date','pre_enrollment_completed'
    ];

    protected $hidden = [
        'Scholarship'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'StudentID', 'student_id');
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
