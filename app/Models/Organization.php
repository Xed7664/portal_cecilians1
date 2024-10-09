<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // Add other fields as needed
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'organization_members')
                    ->withPivot(['organization_role_id', 'school_year_id'])
                    ->using(OrganizationMember::class);
    }

    public function roles()
    {
        return $this->hasMany(OrganizationRole::class);
    }

}
