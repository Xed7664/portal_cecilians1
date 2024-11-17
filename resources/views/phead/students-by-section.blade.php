@extends('layouts.app')

@section('title', 'Students in Section')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="pagetitle">
            <h1>Student Masterlist</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                    <li class="breadcrumb-item active">Section-{{ $section->name ?? 'N/A' }} Masterlist</li>
                </ol>
            </nav>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('phead.yearandsection') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Back to Students
                </a>
            </div>
        </div>

        @if($students->isEmpty())
            <div class="alert alert-info" role="alert">
                No students enrolled in this section.
            </div>
        @else
            <div class="card">
                <div class="card-body">
                   
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <div id="exportButtonContainer" class="flex-grow-1">
                                       
                                    </div>
                                    <div id="searchContainer" class="flex-grow-1">
                                       
                                
                                    </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="studentsTable" class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Birthdate</th>
                                    <th>Gender</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->StudentID }}</td>
                                        <td>{{ $student->FullName }}</td>
                                        <td>{{ $student->Birthday }}</td>
                                        <td>{{ $student->Gender }}</td>
                                        <td>{{ $student->Address }}</td>
                                        <td>{{ $student->contact }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </section>
</main>


</style>

<!-- DataTable Initialization Script -->
<script>
    $(document).ready(function () {
        var table = $('#studentsTable').DataTable({
            dom: '<"dataTables_filter mb-3"f>rt<"bottom"ip>',
            lengthChange: true,
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-sm btn-secondary dropdown-toggle",
                    text: '<i class="bx bxs-file-export me-1 ti-xs"></i>Export',
                    buttons: [
                        { extend: "print", text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5] }},
                        { extend: "csv", text: '<i class="bx bx-file me-2"></i>CSV', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5] }},
                        { extend: "excel", text: '<i class="bx bx-spreadsheet me-2"></i>Excel', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5] }},
                        { extend: "pdf", text: '<i class="bx bxs-file-pdf me-2"></i>PDF', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5] }},
                        { extend: "copy", text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5] }}
                    ]
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });

        // Append export buttons to container
        table.buttons().container().appendTo($('#exportButtonContainer'));
    });
</script>

@endsection