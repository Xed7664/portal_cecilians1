(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        var responseElement = document.getElementById("response");
        $('#importButton').click(function () {
            var fileInput = $('#fileInput');
            var file = fileInput.prop('files')[0];
            var inputValue = $('#operator').val(); // Get the value of the input field with id "test"

            // Disable the file input
            $('#fileInput').prop('disabled', true);
            $('#cancelButton').prop('disabled', true);
            $('#closeButton').prop('disabled', true);

            // Show the spinner
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Import');

            if (file) {
                var formData = new FormData();
                formData.append('xlsFile', file);

                $.ajax({
                    url: '/ajax/import/' + encodeURIComponent(inputValue), // Append the input value to the URL
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                            .text(response.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                        $('#importModal #response').empty().append(alertDiv);
                    },
                    error: function (xhr, status, error) {
                        var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                            .text(xhr.responseJSON.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                        $('#importModal #response').empty().append(alertDiv);
                    },
                    complete: function () {
                        $('#fileInput').prop('disabled', false);
                        $('#cancelButton').prop('disabled', false);
                        $('#closeButton').prop('disabled', false);
                        $('#importButton').html('Import');
                    }
                });
            } else {
                var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                    .text("Cannot process an empty file.")
                    .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                $('#importModal #response').empty().append(alertDiv);

                $('#fileInput').prop('disabled', false);
                $('#cancelButton').prop('disabled', false);
                $('#closeButton').prop('disabled', false);
                $('#importButton').html('Import');
            }
        });

        $(document).on('click', '.resendEmail', function () {
            // Get the data-id value from the button's data attribute
            var dataId = $(this).data('id');
        
            // Disable the button to prevent redundancy
            $(this).prop('disabled', true);
        
            // Send the AJAX request
            $.ajax({
                url: './../ajax/admin/user_resend',
                method: 'POST', // or 'GET' depending on your server-side implementation
                data: { id: dataId },
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
        
                    $('#response').empty().append(alertDiv);
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
        
                    $('#response').empty().append(alertDiv);
                },
                complete: function () {
                    responseElement.scrollIntoView({ behavior: "smooth" });
                }
            });
        });

        $('.deregisterBtn').on('click', function () {
            const id = $(this).data('id');
            const fullName = $(this).data('name');
            $('#full-name-placeholder').text(fullName);
            $('#confirmDeleteBtn').data('id', id);
        });

        $('.updateProfileBtn').click(function () {
            var id = $(this).data('id');
            var fullName = $(this).data('name');
            $('#full-name-placeholder').text(fullName);
            $('#confirmUpdateProfileBtn').attr('data-id', id);
        });

        $('#addFirstPage #searchQuery').click(function () {
            var inputValue = $('#addFirstPage #query').val();

            // Send the AJAX request
            $.ajax({
                url: './../ajax/admin/user_query',
                method: 'POST',
                data: { query: inputValue },
                success: function (response) {
                    $('#addFirstPage #s_id').val(response.student_id).attr('class', 'form-control valid');
                    $('#addFirstPage #f_name').val(response.student_fname).attr('class', 'form-control valid');

                    $('#addModal #nextBtn').prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    $('#addFirstPage #s_id').val('Cannot be found.').attr('class', 'form-control');
                    $('#addFirstPage #f_name').val('Cannot be found.').attr('class', 'form-control');

                    $('#addModal #nextBtn').prop('disabled', true);
                    $('#addModal #nextBtn').css('display', 'block');
                    $('#addModal #submitBtn').css('display', 'none');
                },
                complete: function () {
                    responseElement.scrollIntoView({ behavior: "smooth" });
                }
            });

        });

        $('#addBtnExecuter').click(function () {
            // Switch Page
            $('#addModal #addFirstPage').css('display', 'block');
            $('#addModal #addSecondPage').css('display', 'none');

            // Buttons
            $('#addModal #cancelButton').css('display', 'block');
            $('#addModal #prevBtn').css('display', 'none');
            $('#addModal #nextBtn').css('display', 'block');
            $('#addModal #submitBtn').css('display', 'none');
        });

        $('#addModal #submitBtn').click(function () {
            var studentID = $('#addFirstPage #s_id').val();
            var fullName = $('#addFirstPage #f_name').val();
            var username = $('#addSecondPage #u_name').val();
            var email = $('#addSecondPage #e_mail').val();
            var password = $('#addSecondPage #u_pass').val();
            var type = $('#addSecondPage #u_type').val();

            $.ajax({
                url: './../ajax/admin/user_create',
                method: 'POST',
                data: {
                    std_id: studentID,
                    f_name: fullName,
                    username: username,
                    email: email,
                    password: password,
                    type: type
                },
                success: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(xhr.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    $('#addModal').modal('hide');
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#addModal #response').empty().append(alertDiv);
                },
                complete: function () {
                    responseElement.scrollIntoView({ behavior: "smooth" });
                }
            });
        });


        $('#addModal #nextBtn').click(function () {
            var isSIdValid = $('#addFirstPage #s_id').hasClass('valid');
            var isFNameValid = $('#addFirstPage #f_name').hasClass('valid');

            if (isSIdValid && isFNameValid) {
                // Switch Page
                $('#addModal #addFirstPage').css('display', 'none');
                $('#addModal #addSecondPage').css('display', 'block');

                // Buttons
                $('#addModal #cancelButton').css('display', 'none');
                $('#addModal #prevBtn').css('display', 'block');
                $('#addModal #nextBtn').css('display', 'none');
                $('#addModal #submitBtn').css('display', 'block');

            } else {
                $('#addModal #nextBtn').prop('disabled', true);
            }

        });

        $('#addModal #prevBtn').click(function () {

            // Switch Page
            $('#addModal #addFirstPage').css('display', 'block');
            $('#addModal #addSecondPage').css('display', 'none');

            // Buttons
            $('#addModal #nextBtn').css('display', 'block');
            $('#addModal #cancelButton').css('display', 'block');
            $('#addModal #prevBtn').css('display', 'none');
            $('#addModal #submitBtn').css('display', 'none');

        });

        $('#confirmationModal #confirmDeleteBtn').click(function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/./../ajax/admin/user_delete',
                type: 'POST',
                data: { 'user_id': id },
                success: function (response) {
                    if (response.result) {
                        createToast("Done", response.message, "success");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        createToast("Done", response.message, "warning");
                    }
                },
                error: function (response) {
                    // Handle error response
                    createToast("Error", response.responseJSON.message, "danger");
                }
            });
        });

        $('#confirmationModal #confirmDeleteStudentBtn').click(function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/./../ajax/admin/student_delete',
                type: 'POST',
                data: { 'student_id': id },
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    setTimeout(function () {
                        location.reload();
                    }, 5000);
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                },
                complete: function () {
                    $('#confirmation-modal').modal('hide');
                    responseElement.scrollIntoView({ behavior: "smooth" });
                }
            });
        });

        $('.editBtn').click(function () {
            var id = $(this).data('id');
            var row = $(this).closest('tr');
            var rowData = row.find('td').map(function () {
                return $(this).text();
            }).get();

            // Populate the editModal with the data from the selected row
            $('#editModal #std_id').val(rowData[0]);
            $('#editModal #f_name').val(rowData[1]);
            $('#editModal #u_name').val(rowData[2]);
            $('#editModal #e_mail').val(rowData[3]);
            $('#editModal #u_type').val(rowData[4]);

            $('#saveBtn').attr('data-id', id);
        });

        $('#saveBtn').click(function () {
            var id = $(this).data('id'); // Get the data-id value from the button's data attribute

            // Retrieve the updated values from the form
            var username = $('#u_name').val();
            var userEmail = $('#e_mail').val();
            var userType = $('#u_type').val();

            // Create an object with the updated data
            var updatedData = {
                id: id,
                username: username,
                email: userEmail,
                type: userType
            };

            // Send the AJAX request to update the user
            $.ajax({
                url: '/./../ajax/admin/user_edit',
                type: 'POST',
                data: updatedData,
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                },
                complete: function () {
                    responseElement.scrollIntoView({ behavior: "smooth" });
                }
            });
        });

        
        // Support/Ticket Page
        function sendMessage() {
            var message = $('#messageContent').val();
            var ticketID = $('#ticket_id').val();
            var HTMLSender = $('#HTMLSender').val();
            var timestamp = new Date().toLocaleString();

            
            // Send AJAX request to support.php
            $.ajax({
                url: './.././../ajax/admin/send_reply',
                method: 'POST',
                data: {
                    ticket_id: ticketID,
                    message: message
                },
                success: function (response) {
                    // Create the message HTML
                    var messageHtml = '<div class="row mt-2">' +
                        '<div class="col-lg-4"></div>' +
                        '<div class="col-lg-8 col-sm-12">' +
                        '<div class="alert alert-info alert-dismissible fade show text-dark" role="alert">' +
                        '<small><p class="mb-0 text-secondary">' + HTMLSender + '</p></small>' +
                        '<hr>' +
                        message +
                        '<small><p class="mb-0 text-end text-secondary">Just now</p></small>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    // Append the message to the messages container
                    $('#messages').append(messageHtml);

                    var messagesContainer = $('#messages');
                    var scrollTo = messagesContainer.prop('scrollHeight') - messagesContainer.height();
                    messagesContainer.scrollTop(scrollTo);
                },
                error: function (xhr, status, error) {
                    // Create the message HTML
                    var messageHtml = '<div class="row mt-2">' +
                        '<div class="col-lg-4"></div>' +
                        '<div class="col-lg-8 col-sm-12">' +
                        '<div class="alert alert-danger alert-dismissible fade show text-secondary" role="alert">' +
                        '<small><p class="mb-0 text-secondary">' + HTMLSender + '</p></small>' +
                        '<hr>' +
                        xhr.responseJSON.message +
                        '<small><p class="mb-0 text-end text-secondary">Just now</p></small>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    // Append the message to the messages container
                    $('#messages').append(messageHtml);

                    var messagesContainer = $('#messages');
                    var scrollTo = messagesContainer.prop('scrollHeight') - messagesContainer.height();
                    messagesContainer.scrollTop(scrollTo);
                }
            });

            $('#messageContent').val('');
            $('#messageContent').focus();
        }

        // Call the sendMessage function when the send button is clicked
        $('#ticketReply #sendMessageBtn').click(function () {
            sendMessage();
        });

        // Call the sendMessage function when Enter key is pressed
        $('#messageContent').keypress(function (event) {
            if (event.which === 13) { // 13 is the keycode for Enter
                event.preventDefault(); // Prevent the default Enter key behavior
                sendMessage();
            }
        });

        if ($('section.ticket_view').length > 0) {
            // Scroll down to the element with the ID "messages"
            var messagesContainer = $('#messages');
            var scrollTo = messagesContainer.prop('scrollHeight') - messagesContainer.height();
            messagesContainer.scrollTop(scrollTo);
        }

        // Adding note
        $('#addNote').click(function () {
            // Get the note text from the textarea
            var note = $('#floatingTextarea').val();
            var ticketID = $('#ticket_id').val();

            // Create the data object to send via AJAX
            var data = {
                note: note,
                ticketID: ticketID
            };

            // Send the AJAX request
            $.ajax({
                url: '.././../ajax/admin/support_add_note',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(xhr.responseJSON.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                }
            });
        });

        $('.remove-note').click(function () {
            // Get the data-id attribute value of the clicked button
            var noteId = $(this).data('id');
            var noteDiv = $(this).closest('.mb-2');

            // Create the data object to send via AJAX
            var data = {
                noteId: noteId
            };

            // Send the AJAX request
            $.ajax({
                url: '.././../ajax/admin/support_delete_note',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    // Handle the success response here
                    console.log(response); // You can log the response or perform any other action

                    noteDiv.remove();
                },
                error: function (xhr, status, error) {
                    // Handle the error response here
                    console.error(error);
                }
            });
        });

        $('#status-saveBtn').click(function () {
            var selectedStatus = $('#u_type').val();
            var ticketID = $('#ticket_id').val();

            $.ajax({
                url: '.././../ajax/admin/support_update_status',
                type: 'POST',
                data: {
                    ticketID: ticketID,
                    status: selectedStatus
                },
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(xhr.responseJSON.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                }
            });
        });

        $('#supportMessageCooldown').on('input', function () {
            var value = $(this).val();
            $('#supportMessageCooldownLb').html('Value: ' + value + ' (Minutes)   <small class="text-danger">Set 0 for unlimited. (Not recommended)</small>');
        });

        // Admin Calendar
        if ($('.admin-calendar').length) {
            var calendarEl = document.getElementById('calendar');
            var eventClass = null;
            var calendar = new FullCalendar.Calendar(calendarEl, {
                expandRows: true,
                slotMinTime: '08:00',
                slotMaxTime: '20:00',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialView: 'dayGridMonth',
                initialDate: '2023-05-10',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                selectable: true,
                selectMirror: true,
                select: function (arg) {
                    var modal = $('#eventModal');
                    modal.find('#titleInput').val('');
                    modal.find('#startDateInput').val(arg.startStr);
                    modal.find('#endDateInput').val(arg.endStr);
                    modal.modal('show');

                    //modal.find('#addEventBtn').off('click').on('click', function () {
                    //    var title = modal.find('#titleInput').val();
                    //    if (title) {
                    //        calendar.addEvent({
                    //            title: title,
                    //            start: arg.start,
                    //            end: arg.end,
                    //            allDay: arg.allDay
                    //        });
                    //        modal.modal('hide');
                    //    }
                    //});

                    calendar.unselect();
                },
                eventClick: function (arg) {
                    var modal = $('#eventModal');
                    modal.find('#eventModalSize #titleInput').val(arg.event.title);
                    modal.find('#eventModalSize #locationInput').val(arg.event.location);
                    modal.find('#eventModalSize #startDateInput').val(arg.event.startStr);
                    modal.find('#eventModalSize #endDateInput').val(arg.event.endStr);

                    // Display additional fields and data
                    modal.find('#eventModalSize #permissionInput').val(arg.event.permission);
                    modal.find('#eventModalSize #accessInput').val(arg.event.access);
                    // Add more fields as needed for other data properties

                    modal.modal('show');
                },
                nowIndicator: true,
                dayMaxEvents: true, // allow "more" link when too many events
                events: function (fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/ajax/calendar/get_events',
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (response.code === 10000) {
                                var rawEvents = response.data;
                                if (Array.isArray(rawEvents)) {
                                    var events = rawEvents.map(function (event) {
                                        switch (event.EventType) {
                                            case 'holiday':
                                            case 'suspension':
                                                eventClass = 'd-grid badge bg-danger';
                                                break;

                                            case 'exam':
                                                eventClass = 'd-grid badge bg-warning';
                                                break;

                                            case 'mass':
                                                eventClass = 'd-grid badge bg-info';
                                                break;

                                            default:
                                                eventClass = 'd-grid badge bg-primary';
                                                break;
                                        }

                                        return {
                                            id: event.ID,
                                            title: event.Title,
                                            description: event.Description,
                                            start: event.StartDate,
                                            end: event.EndDate,
                                            location: event.Location,
                                            type: event.EventType,
                                            classNames: eventClass
                                        };
                                    });
                                    successCallback(events);
                                }
                            } else {
                                failureCallback(response.message);
                            }
                        },
                        error: function () {
                            failureCallback('Error fetching events');
                        }
                    });
                },
                eventContent: function (arg) {
                    return {
                        html: '<div class="event-title">' + arg.event.title + '</div>'
                    };
                }
            });

            calendar.render();

            $('#eventModal #allDayInput').change(function () {
                if ($(this).is(':checked')) {
                    $('#eventModal #endDateForm').hide();
                } else {
                    $('#eventModal #endDateForm').show();
                }
            });

            $('#advancedSettings').click(function () {
                var isOpen = $('#advancedSettings').data('open');

                if (isOpen === 0) {
                    $('#advancedSettings').data('open', 1);
                    $('#eventModal #simpleOption').attr('class', 'col-lg-6');
                    $('eventModal #simpleOption').css('display', 'block');

                    $('#eventModal #advancedOption').attr('class', 'col-lg-6');
                    $('#eventModal #advancedOption').css('display', 'block');

                    $('#eventModal #eventModalSize').attr('class', 'modal-dialog modal-dialog-centered modal-xl');

                    $('#eventModal #advancedSettings').text('Simple Settings');

                    $('#bsit, #bsba, #bsed, #beed, #bshm, #bstm, #bscrim').prop('checked', true);

                } else {
                    $('#advancedSettings').data('open', 0);
                    $('#eventModal #simpleOption').attr('class', 'col-lg-12');
                    $('eventModal #simpleOption').css('display', 'block');

                    $('#eventModal #advancedOption').attr('class', 'col-lg-6');
                    $('#eventModal #advancedOption').css('display', 'none');

                    $('#eventModal #eventModalSize').attr('class', 'modal-dialog modal-dialog-centered');

                    $('#eventModal #advancedSettings').text('Advanced Settings');
                }
            });

            $('#eventModal #eventModalSize #addEventBtn').click(function () {
                var modal = $('#eventModal');

                // Retrieve the updated values from the form
                var eventName = $('#titleInput').val();
                var eventType = $('#typeInput').val();
                var eventLocation = $('#locationInput').val();
                var eventStartDate = $('#startDateInput').val();
                var eventEndDate = $('#endDateInput').val();
                var eventStartTime = $('#startTimeInput').val();
                var eventEndTime = $('#endTimeInput').val();
                var eventAllDay = $('#allDayInput').is(':checked');
                var eventNoClass = $('#noClassInput').is(':checked');

                var permBSIT = $('#bsitInput').is(':checked');
                var permBSBA = $('#bsbaInput').is(':checked');
                var permBSED = $('#bsedInput').is(':checked');
                var permBEED = $('#beedInput').is(':checked');
                var permBSHM = $('#bshmInput').is(':checked');
                var permBSTM = $('#bstmInput').is(':checked');
                var permBSCRIM = $('#bscrimInput').is(':checked');

                //var permEditMod = $('#modEdit').is(':checked');
                //var permEditTeach = $('#teachEdit').is(':checked');
                //var permEditOfficer = $('#officerEdit').is(':checked');
                //
                //var permDeleteMod = $('#modDelete').is(':checked');
                //var permDeleteTeach = $('#teachDelete').is(':checked');
                //var permDeleteOfficer = $('#officerDelete').is(':checked');

                // Check if title is empty
                if (eventName === '') {
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text('Title cannot be empty.')
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
                    $('#titleInput').focus();
                    $('#eventModal #eventModalSize #response').empty().append(alertDiv);
                    return;
                }

                // Create an object with the updated data
                var updatedData = {
                    name: eventName,
                    type: eventType,
                    start: {
                        date: eventStartDate,
                        time: eventStartTime
                    },
                    end: {
                        date: eventEndDate,
                        time: eventEndTime
                    },
                    location: eventLocation,
                    //allDay: eventAllDay,
                    //noClass: eventNoClass,
                    //permissions: {
                    //    edit: {
                    //        moderator: permEditMod,
                    //        teacher: permEditTeach,
                    //        officer: permEditOfficer
                    //    },
                    //    delete: {
                    //        moderator: permDeleteMod,
                    //        teacher: permDeleteTeach,
                    //        officer: permDeleteOfficer
                    //    }
                    //},
                    access: {
                        bsit: permBSIT,
                        bsba: permBSBA,
                        bsed: permBSED,
                        beed: permBEED,
                        bshm: permBSHM,
                        bstm: permBSTM,
                        bscrim: permBSCRIM
                    }
                };

                // Send the AJAX request to update the event
                $.ajax({
                    url: './.././../ajax/admin/add_event',
                    type: 'POST',
                    data: updatedData,
                    success: function (response) {
                        modal.modal('hide');

                        var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                            .text(response.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                        $('#response').empty().append(alertDiv);

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    },
                    error: function (xhr, status, error) {
                        var response = xhr.responseJSON;
                        var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                            .text(response.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                        $('#response').empty().append(alertDiv);
                    },
                    complete: function () {
                        responseElement.scrollIntoView({ behavior: "smooth" });
                    }
                });

            });

            // Event Name
            $('#titleInput').on('input', function () {
                var newTitle = $(this).val();
                $('.preview #name').text(newTitle);
            });

            $('#beforePrev').on('click', function () {
                var badgeText = '';
                var badgeClass = '';

                badgeText = 'Later';
                badgeClass = 'badge bg-info';

                $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);
            });

            $('#todayPrev').on('click', function () {
                var badgeText = '';
                var badgeClass = '';

                badgeText = 'Ongoing';
                badgeClass = 'badge bg-success';

                $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);
            });

            $('#afterPrev').on('click', function () {
                var badgeText = '';
                var badgeClass = '';

                badgeText = 'Event Ended';
                badgeClass = 'badge bg-danger';

                $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);
            });

            // Event Location
            $('#locationInput').on('input', function () {
                var newLoc = $(this).val();
                if (newLoc.length <= 0) {
                    $('.preview #location').text('');
                } else {
                    $('.preview #location').text('- (' + newLoc + ')');
                }
            });

            $('#typeInput').on('change', function () {
                var eventType = $(this).val();
                var badgeText = '';
                var badgeClass = '';
                var imagePath = '';

                switch (eventType) {
                    case 'general': 
                        badgeText = 'Ongoing';
                        badgeClass = 'badge bg-success';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', false);
                        $('#todayPrev').prop('disabled', false);
                        $('#afterPrev').prop('disabled', false);

                        $('#todayPrev').addClass('active');
                        break;
                    case 'seminar':
                        badgeText = 'Ongoing';
                        badgeClass = 'badge bg-success';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', false);
                        $('#todayPrev').prop('disabled', false);
                        $('#afterPrev').prop('disabled', false);

                        $('#todayPrev').addClass('active');
                        break;
                    case 'exam':
                        badgeText = 'Ongoing';
                        badgeClass = 'badge bg-success';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', false);
                        $('#todayPrev').prop('disabled', false);
                        $('#afterPrev').prop('disabled', false);

                        $('#todayPrev').addClass('active');
                        break;
                    case 'holiday':
                        badgeText = 'No Class';
                        badgeClass = 'badge bg-danger';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', true);
                        $('#todayPrev').prop('disabled', true);
                        $('#afterPrev').prop('disabled', true);
                        break;
                    case 'mass':
                        badgeText = 'Ongoing';
                        badgeClass = 'badge bg-success';
                        imagePath = './../../assets/img/calendar/mass.jpg';

                        $('#beforePrev').prop('disabled', false);
                        $('#todayPrev').prop('disabled', false);
                        $('#afterPrev').prop('disabled', false);

                        $('#todayPrev').addClass('active');
                        break;
                    case 'suspension':
                        badgeText = 'No Class';
                        badgeClass = 'badge bg-danger';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', true);
                        $('#todayPrev').prop('disabled', true);
                        $('#afterPrev').prop('disabled', true);
                        break;
                    case 'meeting':
                        badgeText = 'Ongoing';
                        badgeClass = 'badge bg-success';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', false);
                        $('#todayPrev').prop('disabled', false);
                        $('#afterPrev').prop('disabled', false);

                        $('#todayPrev').addClass('active');
                        break;
                    default:
                        badgeText = '';
                        badgeClass = 'badge';
                        imagePath = '';

                        $('#beforePrev').prop('disabled', true);
                        $('#todayPrev').prop('disabled', true);
                        $('#afterPrev').prop('disabled', true);
                        break;
                }

                $('.preview #badge').text(badgeText).removeClass().addClass(badgeClass);

                if (imagePath) {
                    $('.preview img').attr('src', imagePath);
                    $('.preview img').show();
                    $('.preview #details').removeClass().addClass("card-img-overlay");
                } else {
                    $('.preview img').attr('src', '');
                    $('.preview img').hide();
                    $('.preview #details').removeClass().addClass("card-body");
                }
            });

            $('#startDateInput, #startTimeInput, #endDateInput, #endTimeInput').on('change', function () {
                var startDate = new Date($('#startDateInput').val());
                var startTime = $('#startTimeInput').val();
                var endDate = new Date($('#endDateInput').val());
                var endTime = $('#endTimeInput').val();

                var startDateTime = new Date(startDate.toDateString() + ' ' + startTime);
                var endDateTime = new Date(endDate.toDateString() + ' ' + endTime);

                var duration = Math.abs(endDateTime - startDateTime) / 36e5; // Calculate duration in hours

                if (duration > 1) {
                    $('#duration').text(duration + ' hours');
                } else {
                    $('#duration').text(duration + ' hour');
                }
            });


            $('#beforePrev, #todayPrev, #afterPrev').click(function () {
                $('#beforePrev, #todayPrev, #afterPrev').removeClass('active');

                $(this).addClass('active');
            });

        }

    });
})();
