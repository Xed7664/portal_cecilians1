<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = ['EmployeeID', 'FullName','Birthday', 'Gender', 'department_id', 'user_id'];

    // Define the relationship with User
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'EmployeeID');
    }

    public function isRegistered()
    {
        return $this->user !== null;
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
