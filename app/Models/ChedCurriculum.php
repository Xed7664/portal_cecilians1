<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChedCurriculum extends Model
{
    use HasFactory;

    protected $table = 'ched_curriculums';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'title',
        'effectivity_year',
        'archive_status',
        'created_at',
        'updated_at'
    ];

    /**
     * Define a relationship to the SubjectsProspectus model.
     */
    public function prospectuses()
    {
        return $this->hasMany(SubjectsProspectus::class, 'ched_curriculum_id', 'id');
    }
}
