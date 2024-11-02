<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_year_id'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class);
    }
    
    public function schoolYear()
        {
            return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
        }

        public function schedules()
        {
            return $this->hasMany(Schedule::class);
        }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
