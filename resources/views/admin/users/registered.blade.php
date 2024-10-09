@extends('layouts.app')

@section('title', 'Registered')

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
                            <div class="col-md-4 user_year"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <!-- Table with stripped rows -->
                            <table id="registered" class="table datatable">
                                <thead>
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('profile.show', ['username' => $item->username]) }}" class="text-portal">
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-3">
                                                        <img src="{{ asset('img/profile/' . $item->avatar) }}" alt="Avatar" class="rounded-circle profile-sm" style="max-height: 40px;" >
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="app-user-view-account.html" class="text-body text-truncate">
                                                        <span class="fw-semibold">{{ $item->getFullname() }}</span>
                                                    </a>
                                                    <small class="text-muted">{{ '@'.$item->username }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center">
                                            <span class="badge badge-center me-2">
                                            <div class="btn btn-sm @if ($item->type === 'student') bg-primary-subtle @else bg-success-subtle @endif rounded-circle">
                                                    @if ($item->type === 'student')
                                                        <i class="bx bxs-user"></i>
                                                    @else
                                                        <i class="bx bxs-briefcase"></i>
                                                    @endif
                                                </div>
                                            </span>
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $item->email }}
                                    </td>
                                    <td>
                                        @if($item->type === 'student')
                                            {{ $item->student->Course }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->type === 'student')
                                            {{ $item->student->YearLevel }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td><h6><span class="badge {{ $item->status === 'member' ? 'bg-success' : ($item->status === 'unverified' ? 'bg-secondary' : 'bg-danger') }}">{{ $item->status }}</span></h6></td>

                                    <td>
                                        <a href="{{ route('profile.show', ['username' => $item->username]) }}">
                                            <button class="btn btn-sm" type="button">
                                                <i class="bx ri-edit-box-line"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('profile.show', ['username' => $item->username]) }}">
                                            <button class="btn btn-sm deregisterBtn" type="button">
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
<script>
   $(document).ready(function () {
    var table = $('#registered').DataTable({
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
