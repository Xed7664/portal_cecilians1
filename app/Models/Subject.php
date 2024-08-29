<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['subject_code', 'description', 'room_name', 'day', 'corrected_day', 'time', 'corrected_time', 'units', 'instructor_name', 'amount'];

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
      // Define the relationship to the Grade model
      public function grades()
      {
          return $this->hasMany(Grade::class, 'subject_id', 'id');
      }
}
