<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMO extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's naming convention)
    protected $table = 'cmos';

    // Allow mass assignment for these fields
    protected $fillable = [
        'cmo_number',
        'description',
        'year_issued',
    ];

    /**
     * Relationship with the Prospectus table
     * Each CMO can have many prospectuses
     */
    public function prospectuses()
    {
        return $this->hasMany(Prospectus::class, 'cmo_id');
    }
}
