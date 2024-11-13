@extends('layouts.app')

@section('title', 'Student Grades')

@section('content')
<main id="main" class="main">
<section class="section profile">
    <div class="pagetitle">
        <h1>Student Grade List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teacher.fetchEnrolledStudents') }}">Enrolled Student</a></li>
                <li class="breadcrumb-item active">Student Grade</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

            <!-- Student Information Centered as Header -->
                 <div class="d-flex flex-column align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('img/profile/' . ($student->user->avatar ?? 'default-profile.png')) }}" 
                            alt="{{ $student->FullName }}'s Avatar" 
                            class="rounded-circle me-3 border border-custom"
                            style="width: 100px; height: 100px; object-fit: cover;">

                        <div class="text-center text-sm-start">
                            <h5 class="card-title mb-1">{{ $student->FullName }}</h5>
                            <p class="mb-0 text-muted">{{ $student->program->name ?? 'Program Not Set' }}</p>
                            <p class="mb-0 text-muted">{{ $student->yearLevel->name ?? 'Year Level Not Set' }} | Section {{ $student->section->name ?? 'Not Set' }}</p>
                        </div>
                    </div>
                </div>


                    <!-- Subject-wise Grades Table -->
                    <div class="table-responsive mb-4">
                        <table id="gradesTable" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Description</th>
                                    <th>Prelim</th>
                                    <th>Midterm</th>
                                    <th>Pre-Final</th>
                                    <th>Final</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject->subject_code }}</td>
                                        <td>{{ $grade->subject->description }}</td>
                                        <td>{{ $grade->prelim }}</td>
                                        <td>{{ $grade->midterm }}</td>
                                        <td>{{ $grade->prefinal }}</td>
                                        <td>{{ $grade->final }}</td>
                                        <td>{{ $grade->remarks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
<style>
.border-custom {
    border: 3px solid #871616 !important;
}
</style>
<script>
    $(document).ready(function () {
        var table = $('#gradesTable').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"bottom"ip>',
            lengthChange: true,
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-sm btn-secondary dropdown-toggle",
                    text: '<i class="bx bxs-file-export me-1 ti-xs"></i>Export',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="bx bx-printer me-2"></i>Print',
                            className: "dropdown-item",
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                        },
                        {
                            extend: "csv",
                            text: '<i class="bx bx-file me-2"></i>CSV',
                            className: "dropdown-item",
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                        },
                        {
                            extend: "excel",
                            text: '<i class="bx bx-spreadsheet me-2"></i>Excel',
                            className: "dropdown-item",
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                        },
                        {
                            extend: "pdf",
                            text: '<i class="bx bxs-file-pdf me-2"></i>PDF',
                            className: "dropdown-item",
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                        },
                        {
                            extend: "copy",
                            text: '<i class="bx bx-copy me-2"></i>Copy',
                            className: "dropdown-item",
                            exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                        }
                    ]
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });

        // Append export buttons to the custom container for better styling
        table.buttons().container().appendTo('#exportButtonContainer');
    });
</script>


@endsection