@extends('layouts.app')
@section('title', 'Grade Management')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Grade Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Grades</li>
            </ol>
        </nav>
    </div>
    <div class="container">
        <h2>{{ $subject->subject_code }} - {{ $subject->description }}</h2>
        <h4>Section: {{ $section->name }}</h4>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form action="{{ url('/send-grades-notification') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Notify Students</button>
                    </form>
                </div>

                <form id="gradesForm" action="{{ route('teacher.grades.storeOrUpdate', $subjectEnrolled->id) }}" method="POST">
    @csrf
    <table class="table table-bordered table-hover" id="gradesTable">
        <thead class="table-danger">
            <tr>
                <th scope="col"><input type="checkbox" id="selectAll"></th>
                <th scope="col">Student ID</th>
                <th scope="col">Student Name</th>
                <th scope="col">Prelim</th>
                <th scope="col">Midterm</th>
                <th scope="col">Prefinal</th>
                <th scope="col">Final</th>
                <th scope="col">Remarks</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            @php
                $subjectEnrolled = $student->subjectsEnrolled->where('subject_id', $subject->id)->first();
                $grade = $subjectEnrolled ? $subjectEnrolled->grades()->where('student_id', $student->id)->first() : null;
            @endphp
            <tr data-student-id="{{ $student->id }}" class="student-grade-row">
                 <!-- Checkbox to select student -->
            <td>
                <input type="checkbox" class="student-checkbox" value="{{ $student->id }}">
            </td>

                <td>{{ $student->StudentID }}</td>
                <td>{{ $student->FullName }}</td>
                <td><input type="number" step="0.1" name="grades[{{ $student->id }}][prelim]" value="{{ $grade->prelim ?? '' }}" class="form-control prelim-input" required></td>
                <td><input type="number" step="0.1" name="grades[{{ $student->id }}][midterm]" value="{{ $grade->midterm ?? '' }}" class="form-control midterm-input" required></td>
                <td><input type="number" step="0.1" name="grades[{{ $student->id }}][prefinal]" value="{{ $grade->prefinal ?? '' }}" class="form-control prefinal-input" required></td>
                <td><input type="number" step="0.1" name="grades[{{ $student->id }}][final]" value="{{ $grade->final ?? '' }}" class="form-control final-input" required></td>
                <td><input type="text" name="grades[{{ $student->id }}][remarks]" class="form-control" value="{{ $grade->remarks ?? '' }}" readonly></td>
                <td>{{ $grade->status ?? 'Draft' }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success submit-student-grade" data-student-id="{{ $student->id }}">Save</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>


                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">Submit All Grades</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSubmitLabel">Confirm Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit the grades for review? Once submitted, the program head will review them.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Proceed</button>
                </div>
            </div>
        </div>
    </div>





  <!-- Modal -->
<div class="modal fade" id="importGradeModal" tabindex="-1" aria-labelledby="importGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importGradeModalLabel">Import Grades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Updated File Upload Input -->
                <form id="gradeUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="custom-file-upload">
                        <label for="gradesFile" class="form-label">
                            <div class="file-upload-wrapper">
                                <i class="bx bx-cloud-upload"></i>
                                <span id="fileLabel">Select or Drop File Here</span>
                                <input class="form-control" type="file" id="gradesFile" name="file" accept=".xlsx, .xls, .csv" required style="display: none;">
                            </div>
                            <small class="form-text text-muted">Accepted formats: .xlsx, .xls, .csv</small>
                        </label>
                    </div>

                    <div id="progressWrapper" class="progress mt-3" style="display: none;">
                        <div id="uploadProgress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </form>
                <div id="filePreview" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitFileBtn">
                    <i class="bx bx-upload"></i> Upload Grades
                </button>
            </div>
        </div>
    </div>
</div>

        <!-- Toast Notifications (Google-Style) -->
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
            <div id="toastMessage" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong id="toastTitle" class="me-auto" style="color: #212529; font-weight: bold;"></strong>
                        <small style="color: #343a40; font-weight: bold;">Just now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                <div class="toast-body" id="toastBody" class="text-white"></div>
            </div>
        </div>
    <!-- End Modal -->
</main>

<script>
$(document).ready(function () {

// Define the showToast function for notifications
function showToast(title, body, isSuccess = true) {
    $('#toastTitle').text(title);
    $('#toastBody').text(body);

    if (isSuccess) {
        $('#toastMessage').removeClass('bg-danger').addClass('bg-success');
    } else {
        $('#toastMessage').removeClass('bg-success').addClass('bg-danger');
    }

    var toastElement = new bootstrap.Toast($('#toastMessage'));
    toastElement.show();
}

// Save individual student grade
$(document).ready(function() {
    $('.submit-student-grade').on('click', function() {
        const studentId = $(this).data('student-id');
        const row = $(`tr[data-student-id="${studentId}"]`);

        // Fetch values from the input fields
        const prelim = row.find('input[name="grades[' + studentId + '][prelim]"]').val();
        const midterm = row.find('input[name="grades[' + studentId + '][midterm]"]').val();
        const prefinal = row.find('input[name="grades[' + studentId + '][prefinal]"]').val();
        const final = row.find('input[name="grades[' + studentId + '][final]"]').val();
        const status = row.find('select[name="grades[' + studentId + '][status]"]').val();  // Fetch status from a dropdown or input
        
        const formData = {
            student_id: studentId,
            prelim: prelim,
            midterm: midterm,
            prefinal: prefinal,
            final: final,
            status: status,  // Add status to the request
            _token: $('meta[name="csrf-token"]').attr('content') // Use CSRF token from meta tag
        };

        $.ajax({
            url: $('#gradesForm').attr('action'), // The form action URL for the request
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.message) {
                    showToast('Success', response.message); // Display a success message
                    location.reload(); 
                } else {
                    showToast('Success', 'Grade saved successfully');
                    location.reload(); 
                }
            },
            error: function(xhr) {
                // Handle errors (like validation issues or server errors)
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorMessages = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessages += value + '<br>';
                    });
                    showToast('Error', errorMessages, false); // Display validation errors
                } else {
                    showToast('Error', 'An error occurred while saving the grade', false);
                }
            }
        });
    });
});


