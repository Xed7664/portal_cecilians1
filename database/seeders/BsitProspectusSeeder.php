<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BsitProspectusSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            // 1st Year, 1st Semester
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'CC101', 'course_description' => 'Introduction to Computing', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'CC102', 'course_description' => 'Computer Programming 1', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 5],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'ENGPLUS', 'course_description' => 'English Enhancement', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'FIL1', 'course_description' => 'Wikang Filipino', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'GE1', 'course_description' => 'Understanding the Self', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'GE2', 'course_description' => 'Ethics', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'GE4', 'course_description' => 'Science, Technology, and Society', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'GE6', 'course_description' => 'Mathematics in the Modern World', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'MATHPLUS', 'course_description' => 'Basic Mathematics', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'NSTP1', 'course_description' => 'National Services Training Program 1', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'PATHFIT1', 'course_description' => 'Movement Competency Training', 'lec_units' => 2, 'lab_units' => 0, 'total_units' => 2, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '1st Semester', 'course_code' => 'REED1', 'course_description' => 'Salvation History', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],

            // 1st Year, 2nd Semester
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'CC103', 'course_description' => 'Computer Programming 2', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC102', 'total_hours' => 5],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'FIL2', 'course_description' => 'Masining na Pagpapahayag', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'FIL1', 'total_hours' => 3],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'FL', 'course_description' => 'Foreign Language', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'GE5', 'course_description' => 'Purposive Communication', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'GE7', 'course_description' => 'Contemporary World', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'HCI101', 'course_description' => 'Introduction to Human Computer Interaction', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC101', 'total_hours' => 0],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'MS101', 'course_description' => 'Discrete Mathematics', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'NSTP2', 'course_description' => 'National Service Training Program 2', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'NSTP1', 'total_hours' => 3],
            ['year' => 1, 'semester' => '2nd Semester', 'course_code' => 'PATHFIT2', 'course_description' => 'Exercise Based Fitness', 'lec_units' => 2, 'lab_units' => 0, 'total_units' => 2, 'pre_requisite' => 'PATHFIT1', 'total_hours' => 0],

            // 2nd Year, 1st Semester
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'ACCTG', 'course_description' => 'Principles of Accounting', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'CC203', 'course_description' => 'Information Management 1', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC103', 'total_hours' => 5],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'CC204', 'course_description' => 'Data Structures and Algorithms', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'MS101', 'total_hours' => 5],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'GE8', 'course_description' => 'Art Appreciation', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'HUM1', 'course_description' => 'Logic', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'IT201', 'course_description' => 'PC Assembling and Disassembling', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC101', 'total_hours' => 5],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'PATHFIT3', 'course_description' => 'Individual and Dual Sports', 'lec_units' => 2, 'lab_units' => 0, 'total_units' => 2, 'pre_requisite' => 'PATHFIT2', 'total_hours' => 2],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'PF201', 'course_description' => 'Object-Oriented Programming I', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC103', 'total_hours' => 5],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'REED2', 'course_description' => 'Christology', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'REED1', 'total_hours' => 3],
            ['year' => 2, 'semester' => '1st Semester', 'course_code' => 'SOC1', 'course_description' => 'Economics, Taxation and Land Reform', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],

            // 2nd Year, 2nd Semester
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'GE3', 'course_description' => 'Reading in Philippine History', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 0],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'IM207', 'course_description' => 'Fundamentals of Database System', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC203', 'total_hours' => 5],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'IPT209', 'course_description' => 'Integrative Programming and Technologies', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'HCI101', 'total_hours' => 5],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'MATH3', 'course_description' => 'Probability and Statistics', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'NET208', 'course_description' => 'Networking', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 5],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'PATHFIT4', 'course_description' => 'Team Sports/Games', 'lec_units' => 2, 'lab_units' => 0, 'total_units' => 2, 'pre_requisite' => 'PATHFIT3', 'total_hours' => 2],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'PF205', 'course_description' => 'Object-Oriented Programming 2', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'PF201', 'total_hours' => 5],
            ['year' => 2, 

 'semester' => '2nd Semester', 'course_code' => 'PGC', 'course_description' => 'Philippine Governance and Constitution', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 2, 'semester' => '2nd Semester', 'course_code' => 'REED3', 'course_description' => 'Sacraments, Church and Christian Morality', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'REED2', 'total_hours' => 3],

            // 3rd Year, 1st Semester
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'HUM2', 'course_description' => 'Introduction to Literature', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'IT306', 'course_description' => 'Multimedia Systems', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 5],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'IT307', 'course_description' => 'System Analysis and Design', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'IM207', 'total_hours' => 5],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'NATSCI1', 'course_description' => 'Physical Science', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'PT300', 'course_description' => 'Free Elective: Platform Technologies', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC103', 'total_hours' => 5],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'REED4', 'course_description' => 'Christian Commitment and Responsibility', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'REED3', 'total_hours' => 3],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'SIA304', 'course_description' => 'Systems Integration and Architecture 1', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'IT201', 'total_hours' => 5],
            ['year' => 3, 'semester' => '1st Semester', 'course_code' => 'WS301', 'course_description' => 'Elective 1: Web Development 1', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'IPT209', 'total_hours' => 5],

            // 3rd Year, 2nd Semester
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'GE9', 'course_description' => 'Life and Works of Rizal and Other Heroes', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'IAS311', 'course_description' => 'Information Assurance and Security', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC101', 'total_hours' => 5],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'IT308', 'course_description' => 'Software Engineering', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC204', 'total_hours' => 5],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'MS309', 'course_description' => 'Quantitative Methods', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'MS101', 'total_hours' => 3],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'NATSCI2', 'course_description' => 'College Physics', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'NATSCI1', 'total_hours' => 3],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'PT206', 'course_description' => 'Project Management', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CC203', 'total_hours' => 5],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'SIA312', 'course_description' => 'Free Elective: System Integration and Architecture 2', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'SIA304', 'total_hours' => 5],
            ['year' => 3, 'semester' => '2nd Semester', 'course_code' => 'WS310', 'course_description' => 'Web Systems and Technologies 2', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'WS301', 'total_hours' => 5],

            // 3rd Year, Summer
            ['year' => 3, 'semester' => 'Summer', 'course_code' => 'CAP314', 'course_description' => 'Capstone Project 1', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => '3rd yr.', 'total_hours' => 5],
            ['year' => 3, 'semester' => 'Summer', 'course_code' => 'CC313', 'course_description' => 'Application Development and Emerging Technologies', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'PF205', 'total_hours' => 5],

            // 4th Year, 1st Semester
            ['year' => 4, 'semester' => '1st Semester', 'course_code' => 'CAP401', 'course_description' => 'Capstone Project 2', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'CAP314', 'total_hours' => 5],
            ['year' => 4, 'semester' => '1st Semester', 'course_code' => 'IT402', 'course_description' => 'Free Elective', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'PF205', 'total_hours' => 5],
            ['year' => 4, 'semester' => '1st Semester', 'course_code' => 'IT404', 'course_description' => 'Seminars in IT Trends / Updates - Elective', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],
            ['year' => 4, 'semester' => '1st Semester', 'course_code' => 'SA405', 'course_description' => 'System Administration and Maintenance', 'lec_units' => 2, 'lab_units' => 1, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 5],
            ['year' => 4, 'semester' => '1st Semester', 'course_code' => 'SP403', 'course_description' => 'Social and Professional Issues', 'lec_units' => 3, 'lab_units' => 0, 'total_units' => 3, 'pre_requisite' => 'None', 'total_hours' => 3],

            // 4th Year, 2nd Semester
            ['year' => 4, 'semester' => '2nd Semester', 'course_code' => 'OJT', 'course_description' => 'Internship / OJT / Practicum', 'lec_units' => 9, 'lab_units' => 0, 'total_units' => 9, 'pre_requisite' => '4th yr.', 'total_hours' => 5],
        ];

        DB::table('subjects')->insert($subjects);

        $semester_totals = [
            ['year' => 1, 'semester' => '1st Semester', 'total_lec_units' => 33, 'total_lab_units' => 2, 'total_units' => 35, 'total_hours' => 5],
            ['year' => 1, 'semester' => '2nd Semester', 'total_lec_units' => 24, 'total_lab_units' => 2, 'total_units' => 26, 'total_hours' => 14],
            ['year' => 2, 'semester' => '1st Semester', 'total_lec_units' => 25, 'total_lab_units' => 4, 'total_units' => 29, 'total_hours' => 37],
            ['year' => 2, 'semester' => '2nd Semester', 'total_lec_units' => 22, 'total_lab_units' => 4, 'total_units' => 26, 'total_hours' => 31],
            ['year' => 3, 'semester' => '1st Semester', 'total_lec_units' => 19, 'total_lab_units' => 5, 'total_units' => 24, 'total_hours' => 34],
            ['year' => 3, 'semester' => '2nd Semester', 'total_lec_units' => 19, 'total_lab_units' => 5, 'total_units' => 24, 'total_hours' => 34],
            ['year' => 3, 'semester' => 'Summer', 'total_lec_units' => 4, 'total_lab_units' => 2, 'total_units' => 6, 'total_hours' => 10],
            ['year' => 4, 'semester' => '1st Semester', 'total_lec_units' => 12, 'total_lab_units' => 3, 'total_units' => 15, 'total_hours' => 21],
            ['year' => 4, 'semester' => '2nd Semester', 'total_lec_units' => 9, 'total_lab_units' => 0, 'total_units' => 9, 'total_hours' => 5],
        ];

        DB::table('semester_totals')->insert($semester_totals);
    }
}