@extends('layouts.app')

@section('title', 'Student Grades')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row mb-3">
            <div class="col-md-6 d-flex justify-content-start">
                <a href="{{ route('phead.students.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Back to Masterlist
                </a>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <a href="{{ route('phead.students.prospectus', $student->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-journal"></i> View Student Prospectus
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Grades of {{ $student->FullName }}</h5>

                        <!-- Export and Search Container -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                           
                            <div id="exportButtonContainer"></div>
                        </div>

                        <!-- Subject-wise Grades Table -->
                        <div class="table-responsive mb-4">
                            <table id="gradesTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Course Code</th>
                                        <th>COurse Description</th>
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

<!-- DataTable Initialization Script -->
<script>
    $(document).ready(function () {
        var table = $('#gradesTable').DataTable({
            dom: '<"dataTables_filter mb-3"f>rt<"bottom"ip>',
            lengthChange: true,
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-sm btn-secondary dropdown-toggle",
                    text: '<i class="bx bxs-file-export me-1 ti-xs"></i>Export',
                    buttons: [
                        { extend: "print", text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }},
                        { extend: "csv", text: '<i class="bx bx-file me-2"></i>CSV', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }},
                        { extend: "excel", text: '<i class="bx bx-spreadsheet me-2"></i>Excel', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }},
                        { extend: "pdf", text: '<i class="bx bxs-file-pdf me-2"></i>Pdf', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }},
                        { extend: "copy", text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }}
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