$(document).on('click', '#confirmSubmitBtn', function () {
    var subjectId = "{{ $subjectEnrolled->id }}";
    var grades = {};

    // Gather grades for each selected student
    $('input.student-checkbox:checked').each(function () {
        var studentId = $(this).val();  // Get the student ID from the checkbox value
        var row = $(this).closest('tr');  // Get the corresponding table row
        
        // Collect the grades for the selected student
        grades[studentId] = {
            prelim: row.find('input[name="grades[' + studentId + '][prelim]"]').val(),
            midterm: row.find('input[name="grades[' + studentId + '][midterm]"]').val(),
            prefinal: row.find('input[name="grades[' + studentId + '][prefinal]"]').val(),
            final: row.find('input[name="grades[' + studentId + '][final]"]').val()
        };
    });

    // Ensure at least one student is selected
    if (Object.keys(grades).length === 0) {
        alert('Please select at least one student to submit grades.');
        return;
    }

    // Submit grades via AJAX
    $.ajax({
        url: '/teacher/grades/submit-all-grades/' + subjectId,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            grades: grades
        },
        success: function (response) {
            alert('Grades have been submitted for the selected students.');
        },
        error: function (xhr, status, error) {
            alert('Error submitting grades: ' + error);
        }
    });
});

});

</script>


<script>
 $(document).ready(function () {
    var table = $('#gradesTable').DataTable({
        lengthChange: true, // Enable or disable show entries
        buttons: [
            {
                extend: "collection",
                className: "btn btn-sm btn-secondary dropdown-toggle mx-3",
                text: '<i class="bx bxs-file-export me-1 ti-xs"></i>Export',
                buttons: [
                    {
                        extend: "print",
                        text: '<i class="bx bx-printer me-2" ></i>Print',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            format: {
                                body: function (e, t, a) {
                                    var s;
                                    return e.length <= 0
                                        ? e
                                        : ((e = $.parseHTML(e)),
                                            (s = ""),
                                            $.each(e, function (e, t) {
                                                void 0 !== t.classList && t.classList.contains("user-name") ? (s += t.lastChild.firstChild.textContent) : void 0 === t.innerText ? (s += t.textContent) : (s += t.innerText);
                                            }),
                                            s);
                                },
                            },
                        },
                        customize: function (e) {
                            $(e.document.body).css("color", s).css("border-color", t).css("background-color", a),
                                $(e.document.body).find("table").addClass("compact").css("color", "inherit").css("border-color", "inherit").css("background-color", "inherit");
                        },
                    },
                    {
                        extend: "csv",
                        text: '<i class="bx bx-file me-2" ></i>CSV',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6],
                            format: {
                                body: function (e, t, a) {
                                    var s;
                                    return e.length <= 0
                                        ? e
                                        : ((e = $.parseHTML(e)),
                                            (s = ""),
                                            $.each(e, function (e, t) {
                                                void 0 !== t.classList && t.classList.contains("user-name") ? (s += t.lastChild.firstChild.textContent) : void 0 === t.innerText ? (s += t.textContent) : (s += t.innerText);
                                            }),
                                            s);
                                },
                            },
                        },
                    },
                    {
                        extend: "excel",
                        text: '<i class="bx bx-spreadsheet me-2"></i>Excel',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7],
                            format: {
                                body: function (e, t, a) {
                                    var s;
                                    return e.length <= 0
                                        ? e
                                        : ((e = $.parseHTML(e)),
                                            (s = ""),
                                            $.each(e, function (e, t) {
                                                void 0 !== t.classList && t.classList.contains("user-name") ? (s += t.lastChild.firstChild.textContent) : void 0 === t.innerText ? (s += t.textContent) : (s += t.innerText);
                                            }),
                                            s);
                                },
                            },
                        },
                    },
                    {
                        extend: "pdf",
                        text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7],
                            format: {
                                body: function (e, t, a) {
                                    var s;
                                    return e.length <= 0
                                        ? e
                                        : ((e = $.parseHTML(e)),
                                            (s = ""),
                                            $.each(e, function (e, t) {
                                                void 0 !== t.classList && t.classList.contains("user-name") ? (s += t.lastChild.firstChild.textContent) : void 0 === t.innerText ? (s += t.textContent) : (s += t.innerText);
                                            }),
                                            s);
                                },
                            },
                        },
                    },
                    {
                        extend: "copy",
                        text: '<i class="bx bx-copy me-2" ></i>Copy',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            format: {
                                body: function (e, t, a) {
                                    var s;
                                    return e.length <= 0
                                        ? e
                                        : ((e = $.parseHTML(e)),
                                            (s = ""),
                                            $.each(e, function (e, t) {
                                                void 0 !== t.classList && t.classList.contains("user-name") ? (s += t.lastChild.firstChild.textContent) : void 0 === t.innerText ? (s += t.textContent) : (s += t.innerText);
                                            }),
                                            s);
                                },
                            },
                        },
                    },
                    {
                        text: '<i class="bx bxs-file-blank"></i>Template',
                        className: "dropdown-item",
                        action: function () {
                            window.location.href = "{{ route('teacher.grades.template', ['subjectEnrolled' => $subjectEnrolled->id]) }}";
                        }
                    }
                ],
            },
            {
                text: '<i class="bx bxs-file-import me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Import</span>',
                className: "add-new btn btn-sm btn-portal",
                attr: { "data-bs-toggle": "modal", "data-bs-target": "#importGradeModal" },
            },
        ],
        initComplete: function () {
            console.log("Init complete");
            this.api().columns([3, 4, 5]).every(function (colIdx) {
                var column = this;
                var select = $('<select class="form-select"><option value="">Select ' + column.header().innerHTML + '</option>')
                    .appendTo($('.user_' + column.header().innerHTML.toLowerCase()))
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                column.data().unique().sort().each(function (d) {
                    if (d.indexOf('>') !== -1) {
                        d = $(d).text().trim();
                    }
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });
        },
        language: {
            lengthMenu: '_MENU_',
            search: "",
            searchPlaceholder: "Search.."
        }
    });
