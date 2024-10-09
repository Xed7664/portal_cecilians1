<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create([
            'StudentID' => 'SCC-14-0001232',
            'FullName' => 'Javeluna, James',
            'Birthday' => '2001-10-29',
            'Gender' => 'Male',
            'Address' => 'Kingswood, Lipata, Minglanilla, Cebu',
            'Status' => 'Single',
            'Semester' => '2nd Sem',
            'YearLevel' => '3rd Year',
            'Section' => "",
            'Major' => "",
            'Course' => 'BSIT',
            'Scholarship' => 'Unifast',
            'SchoolYear' => '2022-2023',
        ]);

        Student::factory(4)->create();
    }
}
