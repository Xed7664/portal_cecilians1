@extends('layouts.app')

@section('title', 'Students')

@section('content')
<main id="main" class="main">
    <section class="newsfeed-container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-3">Search Filter</h5>
                        <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                            <div class="col-md-4 user_program"></div>
                            <div class="col-md-4 user_status"></div>
                            <div class="col-md-4 user_gender"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <!-- Table with stripped rows -->
                        <table id="student" class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">School ID</th>
                                    <th scope="col">FullName</th>
                                    <th scope="col">Birth Date</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td>{{ $item->StudentID }}</td>
                                    <td>{{ $item->FullName }}</td>
                                    <td>{{ $item->Birthday }}</td>
                                    <td>{{ $item->Course }}</td>
                                    <td><span class="text-truncate d-flex align-items-center"><span class="badge badge-center me-2"><div class="btn btn-sm @if(strtolower($item->Gender) === 'male') bg-primary-subtle @else bg-danger-subtle @endif rounded-circle">@if(strtolower($item->Gender) === 'male')<i class="bx bx-male-sign"></i>@else<i class="bx bx-female-sign"></i>@endif</div></span>{{ ucfirst($item->Gender) }}</span></td>
                                    <td>
                                        <h6><span class="badge @if($item->isRegistered()) bg-success @else bg-secondary @endif">{{ $item->isRegistered() ? 'Registered' : 'Not Registered' }}</span></h6>
                                    </td>
                                    <td>
                                        <a href="#">
                                            <button class="btn" type="button">
                                                <i class="bx ri-edit-box-line"></i>
                                            </button>
                                        </a>
                                        <a href="#">
                                            <button class="btn" type="button">
                                                <i class="bi bi-eraser"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>
            </div>
            <!-- End Main Content -->
            
        </div>
    </section>

    <!-- Start Modal -->
    <div class="modal fade" id="importStudentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
    var table = $('#student').DataTable({
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
                attr: { "data-bs-toggle": "modal", "data-bs-target": "#importStudentModal" },
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

function checkFile(dropzone, file) {
    // Create a FormData object
    var formData = new FormData();
    formData.append('file', file);

    // Get the CSRF token from the meta tag in your Blade layout
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Define headers with the CSRF token
    var headers = new Headers({
        'X-CSRF-TOKEN': csrfToken,
    });

    // Create a request object with the headers
    var request = new Request('{{ route('admin.users.student.check') }}', {
        method: 'POST',
        body: formData,
        headers: headers,
    });

    // First
    var filePreview = file.previewElement;
    if (filePreview) {
        filePreview.querySelector(".dz-error-mark").style.display = "none"; // Display the error mark
        filePreview.querySelector(".dz-success-mark").style.display = "none"; // Display the error mark
    }

    // Send the request
    fetch(request)
        .then(response => response.json())
        .then(data => {
            if (data.result) {
                // File is valid, proceed with the upload
                //dropzone.processFile(file);
                //file.markedForProcessing = true;
                validFiles.push(file);
                file.accepted = true;
                
                toggleUploadButton();

            } else {
                // File is not valid, add an error mark and error message to the file preview
                var filePreview = file.previewElement;
                if (filePreview) {
                    filePreview.querySelector(".dz-error-mark").style.display = "block"; // Display the error mark
                    filePreview.querySelector("[data-dz-errormessage]").textContent = data.message; // Set the error message from the server
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            createToast("Error", error.message, "danger");
        });
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
    var request = new Request('{{ route('admin.users.student.upload') }}', {
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
