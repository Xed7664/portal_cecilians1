<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
        use HasFactory;
    
        protected $fillable = [
            'subject_code', 'description', 
            'units', 'amount','lec_units', 'lab_units', 'total_units',
            'pre_requisite', 'total_hours', 'archive_status'
        ];
        protected $attributes = [
            'amount' => '0.00',
        ];
    
        public function schoolYear()
        {
            return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
        }
    
        public function yearLevel()
        {
            return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
        }
    
        public function semester()
        {
            return $this->belongsTo(Semester::class);
        }
    
        public function grades()
        {
            return $this->hasMany(Grade::class, 'subject_id', 'id');
        }
    
        public function department()
        {
            return $this->belongsTo(Department::class);
        }
        public function subjectEnrolled()
        {
            return $this->hasMany(SubjectEnrolled::class, 'subject_id', 'id');
        }

        public function prospectus()
        {
            return $this->hasMany(SubjectsProspectus::class);
        }
        public function sections()
{
    return $this->belongsToMany(Section::class, 'section_subject')
                ->withPivot('section_id', 'subject_id');
}
// In Subject.php
public function schedules()
{
    return $this->hasMany(Schedule::class);
}
public function departmentSubjects()
{
    return $this->hasMany(DepartmentSubject::class);
}

/**
 * Get the subjects for a specific department (program).
 *
 * @param int $programId
 * @return \Illuminate\Database\Eloquent\Collection
 */
public static function forDepartment(int $programId)
{
    return self::whereHas('departmentSubjects', function ($query) use ($programId) {
        $query->where('program_id', $programId);
    })->get();
}
// Relationship to Enrollments
public function enrollments()
{
    return $this->hasMany(Enrollment::class);
}

}