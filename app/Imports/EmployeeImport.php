<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $mappedRow = $this->mapHeaders($row);

        // Skip if EmployeeID or FullName is missing
        if (empty($mappedRow['EmployeeID']) || empty($mappedRow['FullName'])) {
            \Log::warning('Skipping row with missing EmployeeID or FullName', ['row' => $row]);
            return null;
        }

        // Format Birthday
        $birthday = null;
        if (!empty($mappedRow['Birthday'])) {
            try {
                $birthday = Carbon::createFromFormat('Y-m-d', $mappedRow['Birthday'])->format('Y-m-d');
            } catch (\Exception $e) {
                \Log::warning('Invalid date format for Birthday', ['row' => $row]);
            }
        }

        // Determine department_id based on the department code
        $departmentId = $this->mapDepartmentId($mappedRow['Department']);

        // Determine user type based on EmployeeType
        $type = $this->mapEmployeeType($mappedRow['EmployeeType']);
        $email = $mappedRow['Email'] ?? Str::slug($mappedRow['FullName']) . '@example.com';
        $username = Str::slug($mappedRow['FullName']) . rand(1000, 9999);
          // Generate a unique google_id
        $googleId = $this->generateUniqueGoogleId();


        // Create user record in the users table
        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => '$2y$10$VAU1NXNA5A1ZPyavKoH0l.pFTyzgbXQUJB9jP9Hwmb8',
            'type' => $type,
            'employee_id' => $mappedRow['EmployeeID'],
            'avatar' => 'default-profile.png',
            'status' => 'unverified',
            'google_id' => $googleId, // Save the generated Google ID
        ]);

       // Generate a verification link
        $verificationUrl = route('reset.credentials', ['id' => $user->id, 'hash' => sha1($user->email)]);

        // Send an email with auto-generated credentials
        Mail::to($user->email)->send(new VerificationMail($user, $username, $verificationUrl));

        

        // Create employee record and link it with the user_id
        return Employee::create([
            'EmployeeID' => $mappedRow['EmployeeID'],
            'FullName' => $mappedRow['FullName'],
            'Birthday' => $birthday,
            'Gender' => $mappedRow['Gender'],
            'department_id' => $departmentId,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Generate a unique Google ID.
     *
     * @return string
     */
    protected function generateUniqueGoogleId()
    {
        do {
            $googleId = Str::uuid()->toString(); // Generate a unique UUID
        } while (User::where('google_id', $googleId)->exists());

        return $googleId;
    }
    /**
     * Map headers from the Excel file to database fields.
     */
    protected function mapHeaders(array $row): array
    {
        return [
            'EmployeeID' => $row['employeeid'] ?? null,
            'FullName' => $row['fullname'] ?? null,
            'Birthday' => $row['birthday'] ?? null,
            'Gender' => $row['gender'] ?? null,
            'Department' => $row['department'] ?? null,
            'EmployeeType' => $row['employeetype'] ?? null,
            'Email' => $row['email'] ?? null,
        ];
    }

    /**
     * Map department code to department_id.
     */
    protected function mapDepartmentId($departmentCode)
    {
        return match (strtoupper($departmentCode)) {
            'BSIT' => 1,
            'BSBA' => 2,
            'BEED' => 3,
            'BSCRIM' => 4,
            'BSHTM' => 5,
            default => null, // Return null if no match found
        };
    }

    /**
     * Map EmployeeType to user type enum.
     */
    protected function mapEmployeeType($type)
    {
        return match (strtolower($type)) {
            'teacher' => 'teacher',
            'program head' => 'program_head',
            'admin', 'registrar', 'assistant registrar' => 'admin',
            default => 'teacher',
        };
    }

    /**
     * Specify the row number containing the headers.
     */
    public function headingRow(): int
    {
        return 1;
    }
}
