@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      
    </div>

    <section class="section mt-4">
      <div class="card">
        <div class="card-body">
              <div class="container my-3">
                <div id="calendar"></div>
              </div>
        </div>
      </div>

    </section>

  </main><!-- End #main -->

  
  <script>

$(function () {
        var calendarEl = document.getElementById('calendar');
        var eventClass = null;
        var calendar = new FullCalendar.Calendar(calendarEl, {
            expandRows: true,
            slotMinTime: '08:00',
            slotMaxTime: '20:00',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            navLinks: true, // can click day/week names to navigate views
            eventClick: function (arg) {
                //var modal = $('#eventModal');
                //modal.find('#eventModalSize #titleInput').val(arg.event.title);
                //modal.find('#eventModalSize #locationInput').val(arg.event.location);
                //modal.find('#eventModalSize #startDateInput').val(arg.event.startStr);
                //modal.find('#eventModalSize #endDateInput').val(arg.event.endStr);
                //
                //// Display additional fields and data
                //modal.find('#eventModalSize #permissionInput').val(arg.event.permission);
                //modal.find('#eventModalSize #accessInput').val(arg.event.access);
                //// Add more fields as needed for other data properties
                //
                //modal.modal('show');
            },
            nowIndicator: true,
            dayMaxEvents: true, // allow "more" link when too many events
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '/get-events',
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

    });
        
    </script>
@endsection
