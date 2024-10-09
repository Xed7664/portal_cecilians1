$(document).ready(function () {
    // Auto-save grade inputs
    $(".grade-input").on("input", function () {
        var studentId = $(this).data("student-id");
        var formData = {
            student_id: studentId,
            prelim: $('input[name="grades[' + studentId + '][prelim]"]').val(),
            midterm: $(
                'input[name="grades[' + studentId + '][midterm]"]'
            ).val(),
            prefinal: $(
                'input[name="grades[' + studentId + '][prefinal]"]'
            ).val(),
            final: $('input[name="grades[' + studentId + '][final]"]').val(),
            _token: "{{ csrf_token() }}",
        };

        $.ajax({
            url: "{{ route('teacher.grades.autoSave') }}", // Define this route
            type: "POST",
            data: formData,
            success: function (response) {
                console.log(
                    "Grade auto-saved successfully for student " + studentId
                );
            },
            error: function () {
                console.error("Error auto-saving grades.");
            },
        });
    });

    // Submit individual student grades (Mark as Ready)
    $(".submit-student-grade").on("click", function () {
        var studentId = $(this).data("student-id");
        var formData = {
            student_id: studentId,
            status: "ready_for_submission",
            _token: "{{ csrf_token() }}",
        };

        $.ajax({
            url: "{{ route('teacher.grades.submitStudent', $subject->id) }}", // Make sure $subject->id is defined
            type: "POST",
            data: formData,
            success: function (response) {
                alert("Grades for student " + studentId + " marked as ready.");
                location.reload(); // Refresh to show updated status
            },
            error: function (xhr, status, error) {
                console.error(
                    "Error submitting grades for student " + studentId,
                    error
                );
                alert("Error submitting grades for student " + studentId);
            },
        });
    });

    // Show confirmation modal when "Submit All Grades" button is clicked
    $("#confirmSubmitBtn").on("click", function () {
        // Trigger form submission
        $("#gradesForm").submit();
    });
});

//--------------------------------------------------------------------

$(document).ready(function () {
    var table = $("#gradesTable").DataTable({
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
                                              void 0 !== t.classList &&
                                              t.classList.contains("user-name")
                                                  ? (s +=
                                                        t.lastChild.firstChild
                                                            .textContent)
                                                  : void 0 === t.innerText
                                                  ? (s += t.textContent)
                                                  : (s += t.innerText);
                                          }),
                                          s);
                                },
                            },
                        },
                        customize: function (e) {
                            $(e.document.body)
                                .css("color", s)
                                .css("border-color", t)
                                .css("background-color", a),
                                $(e.document.body)
                                    .find("table")
                                    .addClass("compact")
                                    .css("color", "inherit")
                                    .css("border-color", "inherit")
                                    .css("background-color", "inherit");
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
                                              void 0 !== t.classList &&
                                              t.classList.contains("user-name")
                                                  ? (s +=
                                                        t.lastChild.firstChild
                                                            .textContent)
                                                  : void 0 === t.innerText
                                                  ? (s += t.textContent)
                                                  : (s += t.innerText);
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
                                              void 0 !== t.classList &&
                                              t.classList.contains("user-name")
                                                  ? (s +=
                                                        t.lastChild.firstChild
                                                            .textContent)
                                                  : void 0 === t.innerText
                                                  ? (s += t.textContent)
                                                  : (s += t.innerText);
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
                                              void 0 !== t.classList &&
                                              t.classList.contains("user-name")
                                                  ? (s +=
                                                        t.lastChild.firstChild
                                                            .textContent)
                                                  : void 0 === t.innerText
                                                  ? (s += t.textContent)
                                                  : (s += t.innerText);
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
                                              void 0 !== t.classList &&
                                              t.classList.contains("user-name")
                                                  ? (s +=
                                                        t.lastChild.firstChild
                                                            .textContent)
                                                  : void 0 === t.innerText
                                                  ? (s += t.textContent)
                                                  : (s += t.innerText);
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
                            window.location.href =
                                "{{ route('teacher.grades.template', ['subjectEnrolled' => $subjectEnrolled->id]) }}";
                        },
                    },
                ],
            },
            {
                text: '<i class="bx bxs-file-import me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Import</span>',
                className: "add-new btn btn-sm btn-portal",
                attr: {
                    "data-bs-toggle": "modal",
                    "data-bs-target": "#importGradeModal",
                },
            },
        ],
        initComplete: function () {
            console.log("Init complete");
            this.api()
                .columns([3, 4, 5])
                .every(function (colIdx) {
                    var column = this;
                    var select = $(
                        '<select class="form-select"><option value="">Select ' +
                            column.header().innerHTML +
                            "</option>"
                    )
                        .appendTo(
                            $(
                                ".user_" +
                                    column.header().innerHTML.toLowerCase()
                            )
                        )
                        .on("change", function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? "^" + val + "$" : "", true, false)
                                .draw();
                        });

                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d) {
                            if (d.indexOf(">") !== -1) {
                                d = $(d).text().trim();
                            }
                            select.append(
                                '<option value="' + d + '">' + d + "</option>"
                            );
                        });
                });
        },
        language: {
            lengthMenu: "_MENU_",
            search: "",
            searchPlaceholder: "Search..",
        },
    });

    table
        .buttons()
        .container()
        .appendTo($(".dataTables_filter", table.table().container()));
});

