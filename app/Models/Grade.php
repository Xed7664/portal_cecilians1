<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_enrolled_id',
        'student_id',
        'prelim',
        'midterm',
        'prefinal',
        'final',
        'remarks'
    ];

    public function subjectEnrolled()
    {
        return $this->belongsTo(SubjectEnrolled::class, 'subject_enrolled_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
       // Access related data through SubjectEnrolled
       public function subject()
       {
           return $this->hasOneThrough(
               Subject::class,
               SubjectEnrolled::class,
               'id',  // Foreign key on SubjectEnrolled table
               'id',  // Foreign key on Subject table
               'subject_enrolled_id', // Local key on Grade table
               'subject_id'  // Local key on SubjectEnrolled table
           );
       }
   
       public function semester()
       {
           return $this->hasOneThrough(
               Semester::class,
               SubjectEnrolled::class,
               'id',  // Foreign key on SubjectEnrolled table
               'id',  // Foreign key on Semester table
               'subject_enrolled_id', // Local key on Grade table
               'semester_id'  // Local key on SubjectEnrolled table
           );
       }
   
       public function section()
       {
           return $this->hasOneThrough(
               Section::class,
               SubjectEnrolled::class,
               'id',  // Foreign key on SubjectEnrolled table
               'id',  // Foreign key on Section table
               'subject_enrolled_id', // Local key on Grade table
               'section_id'  // Local key on SubjectEnrolled table
           );
       }
   
       public function yearLevel()
       {
           return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
       }
       public function schoolYear()
       {
           return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
       }

       public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
       
}
