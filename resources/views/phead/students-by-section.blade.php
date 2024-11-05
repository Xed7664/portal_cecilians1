@extends('layouts.app')

@section('title', 'Students in Section')

@section('content')
<main id="main" class="main">
    <div class="col-md-12 mb-0 d-flex justify-content-start">
        <a href="{{ route('phead.yearandsection') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left-circle"></i> Back to Students
        </a>
    </div>
    <section class="section py-5">
        <div class="container">
            <h2 class="text-center mb-5">
               Section-{{ $section->name ?? 'N/A' }}  Masterlist
            </h2>

            @if($students->isEmpty())
                <p class="text-center">No students enrolled in this section.</p>
            @else
                <!-- Export and Search Container -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div id="exportButtonContainer"></div>
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
            @endif
        </div>
    </section>
</main>

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
