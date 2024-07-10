<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = ['EmployeeID', 'FullName'];

    // Define the relationship with User
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'EmployeeID');
    }

    public function isRegistered()
    {
        return $this->user !== null;
    }
}
