<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SectionYearLevelLock extends Model
{
    use HasFactory;
    protected $fillable = ['section_id', 'year_level_id', 'is_locked'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
