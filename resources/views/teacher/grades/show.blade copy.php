@extends('layouts.app')
@section('title', 'Grade Management')

@section('content')
<main id="main" class="main">
<section class="section profile">
    <div class="pagetitle">
        <h1>Grade Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Grades</li>
            </ol>
        </nav>
    </div>

  


    <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/images/finalhomebg11.png') }}" alt="Banner image" class="rounded-top">
                </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-3">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset('img/course/default.png') }}" alt="user image" class="d-block ms-0 ms-sm-4 rounded user-profile-img border-dark" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                     <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between      justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <div class="d-flex justify-content-sm-start justify-content-center">
                                <h4 class="mb-0">{{ $subject->description }}</h4>
                            </div>
                                 <span class="fw-light mt-0">{{ $subject->subject_code }}</b></span>
                      
                                <ul class="list-inline mt-2 mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item d-flex gap-1">
                                    <i class="bx bxs-school mt-1"></i> 
                                    <span class="fw-light">Section: {{ $section->name }}</span>
                                    </li>
                                </ul>
                        </div>
                    
                     </div>
                  </div>
              </div>

           
              <div class="card-body">
    <!-- Notification Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ url('/send-grades-notification') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary notify-btn">
                <i class="fas fa-bell"></i> Notify Students
            </button>
        </form>
    </div>

    <!-- Grades Form -->
    <form id="gradesForm" action="{{ route('teacher.grades.storeOrUpdate', $subjectEnrolled->id) }}" method="POST">
        @csrf
        <!-- Add Bootstrap's table-responsive class -->
        <div class="table-responsive">
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
                    <tr data-student-id="{{ $student->id }}" data-subject-enrolled-id="{{ $subjectEnrolled->id }}">
                        <td><input type="checkbox" class="student-checkbox" value="{{ $student->id }}"></td>
                        <td>{{ Str::limit($student->StudentID, 6, '...') }}</td>
                        <td>{{ $student->FullName }}</td>
                        <td><input type="number" step="0.1" name="grades[{{ $student->id }}][prelim]" value="{{ $grade->prelim ?? '' }}" class="form-control grade-input" required></td>
                        <td><input type="number" step="0.1" name="grades[{{ $student->id }}][midterm]" value="{{ $grade->midterm ?? '' }}" class="form-control grade-input" required></td>
                        <td><input type="number" step="0.1" name="grades[{{ $student->id }}][prefinal]" value="{{ $grade->prefinal ?? '' }}" class="form-control grade-input" required></td>
                        <td><input type="number" step="0.1" name="grades[{{ $student->id }}][final]" value="{{ $grade->final ?? '' }}" class="form-control grade-input" required></td>
                        <td><input type="text" name="grades[{{ $student->id }}][remarks]" class="form-control" value="{{ $grade->remarks ?? '' }}" readonly></td>
                        <td class="status-cell">{{ $grade->status ?? '' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm" style="background-color: #b22222; color: white;" data-student-id="{{ $student->id }}">Save</button>
                                <button type="button" class="btn btn-sm" style="background-color: #4682b4; color: white;" data-bs-toggle="modal" data-bs-target="#studentModal{{ $student->id }}">Details</button>
                            </div>
                        </td>
                    </tr>
                       <!-- Modal for Detailed Grade Input -->
                       <div class="modal fade" id="studentModal{{ $student->id }}" tabindex="-1" aria-labelledby="studentModalLabel{{ $student->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="studentModalLabel{{ $student->id }}">Grades for {{ $student->FullName }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Student ID:</strong> {{ $student->StudentID }}</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="prelim">Prelim:</label>
                                                <input type="number" step="0.1" name="modal_grades[{{ $student->id }}][prelim]" value="{{ $grade->prelim ?? '' }}" class="form-control grade-input">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="midterm">Midterm:</label>
                                                <input type="number" step="0.1" name="modal_grades[{{ $student->id }}][midterm]" value="{{ $grade->midterm ?? '' }}" class="form-control grade-input">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="prefinal">Prefinal:</label>
                                                <input type="number" step="0.1" name="modal_grades[{{ $student->id }}][prefinal]" value="{{ $grade->prefinal ?? '' }}" class="form-control grade-input">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="final">Final:</label>
                                                <input type="number" step="0.1" name="modal_grades[{{ $student->id }}][final]" value="{{ $grade->final ?? '' }}" class="form-control grade-input">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <!-- Submit All Grades Button -->
    <button type="button" class="btn btn-primary mt-3" style="background-color: #871616; color: white;" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">Submit All Grades</button>
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
                    <button type="button" class="btn btn-secondary" style="background-color: #6c757d; color: white;" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" style="background-color: #871616; color: white;">Save Changes</button>
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
    </section>
</main>

<style>
/* Grade input adjustment */
.grade-input {
    width: 70px; /* Adjust input width */
    max-width: 100%; /* Ensure inputs are responsive */
}

/* Responsive table adjustments */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 0.8rem;
        padding: 8px;
    }

    .grade-input {
        width: 70px;
    }

    /* Enable horizontal scrolling */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
    }
}

/* Extra small screens */
@media (max-width: 576px) {
    .table th, .table td {
        font-size: 0.7rem;
        padding: 6px;
    }

    .grade-input {
        width: 70px;
    }
}

/* Scrollbar customization for larger screens */
@media (min-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
    }
    /* WebKit-based browsers (Chrome, Safari) */
    .table-responsive::-webkit-scrollbar {
        height: 6px; /* Reduce scrollbar thickness */
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(135, 22, 22, 0.6); /* Subtle scrollbar color */
        border-radius: 10px; /* Round scrollbar edges */
    }

    .table-responsive::-webkit-scrollbar-track {
        background-color: rgba(0, 0, 0, 0.1); /* Track color */
    }

    /* For Firefox */
    .table-responsive {
        scrollbar-width: thin; /* Makes the scrollbar thinner */
        scrollbar-color: rgba(135, 22, 22, 0.6) rgba(0, 0, 0, 0.1); /* Thumb and track colors */
    }
}

