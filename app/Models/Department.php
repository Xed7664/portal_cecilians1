<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function subjects(){
        return $this->hasMany(Subject::class);
    }
    public function prospectus()
    {
        return $this->hasMany(SubjectsProspectus::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'program_id', 'id');
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    
}