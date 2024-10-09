<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
   
     // Relationship with Section (if YearLevel is related to Section)
     public function sections()
     {
         return $this->belongsTo(Section::class, 'year_level_id', 'id');
     }
 
     // Relationship with SubjectEnrolled
     public function subjectsEnrolled()
     {
         return $this->hasMany(SubjectEnrolled::class, 'year_level_id', 'id');
     }
}