// Select all checkboxes when the header checkbox is clicked
$('#selectAll').on('click', function() {
        $('.student-checkbox').prop('checked', this.checked);
    });

    // If all checkboxes are selected, check the select-all checkbox; otherwise, uncheck it
    $('#gradesTable tbody').on('click', '.student-checkbox', function() {
        if ($('.student-checkbox:checked').length === $('.student-checkbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });
    table.buttons().container()
        .appendTo($('.dataTables_filter', table.table().container()));
});

</script>
<script>
$(document).ready(function () {
    // Custom file upload UI
    $('#gradesFile').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#fileLabel').text(fileName || 'Select or Drop File Here');
    });

    // Toast notification function
    function showToast(title, message, success = true) {
        const toastElement = new bootstrap.Toast(document.getElementById('toastMessage'));
        $('#toastTitle').text(title);
        $('#toastBody').text(message);
        $('#toastMessage').removeClass('bg-success bg-danger').addClass(success ? 'bg-success' : 'bg-danger');
        toastElement.show();
    }

    // Handle file upload via AJAX
    $('#submitFileBtn').on('click', function (e) {
        e.preventDefault();

        var formData = new FormData();
        var fileInput = document.getElementById('gradesFile');
        var file = fileInput.files[0];

        if (!file) {
            showToast('Error', 'Please select a valid file.', false);
            return;
        }

        // Append file to FormData
        formData.append("file", file);
        formData.append("_token", "{{ csrf_token() }}");

        // Show progress bar
        $('#progressWrapper').show();
        $('#uploadProgress').css('width', '0%').attr('aria-valuenow', 0);

        // Make AJAX request
        $.ajax({
            url: "{{ route('teacher.grades.import') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('#uploadProgress').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
                    }
                }, false);
                return xhr;
            },
            success: function (response) {
                $('#uploadProgress').removeClass('progress-bar-striped progress-bar-animated').addClass('bg-success');
                $('#uploadProgress').html('<i class="bx bx-check-circle"></i> File Uploaded Successfully!');
                
                // Show success message
                showToast('Success', 'Grades file imported successfully!');

                // Reset file input
                $('#gradesFile').val('');
                $('#fileLabel').text('Select or Drop File Here');
                
                // Close modal after short delay
                setTimeout(() => {
                    $('#importGradeModal').modal('hide');
                }, 1500);

                // Reload the grade table
                $('#gradesTable').load(location.href + ' #gradesTable');
            },
            error: function (response) {
                $('#uploadProgress').addClass('bg-danger');
                $('#uploadProgress').html('<i class="bx bx-x-circle"></i> Upload Failed');
                showToast('Error', 'An error occurred during the upload.', false);
            }
        });
    });
});
</script>
@endsection
