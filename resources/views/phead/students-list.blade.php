@extends('layouts.app')

@section('title', 'Students')

@section('content')
<main id="main" class="main">
    <section class="container">
        <div class="row">
            <h1>Student Masterlist</h1>
            <!-- Main Content -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                            <div class="col-md-4 user_year_level"></div>
                            <div class="col-md-4 user_section"></div>
                            <div class="col-md-4 user_semester"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="student" class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">School ID</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Year Level</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Semester</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                    <tr>
                                        <td>{{ $item->StudentID }}</td>
                                        <td>{{ $item->FullName }}</td>
                                        <td>{{ $item->yearLevel->name ?? 'N/A' }}</td>
                                        <td>{{ $item->section->name ?? 'N/A' }}</td>   
                                        <td>{{ $item->semester->name ?? 'N/A' }}</td>  
                                        <td>
                                            <a href="{{ route('phead.students.view', $item->id) }}" class="btn btn-sm btn-info me-2">
                                                <i class="bx bx-user me-1"></i> View Info
                                            </a>
                                            <a href="{{ route('phead.students.grades', $item->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bx bx-book me-1"></i> View Grades
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Main Content -->
        </div>
    </section>
</main>

<!-- DataTable Initialization Script -->
<script>
    $(document).ready(function () {
        var table = $('#student').DataTable({
            dom: 'Bfrtip',
            lengthChange: true,
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-sm btn-secondary dropdown-toggle mx-3",
                    text: '<i class="bx bxs-file-export me-1 ti-xs"></i>Export',
                    buttons: [
                        { extend: "print", text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4] }},
                        { extend: "csv", text: '<i class="bx bx-file me-2"></i>CSV', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4] }},
                        { extend: "excel", text: '<i class="bx bx-spreadsheet me-2"></i>Excel', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4] }},
                        { extend: "pdf", text: '<i class="bx bxs-file-pdf me-2"></i>Pdf', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4] }},
                        { extend: "copy", text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: { columns: [0, 1, 2, 3, 4] }}
                    ]
                }
            ],
            initComplete: function () {
                this.api().columns([2, 3, 4]).every(function (colIdx) {
                    var column = this;
                    var headerText = column.header().innerHTML.toLowerCase().replace(" ", "_");
                    var select = $('<select class="form-select"><option value="">Select ' + column.header().innerHTML + '</option>')
                        .appendTo($('.user_' + headerText))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function (d) {
                        if (d && d.trim()) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        }
                    });
                });
            },
            language: {
                search: "",
                searchPlaceholder: "Search..."
            }
        });

        table.buttons().container().appendTo($('.dataTables_filter', table.table().container()));
    });
</script>
@endsection
