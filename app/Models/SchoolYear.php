<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class, 'school_year_id', 'id');
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

}