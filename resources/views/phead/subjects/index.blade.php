@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Subjects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                <li class="breadcrumb-item active">Subject Management</li>
            </ol>
        </nav>
    </div>
    <section class="subjects-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <div class="add-new btn btn-sm btn-portal">
                            <i class="bx bxs-file-import me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Import</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="subjects" class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Subject Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col" class="text-center">Lecture Units</th>
                                        <th scope="col" class="text-center">Lab Units</th>
                                        <th scope="col" class="text-center">Total Units</th>
                                        <th scope="col" class="text-center">Total Hours</th>
                                        <th scope="col">Pre-requisite</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->description }}</td>
                                        <td class="text-center">{{ $subject->lec_units }}</td>
                                        <td class="text-center">{{ $subject->lab_units }}</td>
                                        <td class="text-center">{{ $subject->total_units }}</td>
                                        <td class="text-center">{{ $subject->total_hours }}</td>
                                        <td>{{ $subject->pre_requisite ?? 'None' }}</td>
                                        <td>
                                            <form action="{{ route('phead.subjects.archive', $subject->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-archive"></i> Archive
                                                </button>
                                            </form>
                                        </td>
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

<!-- Import Subjects Modal -->
<div class="modal fade" id="importSubjectsModal" tabindex="-1" aria-labelledby="importSubjectsModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importSubjectModalLabel">
                    <i class="bi bi-upload me-2"></i>Import Subjects
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importDropzone" action="{{ route('subject.import') }}" method="POST" class="dropzone">
                    @csrf
                    <div class="dz-message">
                        Drop your file here or click to upload.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    Dropzone.autoDiscover = false;

    $(document).ready(function () {

        $('.add-new').on('click', function () {
        $('#importSubjectsModal').modal('show');
    });

    function showToast(message, type) {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'error' ? "#ff6b6b" : "#51cf66",
        }).showToast();
    }
    var myDropzone = new Dropzone("#importDropzone", {
        paramName: "file",
        maxFilesize: 2, // MB
        acceptedFiles: ".csv, .xls, .xlsx",
        dictDefaultMessage: "Drop files here or click to upload.",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        init: function () {
            this.on("success", function (file, response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    setTimeout(function () {
                        $('#importSubjectsModal').modal('hide');
                        location.reload();
                    }, 1000);
                } else {
                    showToast(response.message || "An error occurred during import.", 'error');
                }
            });

            this.on("error", function (file, response) {
                let errorMessage;

                if (typeof response === "object" && response.message) {
                    errorMessage = response.message;
                } else if (typeof response === "string") {
                    errorMessage = response;
                } else {
                    errorMessage = "An unknown error occurred while uploading the file.";
                }

                showToast("Error: " + errorMessage, 'error');
            });

            this.on("processingerror", function (file) {
                showToast("File processing error. Please check the file format.", 'error');
            });
        }
    });

        var table = $('#subjects').DataTable({
        paging: true, 
        pageLength: 10, 
        lengthMenu: [10, 25, 50, 100], 
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' + 
             '<"row"<"col-sm-12"tr>>' + 
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>', 
        initComplete: function () {
            console.log("Init complete"); // Log when initialization is complete
            this.api().columns([3, 4, 5]).every(function (colIdx) {
                var column = this;
                console.log("Column header:", column.header().innerHTML); // Log the column header
                var select = $('<select class="form-select"><option value="">Select ' + column.header().innerHTML + '</option>')
                    .appendTo($('.user_' + column.header().innerHTML.toLowerCase()))
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        console.log("Selected value:", val);
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                var uniqueValues = column.data().unique().sort();
                console.log("Unique values:", uniqueValues); // Log unique values
                uniqueValues.each(function (d, j) {
                    if (d.indexOf('>') !== -1) {
                        d = $(d).text().trim();
                    }
                    console.log("Appending option:", d); // Log the option being appended
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });
        },
        language: {
            lengthMenu: 'Show _MENU_ entries', // Customize length menu text
            search: "", // Remove default search label
            searchPlaceholder: "Search.." // Add search placeholder
        }
        });

        table.buttons().container()
        .appendTo($('.dataTables_filter', table.table().container()));

    });
</script>
@endsection

     
   

