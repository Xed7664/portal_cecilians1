<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectEnrolled;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Find the student based on their school ID
        $student = Student::where('StudentID', $row['student_school_id'])->first();

        // Find the subject based on the subject code
        $subject = Subject::where('subject_code', $row['subject_code'])->first();

        if ($student && $subject) {
            // Find the SubjectEnrolled record based on student_id and subject_id
            $subjectEnrolled = SubjectEnrolled::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->first();

            if ($subjectEnrolled) {
                return Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_enrolled_id' => $subjectEnrolled->id,
                    ],
                    [
                        'prelim'   => $row['prelim'],
                        'midterm'  => $row['midterm'],
                        'prefinal' => $row['prefinal'],
                        'final'    => $row['final'],
                        'remarks'  => $row['remarks'] ?? $this->calculateRemarks($row),
                    ]
                );
            }
        }
    }

    private function calculateRemarks($row)
    {
        $average = ($row['prelim'] + $row['midterm'] + $row['prefinal'] + $row['final']) / 4;
        return $average <= 3.0 ? 'Passed' : 'Failed';
    }
}
