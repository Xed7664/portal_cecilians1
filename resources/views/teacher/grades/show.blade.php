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
                    <!-- Left side: Show entries dropdown -->

                    <!-- Right side: Search bar, Export buttons, and Download Template -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('teacher.grades.template', ['subjectEnrolled' => $subjectEnrolled->id]) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                     <!-- Button to trigger sending email notifications -->
    <form action="{{ url('/send-grades-notification') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Notify Students</button>
    </form>
                </div>
      <div class="card mb-4">
            <div class="card-header">
                <h5>Upload Grades</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('teacher.grades.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="gradesFile" class="form-label">Select Grades File</label>
                        <input class="form-control" type="file" id="gradesFile" name="file" accept=".xlsx, .xls, .csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Grades
                    </button>
                </form>
            </div>
        </div>
                <form action="{{ route('teacher.grades.storeOrUpdate', $subjectEnrolled->id) }}" method="POST">
                    @csrf
                    <h5 class="card-title"></h5>
                    <table class="table table-bordered table-hover" id="gradesTable">
                        <thead class="table-danger">
                            <tr>
                                <th scope="col">Student ID</th>
                                <th scope="col">Student Name</th>
                                <th scope="col">Prelim</th>
                                <th scope="col">Midterm</th>
                                <th scope="col">Prefinal</th>
                                <th scope="col">Final</th>
                                <th scope="col">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $student)
                            @php
                                $subjectEnrolled = $student->subjectsEnrolled->where('subject_id', $subject->id)->first();
                                $grade = $subjectEnrolled ? $subjectEnrolled->grades()->where('student_id', $student->id)->first() : null;
                            @endphp
                            <tr>
                                <td>{{ $student->StudentID }}</td>
                                <td>{{ $student->FullName }}</td>
                                <td>
                                    <input type="number" step="0.1" name="students[{{ $student->id }}][prelim]" value="{{ $grade->prelim ?? '' }}" min="1" max="5" class="form-control">
                                </td>
                                <td>
                                    <input type="number" step="0.1" name="students[{{ $student->id }}][midterm]" value="{{ $grade->midterm ?? '' }}" min="1" max="5" class="form-control">
                                </td>
                                <td>
                                    <input type="number" step="0.1" name="students[{{ $student->id }}][prefinal]" value="{{ $grade->prefinal ?? '' }}" min="1" max="5" class="form-control">
                                </td>
                                <td>
                                    <input type="number" step="0.1" name="students[{{ $student->id }}][final]" value="{{ $grade->final ?? '' }}" min="1" max="5" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $student->id }}][remarks]" class="form-control" value="{{ $grade->remarks ?? '' }}" readonly>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary mt-3">Save Grades</button>
                </form>       

            </div>
        </div>
    </div>

    <!-- Start Modal -->
    <div class="modal fade" id="importGradeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="response">
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <form action="#" class="dropzone needsclick" id="dropzone-multi">
                    <div class="dz-message needsclick">
                        Drop files here or click to upload
                        <span class="note needsclick">(This is just a demo dropzone. Selected files are <span class="fw-medium">not</span> actually uploaded.)</span>
                    </div>
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-portal" id="importBtn" disabled>Upload</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</main>

<script>
   $(document).ready(function () {
        var table = $('#gradesTable').DataTable({
            lengthChange: true, // Disable show entries
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
                        extend: "excel",
                        text: '<i class="bx bx-spreadsheet me-2"></i>Excel',
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
                        extend: "pdf",
                        text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
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
                ],
            },
            {
                text: '<i class="bx bxs-file-import me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Import</span>',
                className: "add-new btn btn-sm btn-portal",
                attr: { "data-bs-toggle": "modal", "data-bs-target": "#importGradeModal" },
            },
        ],
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
                    // Check if the value contains HTML, and if so, extract the text content and remove leading/trailing spaces
                    if (d.indexOf('>') !== -1) {
                        d = $(d).text().trim();
                    }
                    console.log("Appending option:", d); // Log the option being appended
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });
        },
        language: {
            lengthMenu: '_MENU_',
            //info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            search: "", 
            searchPlaceholder: "Search.." 
        }
        
    });

    table.buttons().container()
        .appendTo($('.dataTables_filter', table.table().container()));
});
</script>
<script>
var validFiles = []; 

