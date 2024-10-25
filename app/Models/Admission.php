<?php

// Admission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'birthday',
        'gender',
        'address',
        'tracker_code',
        'picture',
        'formcard',
        'certifications',
        'status',
    ];
}

