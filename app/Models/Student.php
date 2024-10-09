<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'StudentID', 'FullName', 'Birthday', 'Gender', 'Address', 'Status',
        'Semester', 'YearLevel', 'Section', 'Major', 'Course', 'Scholarship', 'SchoolYear',
        'BirthPlace', 'Religion', 'Citizenship', 'Type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Scholarship'
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->hasOne(User::class, 'student_id', 'StudentID');
    }

    public function isRegistered()
    {
        return $this->user !== null;
    }
    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class);
    }
    // In App\Models\Student.php

        public function enrollments()
        {
            return $this->hasMany(SubjectEnrolled::class, 'student_id', 'id');
        }

}