//------------------------------------------------------------------------//
$(document).ready(function () {
    // Custom file upload UI
    $("#gradesFile").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $("#fileLabel").text(fileName || "Select or Drop File Here");
    });

    // Toast notification function
    function showToast(title, message, success = true) {
        const toastElement = new bootstrap.Toast(
            document.getElementById("toastMessage")
        );
        $("#toastTitle").text(title);
        $("#toastBody").text(message);
        $("#toastMessage")
            .removeClass("bg-success bg-danger")
            .addClass(success ? "bg-success" : "bg-danger");
        toastElement.show();
    }

    // Handle file upload via AJAX
    $("#submitFileBtn").on("click", function (e) {
        e.preventDefault();

        var formData = new FormData();
        var fileInput = document.getElementById("gradesFile");
        var file = fileInput.files[0];

        if (!file) {
            showToast("Error", "Please select a valid file.", false);
            return;
        }

        // Append file to FormData
        formData.append("file", file);
        formData.append("_token", "{{ csrf_token() }}");

        // Show progress bar
        $("#progressWrapper").show();
        $("#uploadProgress").css("width", "0%").attr("aria-valuenow", 0);

        // Make AJAX request
        $.ajax({
            url: "{{ route('teacher.grades.import') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener(
                    "progress",
                    function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round(
                                (evt.loaded / evt.total) * 100
                            );
                            $("#uploadProgress")
                                .css("width", percentComplete + "%")
                                .attr("aria-valuenow", percentComplete);
                        }
                    },
                    false
                );
                return xhr;
            },
            success: function (response) {
                $("#uploadProgress")
                    .removeClass("progress-bar-striped progress-bar-animated")
                    .addClass("bg-success");
                $("#uploadProgress").html(
                    '<i class="bx bx-check-circle"></i> File Uploaded Successfully!'
                );

                // Show success message
                showToast("Success", "Grades file imported successfully!");

                // Reset file input
                $("#gradesFile").val("");
                $("#fileLabel").text("Select or Drop File Here");

                // Close modal after short delay
                setTimeout(() => {
                    $("#importGradeModal").modal("hide");
                }, 1500);

                // Reload the grade table
                $("#gradesTable").load(location.href + " #gradesTable");
            },
            error: function (response) {
                $("#uploadProgress").addClass("bg-danger");
                $("#uploadProgress").html(
                    '<i class="bx bx-x-circle"></i> Upload Failed'
                );
                showToast(
                    "Error",
                    "An error occurred during the upload.",
                    false
                );
            },
        });
    });
});
