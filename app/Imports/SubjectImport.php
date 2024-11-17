<?php

namespace App\Imports;

use App\Models\Subject;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectImport implements ToModel, WithHeadingRow
{
     // To store duplicate entries
     public $duplicates = [];

     public function model(array $row)
     {
         // Check for duplicate subject_code
         $existingSubject = Subject::where('subject_code', $row['subject_code'])->first();
 
         if ($existingSubject) {
             // Add to duplicates array
             $this->duplicates[] = $row['subject_code'];
             // Skip this row by returning null
             return null;
         }
 
         // Create a new Subject model if no duplicate is found
         return new Subject([
             'subject_code'   => $row['subject_code'],
             'description'    => $row['description'],
             'units'          => $row['units'],
             'lec_units'      => $row['lecture_units'] ?? 0,
             'lab_units'      => $row['lab_units'],
             'total_units'    => $row['total_units'],
             'pre_requisite'  => $row['pre_requisite'],
             'total_hours'    => $row['total_hours'],
             'archive_status' => 0,
         ]);
     }
}
