<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectEnrolled extends Model
{
    use HasFactory;

    protected $table = 'subjects_enrolled';

    protected $fillable = [
        'student_id', 'subject_id', 'section_id', 'schedule_id','semester_id', 'school_year_id', 'year_level_id', 'prospectus_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function schoolYear()
        {
            return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
        }

        public function section()
        {
            return $this->belongsTo(Section::class);
        }
    
        // public function grades()
        // {
        //     return $this->hasMany(Grade::class, 'subject_enrolled_id');
        // }
        public function grade()
        {
            return $this->hasOne(Grade::class, 'subject_enrolled_id');
        }
        

        //This is used to display grades in Student's Prospectus
        public function grades()
        {
            return $this->hasMany(Grade::class, 'subject_enrolled_id', 'id');
        }
    
        public function yearLevel()
        {
            return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
        }
        // Define the relationship to the Department model
        public function department()
        {
            return $this->hasOneThrough(Department::class, Schedule::class, 'id', 'id', 'schedule_id', 'program_id');
        }
        
        public function semester()
        {
            return $this->hasOneThrough(Semester::class, Schedule::class, 'id', 'id', 'schedule_id', 'semester_id');
        }
        
        public function schedule()
        {
            return $this->belongsTo(Schedule::class, 'schedule_id');
        }
        
}
