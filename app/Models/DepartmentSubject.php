<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'subject_id',
        'is_major'
    ];

    /**
     * Define relationship with the Department model.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'program_id');
    }

    /**
     * Define relationship with the Subject model.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function prospectus()
    {
        return $this->hasMany(SubjectsProspectus::class, 'program_id', 'program_id');
    }

}