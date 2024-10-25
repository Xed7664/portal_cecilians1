<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectsProspectus extends Model
{
    use HasFactory;

    protected $table = 'subjects_prospectus'; // Specify the correct table name

    protected $fillable = ['subject_id', 'archive_status'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function grades()
    {
        return $this->belongsTo(Grade::class);
    }
  /**
     * Fetch subjects for a specific program, year level, and semester.
     *
     * @param  int  $programId
     * @param  string  $yearLevel
     * @param  string  $semester
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSubjectsForYearLevel($programId, $yearLevel, $semester)
    {
        return self::where('program_id', $programId)
                    ->where('year_level_id', $yearLevel)
                    ->where('semester_id', $semester)
                    ->with('subject') // eager load the related subject
                    ->get();
    }
    
}

