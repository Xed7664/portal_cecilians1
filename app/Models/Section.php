<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    // Relationship with the Grade model
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Relationship with the Employee model (for general employees associated with the section)
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_section');
    }

    // Relationship with the Subject model
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_section', 'section_id', 'subject_id');
    }

    // Relationship with the Employee model specifically for teachers
    public function teachers()
    {
        return $this->belongsToMany(Employee::class, 'teacher_subject_section', 'section_id', 'teacher_id');
    }

    // Relationship with the SubjectEnrolled model
    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class, 'section_id');
    }
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
    }
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
    }
public function students()
{
    return $this->hasMany(Student::class);
}
}