/* Button styles */
.btn-primary {
    background-color: #871616;
    border-color: #871616;
}

.btn-primary:hover {
    background-color: #6d1212;
    border-color: #6d1212;
}

.notify-btn {
    background-color: #871616;
    color: white;
    font-weight: bold;
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    box-shadow: 0px 3px 7px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s, transform 0.3s;
}

.notify-btn i {
    margin-right: 6px;
    font-size: 1rem;
}

.notify-btn:hover {
    background-color: #6d1212;
    transform: scale(1.05);
}

.notify-btn:focus {
    outline: none;
    box-shadow: 0 0 8px rgba(135, 22, 22, 0.7);
}

</style>

<script>
    // Conditional coloring for grade inputs (Failing grades)
    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', function() {
            if (parseFloat(this.value) > 3) {
                this.style.backgroundColor = '#f8d7da'; // Red for failing grades
            } else {
                this.style.backgroundColor = ''; // Reset for passing grades
            }
        });
    });
</script>
<script>
$(document).ready(function () {
    // Define the showToast function for notifications
    function showToast(title, body, isSuccess = true) {
        $('#toastTitle').text(title);
        $('#toastBody').html(body); // Use .html to support multiple error lines

        if (isSuccess) {
            $('#toastMessage').removeClass('bg-danger').addClass('bg-success');
        } else {
            $('#toastMessage').removeClass('bg-success').addClass('bg-danger');
        }

        var toastElement = new bootstrap.Toast($('#toastMessage'));
        toastElement.show();
    }

    // Save individual student grade (using event delegation to ensure proper handling for dynamic rows)
    $(document).on('click', '.submit-student-grade', function() {
        const studentId = $(this).data('student-id');
        const row = $(`tr[data-student-id="${studentId}"]`);

        const prelim = row.find('input[name="grades[' + studentId + '][prelim]"]').val();
        const midterm = row.find('input[name="grades[' + studentId + '][midterm]"]').val();
        const prefinal = row.find('input[name="grades[' + studentId + '][prefinal]"]').val();
        const final = row.find('input[name="grades[' + studentId + '][final]"]').val();
        const status = row.find('select[name="grades[' + studentId + '][status]"]').val();

        const formData = {
            student_id: studentId,
            prelim: prelim,
            midterm: midterm,
            prefinal: prefinal,
            final: final,
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: $('#gradesForm').attr('action'), 
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.message) {
                    showToast('Success', response.message);
                    const remarks = final > 3 ? 'Failed' : 'Passed';
                    row.find('input[name="grades[' + studentId + '][remarks]"]').val(remarks);
                    row.find('td:eq(8)').text('draft');
                } else {
                    showToast('Success', 'Grade saved successfully');
                }
            },
            error: function(xhr) {
                let errorMessages = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessages += value + '<br>';
                    });
                } else {
                    errorMessages = 'An error occurred while saving the grade.';
                }
                showToast('Error', errorMessages, false);
            }
        });
    });
    $(document).on('click', '#confirmSubmitBtn', function () {
    var subjectId = "{{ $subjectEnrolled->id }}";
    var grades = {};

    $('input.student-checkbox:checked').each(function () {
        var studentId = $(this).val();
        var row = $(this).closest('tr'); // Get the row

        grades[studentId] = {
            prelim: row.find('input[name="grades[' + studentId + '][prelim]"]').val(),
            midterm: row.find('input[name="grades[' + studentId + '][midterm]"]').val(),
            prefinal: row.find('input[name="grades[' + studentId + '][prefinal]"]').val(),
            final: row.find('input[name="grades[' + studentId + '][final]"]').val(),
            // Remove status from client-side as it is managed on server
            subject_enrolled_id: row.data('subject-enrolled-id') // Ensure subject_enrolled_id is sent
        };
    });

    // Submit only if students are selected
    if (Object.keys(grades).length === 0) {
        errorMessages = 'Please select at least one student to submit grades.';
       
        showToast('Error', errorMessages,false);
        return;
        
    }
    
    $.ajax({
        url: '/teacher/grades/submit-all-grades/' + subjectId,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            grades: grades
        },
        success: function (response) {
            showToast('Success', response.message || 'Grades submitted successfully.');

            // Update rows with selected student grades
            $('input.student-checkbox:checked').each(function () {
                var studentId = $(this).val();
                var row = $(this).closest('tr');
                var finalGrade = grades[studentId].final;
                var remarks = finalGrade > 3 ? 'Failed' : 'Passed';

                row.find('input[name="grades[' + studentId + '][remarks]"]').val(remarks);
                row.find('td.status-cell').text('Reviewing'); // Update status to 'Reviewing'
            });
        },
        error: function (xhr) {
            showToast('Error', 'Error submitting grades.');
            console.log(xhr.responseText);
        }
    });
});

// Handle the "select all" checkboxes logic
$('#selectAll').on('click', function() {
    $('.student-checkbox').prop('checked', this.checked);
});

$('#gradesTable tbody').on('click', '.student-checkbox', function() {
    if ($('.student-checkbox:checked').length === $('.student-checkbox').length) {
        $('#selectAll').prop('checked', true);
    } else {
        $('#selectAll').prop('checked', false);
    }
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

// Handle the "select all" checkboxes logic
$('#selectAll').on('click', function() {
    $('.student-checkbox').prop('checked', this.checked);
});

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
