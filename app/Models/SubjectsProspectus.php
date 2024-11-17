<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectsProspectus extends Model
{
    use HasFactory;

    protected $table = 'subjects_prospectus'; // Specify the correct table name

    protected $fillable = [  'cmo_id', 'ched_curriculum_id','subject_id', 'program_id', 'year_level_id', 'semester_id', 'archive_status'];

    // Relationship with the Subject model
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Relationship with the Department model
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Relationship with the Grade model
    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id', 'subject_id');
    }

    // Relationship with the Semester model
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // Relationship with the YearLevel model
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id');
    }

    /**
     * Fetch subjects for a specific program, year level, and semester.
     *
     * @param  int  $programId
     * @param  int  $yearLevel
     * @param  int  $semester
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSubjectsForYearLevel($programId, $yearLevel, $semester)
    {
        return self::where('program_id', $programId)
                    ->where('year_level_id', $yearLevel)
                    ->where('semester_id', $semester)
                    ->with(['subject', 'semester', 'yearLevel']) // Eager load relationships
                    ->get();
    }
    public function program()
    {
        return $this->belongsTo(Department::class);
    }
    public function chedCurriculum()
    {
        return $this->belongsTo(ChedCurriculum::class, 'ched_curriculum_id', 'id');
    }
    public function cmo()
    {
        return $this->belongsTo(CMO::class, 'cmo_id');
    }
}