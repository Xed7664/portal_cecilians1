@extends('layouts.app')

@section('title', 'Students')

@section('content')
<main id="main" class="main">
<section class="section profile">
    <div class="pagetitle">
        <h1>Sudent Masterlist</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Enrolled Student</li>
            </ol>
        </nav>
    </div>

      <div class="card mb-4 px-4 py-3">
    <div class="user-profile-header-banner">
        <img src="{{ asset('assets/images/finalhomebg11.png') }}" alt="Banner image" class="rounded-top">
    </div>

    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-3">
        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
            <img src="{{ asset('img/course/default.png') }}" alt="user image" class="d-block ms-0 ms-sm-4 rounded user-profile-img border-dark" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
    </div>

        <div class="row">
            <div class="col-lg-12">

                <!-- <div class="card"> -->
                    <div class="card-header border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3 user_department"></div>
                            <div class="col-md-3 user_year_level"></div>
                            <div class="col-md-3 user_section"></div>
                            <div class="col-md-3 user_gender"></div>
                            <div class="col-md-3 user_school_year"></div>
                            <div class="col-md-3 user_semester"></div>
                            
                          
                        </div>
                    </div> <br>

                    <div class="card-body">
                        <div class="table-responsive">
                        <div class="container-fluid">
                            
                            <table id="student" class="table table-striped datatable">
                                 <thead class="table-dark">
                                    <tr>
                                         <th scope="col">No.</th>
                                        <th scope="col">School ID</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Program</th>
                                        <th scope="col">Year Level</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">School Year</th>
                                        <th scope="col">Semester</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->StudentID }}</td>
                                            <td>{{ $student->FullName }}</td>
                                            <td>{{ $student->program->code ?? '-' }}</td>
                                            <td>{{ $student->yearLevel->name ?? '-' }}</td>
                                            <td>{{ $student->section->name ?? '-' }}</td>
                                            <td><span class="text-truncate d-flex align-items-center"><span class="badge badge-center me-2"><div class="btn btn-sm @if(strtolower($student->Gender) === 'male') bg-primary-subtle @else bg-danger-subtle @endif rounded-circle">@if(strtolower($student->Gender) === 'male')<i class="bx bx-male-sign"></i>@else<i class="bx bx-female-sign"></i>@endif</div></span>{{ ucfirst($student->Gender) }}</span></td>
                                            <td>{{ $student->schoolYear->name ?? '-' }}</td>  
                                            <td>{{ $student->semester->name ?? '-' }}</td>  
                                            <td class="text-nowrap"> <!-- Keep buttons inline -->
                                                <!-- <a href="#" class="btn btn-info btn-sm me-1">
                                                    <i class="bx bx-user"></i> View Info
                                                </a> -->
                                                <a href="{{ route('teacher.students.grades', $student->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-book"></i> View Grades
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </section>
</main>
<script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('#student').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"fB>>rtip',
            lengthChange: true,
            pageLength: 10, // Default page length
            lengthMenu: [10, 15, 25, 50], // Dropdown options for number of entries
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-sm btn-secondary dropdown-toggle",
                    text: '<i class="bx bxs-file-export me-1 ti-xs"></i> Export',
                    buttons: [
                        { extend: "print", text: '<i class="bx bx-printer me-2"></i> Print', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }},
                        { extend: "csv", text: '<i class="bx bx-file me-2"></i> CSV', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }},
                        { extend: "excel", text: '<i class="bx bx-spreadsheet me-2"></i> Excel', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }},
                        { extend: "pdf", text: '<i class="bx bxs-file-pdf me-2"></i> PDF', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }},
                        { extend: "copy", text: '<i class="bx bx-copy me-2"></i> Copy', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }}
                    ]
                }
            ],
            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: "", // Remove default search label
                searchPlaceholder: "Search.." // Custom search placeholder
            },
            initComplete: function () {
                console.log("Init complete");

                const columnClassMapping = {
                    3: 'user_department',
                    4: 'user_year_level',
                    5: 'user_section',
                    6: 'user_gender',
                    7: 'user_school_year',
                    8: 'user_semester'
                };

                this.api().columns().every(function (colIdx) {
                    var column = this;

                    if (columnClassMapping[colIdx]) {
                        const columnClass = columnClassMapping[colIdx];
                        const select = $('<select class="form-select form-select-sm"><option value="">Select ' + column.header().innerHTML + '</option>')
                            .appendTo($('.' + columnClass))
                            .on('change', function () {
                                const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            const textContent = $('<div>').html(d).text().trim();
                            if (textContent) {
                                select.append('<option value="' + textContent + '">' + textContent + '</option>');
                            }
                        });
                    }
                });
            }
        });

        // Append export buttons to the right of the search bar
        table.buttons().container().appendTo($('.dataTables_filter', table.table().container()));
    });
</script>



@endsection
