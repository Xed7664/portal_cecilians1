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

    
}

