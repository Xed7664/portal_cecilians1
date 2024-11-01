<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    
    // Define the inverse relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'program_id');
    }


    // A schedule belongs to one section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // A schedule belongs to one semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // A schedule belongs to one school year
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function program()
    {
        return $this->belongsTo(Department::class);
    }
    // Schedule.php
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id');
    }

    // A schedule may have many enrolled students
    public function enrollments()
    {
        return $this->hasMany(SubjectEnrolled::class, 'schedule_id');
    }
 // In Schedule.php
public function subject()
{
    return $this->belongsTo(Subject::class);
}

public function teacher()
{
    return $this->belongsTo(Employee::class, 'teacher_id');
}


}