$(document).ready(function () {

    var dropzoneTemplate = `
  <div class="dz-preview dz-file-preview">
    <div class="dz-details">
      <div class="dz-thumbnail">
        <img data-dz-thumbnail>
        <span class="dz-nopreview">No preview</span>
        <div class="dz-success-mark"></div>
        <div class="dz-error-mark"></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
        </div>
      </div>
      <div class="dz-filename" data-dz-name></div>
      <div class "dz-size" data-dz-size></div>
    </div>
  </div>`;

  var dropzoneMulti = document.querySelector("#dropzone-multi");
  if (dropzoneMulti) {
    new Dropzone(dropzoneMulti, {
      previewTemplate: dropzoneTemplate,
      parallelUploads: 1,
      maxFilesize: 5,
      addRemoveLinks: true,

      init: function() {
        var myDropzone = this;

        this.on("success", function(file, response) {
            checkFile(myDropzone, file);
        });

        // Import Button Click Event
        // $('#importBtn').click(function () {
        // var validFiles = myDropzone.getAcceptedFiles();
        //     processValidFiles(validFiles);
        // });
        $('#importBtn').click(function () {
            if (hasValidFiles()) {
                processValidFiles(validFiles);
            } else {
                createToast("Error", "No valid files to upload.", "danger");
            }
        });

        this.on("removedfile", function (file) {
            validFiles = validFiles.filter(validFile => validFile !== file);

            toggleUploadButton();
        });

      }

    });
  }
});

function toggleUploadButton() {
    var importBtn = document.getElementById('importBtn');
    if (hasValidFiles()) {
        importBtn.removeAttribute('disabled');
    } else {
        importBtn.setAttribute('disabled', 'disabled');
    }
}

function hasValidFiles() {
    return validFiles.length > 0;
}



function processValidFiles(validFiles) {
    // Disable the button
    var importBtn = document.getElementById('importBtn');
    importBtn.setAttribute('disabled', 'disabled');

    // Create a spinner element
    var spinner = document.createElement('span');
    spinner.className = 'spinner-border spinner-border-sm me-1';
    spinner.setAttribute('role', 'status');
    spinner.setAttribute('aria-hidden', 'true');

    // Create the "Posting" text
    var postingText = document.createTextNode(' Uploading');

    // Clear the button content and append the spinner and text
    importBtn.innerHTML = '';
    importBtn.appendChild(spinner);
    importBtn.appendChild(postingText);

    if (validFiles.length === 0) {
        // No valid files to upload
        // Restore the button to its original state
        resetButton();
        return;
    }

    // Create a new FormData object to store the valid files
    var formData = new FormData();

    // Append each valid file to the FormData object
    validFiles.forEach(function (file) {
        formData.append('files[]', file, file.name);
    });

    // Get the CSRF token from the meta tag in your Blade layout
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Define headers with the CSRF token
    var headers = new Headers({
        'X-CSRF-TOKEN': csrfToken,
    });

    // Create a request object with the headers
    var request = new Request('{{ route('teacher.grades.import') }}', {
        method: 'POST',
        body: formData,
        headers: headers,
    });

    // Send the request to upload the valid files
    fetch(request)
        .then(response => response.json())
        .then(data => {
            // Remove the loading spinner and restore the button to its original state
            resetButton();
            
            if (data.result) {
                // Display success marks for each valid file
                validFiles.forEach(function (file) {
                    var filePreview = file.previewElement;
                    if (filePreview) {
                        filePreview.querySelector(".dz-success-mark").style.display = "block";
                    }
                });

                createToast("Success", data.message, "success");
            } else {
                createToast("Danger", data.message, "danger");
            }
        })
        .catch(error => {
            // Handle any errors that occur during the upload
            createToast("Danger", error.message, "danger");
            // Restore the button to its original state
            resetButton();
        });
}

function resetButton() {
    var importBtn = document.getElementById('importBtn');
    importBtn.removeAttribute('disabled');
    importBtn.innerHTML = 'Upload';
}


</script>

@endsection
