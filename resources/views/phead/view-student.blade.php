@extends('layouts.app')

@section('title', 'View Student')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-md-12 mb-3 d-flex justify-content-start">
                <a href="{{ route('phead.students.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Back to Students
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-primary text-white mb-3">
                        <h5 class="mb-0"><i class="bi bi-person-badge-fill me-2"></i> Student Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3 ">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                @foreach([
                                    'School ID' => $student->StudentID,
                                    'Full Name' => $student->FullName,
                                    'Birth Date' => $student->Birthday,
                                    'Gender' => $student->Gender,
                                    'Address' => $student->Address,
                                    'Semester' => $student->semester->name ?? 'N/A',
                                    'Year Level' => $student->yearLevel->name ?? 'N/A',
                                    'Section' => $student->section->name ?? 'N/A',
                                    'Program' => $student->program->name ?? 'N/A',
                                    'Scholarship' => $student->Scholarship,
                                    'School Year' => $student->schoolYear->name ?? 'N/A'
                                ] as $label => $value)
                                    <div class="border p-2 mb-2 rounded">
                                        <p class="mb-0"><strong>{{ $label }}:</strong> {{ $value }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                @foreach([
                                    'Birth Place' => $student->BirthPlace,
                                    'Religion' => $student->Religion,
                                    'Citizenship' => $student->Citizenship,
                                    'Type' => $student->Type,
                                    'Student Type' => $student->student_type,
                                    'Category' => $student->category,
                                    'Contact' => $student->contact,
                                    'Father\'s Name' => $student->father_name,
                                    'Father\'s Occupation' => $student->father_occupation,
                                    'Mother\'s Name' => $student->mother_name,
                                    'Mother\'s Occupation' => $student->mother_occupation,
                                    'Previous School' => $student->previous_school,
                                    'Previous School Address' => $student->previous_school_address,
                                    'Status' => $student->isRegistered() ? 'Registered' : 'Not Registered'
                                ] as $label => $value)
                                    <div class="border p-2 mb-2 rounded">
                                        <p class="mb-0">
                                            <strong>{{ $label }}:</strong> 
                                            @if ($label === 'Status')
                                                <span class="badge @if($student->isRegistered()) bg-success @else bg-secondary @endif">
                                                    {{ $value }}
                                                </span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection