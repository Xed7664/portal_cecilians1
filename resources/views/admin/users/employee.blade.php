@extends('layouts.app')

@section('title', 'Employee')

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
                            <div class="col-md-4 user_status"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <!-- Table with stripped rows -->
                            <table id="employees" class="table datatable">
                                <thead>
                                <tr>
                                    <th scope="col">School ID</th>
                                    <th scope="col">FullName</th>
                                    <th scope="col">Birth Date</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td>{{ $item->EmployeeID }}</td>
                                    <td>{{ $item->FullName }}</td>
                                    <td>{{ $item->Birthday }}</td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center">
                                            <span class="badge badge-center me-2">
                                            <div class="btn btn-sm @if (strtolower($item->Gender) === 'male') bg-primary-subtle @else bg-danger-subtle @endif rounded-circle">
                                                @if (strtolower($item->Gender) === 'male')
                                                    <i class="bx bx-male-sign"></i>
                                                @else
                                                    <i class="bx bx-female-sign"></i>
                                                @endif
                                                </div>
                                            </span>
                                            {{ ucfirst($item->Gender) }}
                                        </span>
                                    </td>
                                    <td>
                                        <h6><span class="badge @if ($item->isRegistered()) bg-success @else bg-secondary @endif">{{ $item->isRegistered() ? 'Registered' : 'Not Registered' }}</span></h6>
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
</main>


<!-- Modal for Import -->
<div class="modal fade" id="importStudentModal" tabindex="-1" aria-labelledby="importStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importStudentModalLabel">Import Employees</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dropzone Form for File Upload -->
                <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="importDropzone">
                    @csrf
                    <div class="dz-message text-center">
                        <h4>Drag & Drop Files Here</h4>
                        <p class="text-muted">or click to select a file</p>
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>

<!-- Custom CSS for Dropzone and Modal -->
<style>
    /* Customize Dropzone styling */
    #importDropzone {
        border: 2px dashed #007bff;
        border-radius: 10px;
        background-color: #f8f9fa;
        min-height: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .dz-message {
        font-size: 18px;
        color: #007bff;
    }

    .dz-message h4 {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }

    .dz-message p {
        font-size: 14px;
        color: #6c757d;
    }

    /* Modal Customization */
    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        border-bottom: 1px solid #ddd;
        background-color: #007bff;
    }

    .modal-footer {
        border-top: 1px solid #ddd;
    }

    .btn-close {
        background-color: transparent;
        border: none;
        font-size: 1.5rem;
        color: #fff;
    }

    .btn-close:hover {
        color: #ccc;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>


<script>
   Dropzone.autoDiscover = false;

$(document).ready(function () {


   function showToast(message, type) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: "top", // top or bottom
        position: "right", // left, center or right
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
                showToast(response.message, 'success'); // Show success toast
                setTimeout(function () {
                    $('#importStudentModal').modal('hide'); // Close modal
                    location.reload(); // Reload after showing toast
                }, 2000); // Delay reloading for the toast to display
            } else {
                showToast("Error: " + response.message, 'error'); // Show error toast
            }
        });
        this.on("error", function (file, response) {
            console.error(response); // Log error details
            if (typeof response === "object" && response.message) {
                showToast("Error: " + response.message, 'error'); // Show error toast
            } else {
                showToast("Error uploading file: " + response, 'error'); // Show generic error toast
            }
        });
    }
});




    var table = $('#employees').DataTable({
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
@endsection
