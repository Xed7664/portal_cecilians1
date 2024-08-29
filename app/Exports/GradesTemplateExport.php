<?php

namespace App\Exports;

use App\Models\SubjectEnrolled;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GradesTemplateExport implements FromCollection, WithHeadings
{
    protected $subjectEnrolledId;

    public function __construct($subjectEnrolledId = null)
    {
        $this->subjectEnrolledId = $subjectEnrolledId;
    }

    public function collection()
    {
        // If no subjectEnrolledId is provided, handle it accordingly (e.g., return an empty template)
        if (is_null($this->subjectEnrolledId)) {
            return collect([
                [
                    'Student School ID' => 'e.g., 12345',
                    'Student Name' => 'e.g., John Doe',
                    'Subject Code' => 'e.g., MATH101',
                    'Subject Description' => 'e.g., Basic Mathematics',
                    'Prelim' => '',
                    'Midterm' => '',
                    'Prefinal' => '',
                    'Final' => '',
                    'Remarks' => '',
                ]
            ]);
        }

        // Fetching all records for the specific subject ID and section ID
        $subjectEnrolled = SubjectEnrolled::where('subject_id', function($query) {
            $query->select('subject_id')
                  ->from('subjects_enrolled')
                  ->where('id', $this->subjectEnrolledId);
        })
        ->where('section_id', function($query) {
            $query->select('section_id')
                  ->from('subjects_enrolled')
                  ->where('id', $this->subjectEnrolledId);
        })
        ->with(['student', 'subject'])
        ->get();

        return $subjectEnrolled->map(function ($enrollment) {
            return [
                'Student School ID' => $enrollment->student->StudentID,
                'Student Name' => $enrollment->student->FullName,
                'Subject Code' => $enrollment->subject->subject_code,
                'Subject Description' => $enrollment->subject->description,
                'Prelim' => '',
                'Midterm' => '',
                'Prefinal' => '',
                'Final' => '',
                'Remarks' => '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student School ID',
            'Student Name',
            'Subject Code',
            'Subject Description',
            'Prelim',
            'Midterm',
            'Prefinal',
            'Final',
            'Remarks',
        ];
    }
}
