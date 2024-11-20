@extends('layouts.app')

@section('title', 'Schedule')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="pagetitle">
            <h1>Schedules</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('newsfeed') }}">Home</a></li>
                    <li class="breadcrumb-item active">Schedule Management</li>
                </ol>
            </nav>
        </div>
        <div class="card shadow">
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button" role="tab" aria-controls="table" aria-selected="true">Table View</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="plot-tab" data-bs-toggle="tab" data-bs-target="#plot" type="button" role="tab" aria-controls="plot" aria-selected="false">Plot Schedule</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="scheduleTabsContent">
                    <!-- Table View -->
                    <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">
                        <div class="row mb-3">
                            <div class="col-md-4 d-flex">
                                <div class="schedule_year_level w-100"></div>
                            </div>
                            <div class="col-md-4 d-flex">
                                <div class="schedule_section w-100"></div>
                            </div>
                            <div class="col-md-4 d-flex">
                                <div class="schedule_instructor w-100"></div>
                            </div>
                        </div>
                        

                       
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="schedulesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Description</th>
                                        <th>Year Level</th>
                                        <th>Section</th>
                                        <th>Instructor</th>
                                        <th>Room</th>
                                        <th>Days</th>
                                        <th>Time</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->subject->subject_code ?? 'N/A' }}</td>
                                        <td>{{ $schedule->subject->description ?? 'N/A' }}</td>
                                        <td>{{ $schedule->yearLevel->name ?? 'N/A' }}</td>
                                        <td>{{ $schedule->section->name ?? 'N/A' }}</td>
                                        <td>{{ $schedule->teacher->FullName ?? 'N/A' }}</td>
                                        <td>{{ $schedule->room ?? 'N/A' }}</td>
                                        <td>{{ $schedule->days ?? 'N/A' }}</td>
                                        <td>
                                            @if($schedule->start_time && $schedule->end_time)
                                                {{ date('h:i A', strtotime($schedule->start_time)) }} - {{ date('h:i A', strtotime($schedule->end_time)) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No Schedules Assigned Yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Plot Schedule -->
                    <div class="tab-pane fade" id="plot" role="tabpanel" aria-labelledby="plot-tab">
                        <div class="mb-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select id="filterYearLevel" class="form-select">
                                        <option value="">-- Select Year Level --</option>
                                        @foreach($yearLevels as $yearLevel)
                                            <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select id="filterSection" class="form-select" disabled>
                                        <option value="">-- Select Section --</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                        <div class="table-responsive d-none" id="scheduleGridTable">
                                <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time</th>
                                        <th>Monday</th>
                                        <th>Tuesday</th>
                                        <th>Wednesday</th>
                                        <th>Thursday</th>
                                        <th>Friday</th>
                                        <th>Saturday</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($hour = 6; $hour <= 21; $hour++)
                                        @foreach (['00', '30'] as $minute)
                                            @php
                                                $currentTime = sprintf('%02d:%s', $hour, $minute);
                                            @endphp
                                    
                                            @if ($currentTime == '21:30') @break @endif
                                    
                                            <tr>
                                                <td>{{ date('h:i A', strtotime("$hour:$minute")) }}</td>
                                                @for ($day = 0; $day < 6; $day++)
                                                    <td class="schedule-slot"
                                                        data-time="{{ date('H:i', strtotime("$hour:$minute")) }}"
                                                        data-day="{{ ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'][$day] }}">
                                                    </td>
                                                @endfor
                                            </tr>
                                    
                                            @if ($currentTime == '12:30')
                                                <tr class="table-secondary">
                                                    <td colspan="7" class="text-center">Lunch Break (12:30 PM - 1:00 PM)</td>
                                                </tr>
                                                @break
                                            @endif
                                        @endforeach
                                    @endfor
                                 </tbody>
                                </table>
                           
                            </div>
                        <div id="noRecordSection" class="d-flex flex-column align-items-center justify-content-center">
                            <center>
                                <h5>Select year level and section to proceed.</h5>
                            </center>
                            <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="No Record" style="width: 400px; max-width: 100%;">
                        </div>
                       
                            
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>
</main>

<!-- Plot Schedule Modal -->
<div class="modal fade" id="plotScheduleModal" tabindex="-1" aria-labelledby="plotScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                <h5 class="modal-title" id="plotScheduleModalLabel" style="font-size: 1.5rem; font-weight: 600;">Plot Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form method="POST" action="{{ route('phead.schedules.store') }}" id="scheduleForm">
                    @csrf
                    <div class="mb-4">
                        <label for="subject_id" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 0.5rem;">Subject</label>
                        <select id="subject_id" name="subject_id" class="form-select" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem; transition: border-color 0.2s ease-in-out;">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $departmentSubject)
                                <option value="{{ $departmentSubject->subject_id }}">{{ $departmentSubject->subject->subject_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="teacher_id" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 0.5rem;">Teacher</label>
                        <select id="teacher_id" name="teacher_id" class="form-select" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem; transition: border-color 0.2s ease-in-out;">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->FullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="room" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 0.5rem;">Room</label>
                        <input type="text" class="form-control" id="room" name="room" placeholder="Enter Room Name" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                    </div>
                    <div class="mb-4">
                        <label for="days" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 0.5rem;">Days</label>
                        <input type="text" class="form-control" id="days" name="days" placeholder="e.g., Monday, Wednesday" readonly required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 0.5rem;">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" readonly required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label" style="font-weight: 500; color: #333; margin-bottom: 0.5rem;">End Time</label>
                            <select id="end_time" name="end_time" class="form-select" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                                @for ($hour = 6; $hour <= 21; $hour++)
                                    @foreach (['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                        @endphp
                                        @if ($time != '21:30')
                                            <option value="{{ $time }}">{{ date('h:i A', strtotime($time)) }}</option>
                                        @endif
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="year_level_id" name="year_level_id">
                    <input type="hidden" id="section_id" name="section_id">
                    <div class="text-end">
                        
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; font-weight: 500; border-radius: 20px; background-color: #007bff; border: none; transition: background-color 0.3s ease;">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<!-- Schedule Details Modal -->
<div class="modal fade" id="scheduleDetailsModal" tabindex="-1" aria-labelledby="scheduleDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="schedule-container">
                <div class="schedule-time">
                    <div class="schedule-time-text" id="detailsClassTime"></div>
                    <div class="schedule-room-code" id="detailsSubjectCode"></div>
                </div>
                <div class="schedule-details">
                    <div class="schedule-title" id="detailsDescription"></div>
                    
                    <div class="info-group">
                        <div class="info-label">Instructor:</div>
                        <div class="info-value" id="detailsInstructorName"></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Room Name:</div>
                        <div class="info-value" id="detailsRoom"></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Class Days:</div>
                        <div class="info-value" id="detailsClassDays"></div>
                    </div>
                   
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal btn-edit" id="editScheduleBtn">Edit</button>
                <button type="button" class="btn btn-modal btn-delete" id="deleteScheduleBtn">Delete</button>
            </div>
        </div>
    </div>
</div>


<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);">
            <div class="modal-header" style="border-bottom: none; padding: 1rem 1.5rem;">
                <h5 class="modal-title" id="editScheduleModalLabel" style="font-size: 1.25rem; font-weight: 600;">Edit Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduleForm" method="POST" action="/phead/schedules/update">
                @csrf
                @method('POST')
                <input type="hidden" name="schedule_id" id="edit_schedule_id">
                <input type="hidden" name="year_level_id" id="edit_year_level_id">
                <input type="hidden" name="section_id" id="edit_section_id">

                <div class="modal-body" style="padding: 1rem 1.5rem;">
                    <div class="mb-3">
                        <label for="edit_subject_id" class="form-label" style="font-weight: 500; color: #333;">Subject</label>
                        <select id="edit_subject_id" name="subject_id" class="form-select" required style="border-radius: 6px; border: 1px solid #ddd; padding: 0.5rem;">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $departmentSubject)
                                <option value="{{ $departmentSubject->subject_id }}">{{ $departmentSubject->subject->subject_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_teacher_id" class="form-label" style="font-weight: 500; color: #333;">Teacher</label>
                        <select id="edit_teacher_id" name="teacher_id" class="form-select" style="border-radius: 6px; border: 1px solid #ddd; padding: 0.5rem;">
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->FullName }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="mb-3">
                        <label for="edit_room" class="form-label" style="font-weight: 500; color: #333;">Room</label>
                        <input type="text" class="form-control" id="edit_room" name="room" style="border-radius: 6px; border: 1px solid #ddd; padding: 0.5rem;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_days" class="form-label" style="font-weight: 500; color: #333;">Days</label>
                        <input type="text" class="form-control" id="edit_days" name="days" style="border-radius: 6px; border: 1px solid #ddd; padding: 0.5rem;">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="edit_start_time" class="form-label" style="font-weight: 500; color: #333;">Start Time</label>
                            <select class="form-select" id="edit_start_time" name="start_time" required style="border-radius: 6px; border: 1px solid #ddd; padding: 0.5rem;"></select>
                        </div>
                        <div class="col-6">
                            <label for="edit_end_time" class="form-label" style="font-weight: 500; color: #333;">End Time</label>
                            <select class="form-select" id="edit_end_time" name="end_time" required style="border-radius: 6px; border: 1px solid #ddd; padding: 0.5rem;"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 0.75rem 1.5rem;">
                 
                    <button type="submit" class="btn btn-primary" style="padding: 0.4rem 1.25rem; font-weight: 500; border-radius: 20px; background-color: #007bff; border: none; transition: background-color 0.3s ease;">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #scheduleGridTable tbody td:hover {
      background-color: #f0f8ff;
      cursor: pointer;
      color: #333;  
      transition: background-color 0.3s ease;  
  }

  .modal.fade .modal-dialog {
    transform: translate(0, -50px);
    opacity: 0;
    transition: transform 0.3s ease-out, opacity 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translate(0, 0);
    opacity: 1;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: all 0.3s ease-out;
}

.modal-dialog {
    max-width: 800px !important;
    margin: 1.75rem auto;
}

.schedule-container {
    display: flex;
    min-height: 400px;
}

.schedule-time {
    background-color: #8B0000;
    color: #ffffff;
    padding: 2rem;
    width: 40%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.schedule-time-text {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.schedule-room-code {
    font-size: 1.5rem;
    font-weight: 600;
}

.schedule-details {
    width: 60%;
    padding: 2rem;
    background: #ffffff;
}

.schedule-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.info-group {
    margin-bottom: 1rem;
}

.info-label {
    color: #666;
    font-size: 0.85rem;
    font-weight: 500;
}

.info-value {
    color: #333;
    font-size: 1rem;
    font-weight: 600;
}

.schedule-note {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
    font-size: 0.85rem;
    color: #666;
}

.modal-footer {
    background: #e5e0e0;
    border-top: 1px solid #eee;
    padding: 1rem 1.5rem;
}

.btn-modal {
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-edit {
    background-color: #ffc107;
    color: #000000;
    border: none;
}

.btn-delete {
    background-color: #dc3545;
    color: #ffffff;
    border: none;
}

/* Add this for a smooth fade-in effect */
.modal-backdrop {
    opacity: 0;
    transition: opacity 0.3s ease-out;
}

.modal-backdrop.show {
    opacity: 0.5;
}

/* Optional: Add this if you want a slide-up animation when closing */
.modal.fade.show .modal-dialog {
    transform: translate(0, 0);
}

.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}
  </style>





 <script>
    
$(document).ready(function () {

    var schedulesTable = $('#schedulesTable').DataTable({
    paging: true, 
    pageLength: 10, 
    lengthMenu: [10, 25, 50, 100], 
    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' + 
         '<"row"<"col-sm-12"tr>>' + 
         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>', 
    initComplete: function () {
        console.log("Schedules table init complete"); // Log when initialization is complete
        this.api().columns([2, 3, 4]).every(function (colIdx) { // Replace indices as needed for filterable columns
            var column = this;
            console.log("Column header:", column.header().innerHTML); // Log the column header
            var select = $('<select class="form-select"><option value="">Select ' + column.header().innerHTML + '</option>')
                .appendTo($('.schedule_' + column.header().innerHTML.toLowerCase().replace(/\s/g, '_'))) // Replace with proper filter container classes
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    console.log("Selected value:", val); // Log selected value
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
        lengthMenu: 'Show _MENU_ entries', 
        search: "", 
        searchPlaceholder: "Search Schedules..."
    }
});

// Add buttons container setup, if needed
schedulesTable.buttons().container()
    .appendTo($('.dataTables_filter', schedulesTable.table().container()));

    //Gloabl Variables for global stoarge f Data
    const colorMap = {};
    let selectedScheduleId = null;
    let schedules = @json($schedules); 
    const plottedSchedules = {}; 




    // Initialize the dropdowns
    populateTimeOptions('#edit_start_time');
    populateTimeOptions('#edit_end_time');

    function populateTimeOptions(selector) {
    const startTime = 6 * 60; // 6:00 AM in minutes
    const endTime = 21 * 60; // 9:00 PM in minutes
    const interval = 30; // 30 minutes
    
    for (let minutes = startTime; minutes <= endTime; minutes += interval) {
        let hours = Math.floor(minutes / 60);
        const mins = String(minutes % 60).padStart(2, '0');
        const period = hours >= 12 ? 'PM' : 'AM';
        
        // Convert to 12-hour format
        hours = hours % 12 || 12;
        const time12HourFormat = `${String(hours).padStart(2, '0')}:${mins} ${period}`;
        
        $(selector).append(new Option(time12HourFormat, time12HourFormat));
        }
    }

    function updateScheduleInGrid(updatedSchedule) {
         // Find the existing schedule in the global array
        const index = schedules.findIndex(s => s.id === updatedSchedule.id);
        if (index === -1) {
            console.error('Schedule not found in the global array.');
            return;
        }

        // Manually update the related data (subject and teacher)
        const existingSchedule = schedules[index];
        updatedSchedule.subject = existingSchedule.subject;
        updatedSchedule.teacher = existingSchedule.teacher;

        // Replace the old schedule with the updated one
        schedules[index] = updatedSchedule;

        // Clear the old schedule from the grid
        clearScheduleFromGrid(updatedSchedule.id);

        // Re-plot the updated schedule
        plotSchedule(updatedSchedule);

        // Close the modal
        $('#editScheduleModal').modal('hide');

        console.log('Updated schedule plotted successfully:', updatedSchedule);
        }
    

    
    // Function to plot a schedule
    function plotSchedule(schedule) {
        const startTime = schedule.start_time;
        const endTime = schedule.end_time;
        const day = schedule.days;
        const color = getRandomColor(schedule.subject_id);
        const subjectCode = schedule.subject.subject_code;
        const teacherName = schedule.teacher.FullName;

        let isFirstCell = true;
        const startInt = parseInt(startTime.replace(':', ''));
        const endInt = parseInt(endTime.replace(':', ''));

        $(`[data-day="${day}"]`).each(function () {
            const cellTime = $(this).data('time');
            const cellInt = parseInt(cellTime.replace(':', ''));

            if (cellInt >= startInt && cellInt <= endInt) {
                $(this)
                    .css({ 'background-color': color, 'border': 'none' })
                    .addClass('occupied')
                    .data('schedule-id', schedule.id); // Attach schedule ID

                if (isFirstCell) {
                    $(this).html(`
                        <div style="color: white; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                            <strong>${subjectCode}</strong>
                            <span>${teacherName}</span>
                        </div>
                    `);
                    isFirstCell = false;
                }

                // Store visual details in `plottedSchedules`
                plottedSchedules[schedule.id] = {
                    color,
                    subjectCode,
                    teacherName,
                };
            }
        });
    }


          // Function to clear a specific schedule from the grid
    function clearScheduleFromGrid(scheduleId) {
        $('.occupied').each(function () {
            if ($(this).data('schedule-id') === scheduleId) {
                $(this)
                    .html('') // Clear the content
                    .css({
                        'background-color': '',
                        'border': '1px solid #dee2e6'
                    })
                    .removeClass('occupied')
                    .removeData('schedule-id'); // Remove the schedule ID
            }
        });

        // Remove the schedule from plottedSchedules if it exists
        if (plottedSchedules[scheduleId]) {
            delete plottedSchedules[scheduleId];
        }
    }




        // Function to reload the schedule grid based on the `schedules` array
        function reloadScheduleGrid() {
            // Close the modals if they are open
        
            $('#scheduleDetailsModal').modal('hide');

            // Clear existing schedule entries on the grid to avoid duplicates
            $('#scheduleGrid').empty();

            // Loop through all schedules and plot them on the grid
            schedules.forEach(schedule => {
                plotSchedule(schedule);
            });
        }


        

    function getRandomColor(subjectId) {
        if (colorMap[subjectId]) return colorMap[subjectId];
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 3; i++) {
            const colorComponent = Math.floor(Math.random() * 8);
            color += letters[colorComponent].repeat(2);
        }
        colorMap[subjectId] = color;
        return color;
    }

        //Toast Messages for errors
        function showToast(message, type) {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                backgroundColor: type === 'error' ? "#ff6b6b" : "#51cf66",
            }).showToast();
        }


        //conflict checker
        function checkScheduleConflict(schedule) {
        const startInt = parseInt(schedule.start_time.replace(':', ''));
        const endInt = parseInt(schedule.end_time.replace(':', ''));
        let conflict = false;

        $('.occupied').each(function () {
            const cellTime = parseInt($(this).data('time').replace(':', ''));
            const cellDay = $(this).data('day');
            const cellRoom = $(this).data('room');
            const cellScheduleId = $(this).data('schedule-id');
            const cellProgramId = $(this).data('program-id');

            // Skip checking against the current schedule being edited
            if (cellScheduleId === schedule.id) return true;

            // Check for time and day conflicts, as well as room conflicts across programs
            if (cellDay === schedule.days && cellTime >= startInt && cellTime <= endInt && (cellRoom === schedule.room || cellProgramId !== schedule.program_id)) {
                conflict = true;
                return false; // Break the loop
            }
        });

        return conflict;
    }



          // Convert 12-hour format to 24-hour format
          function convertTo24HourFormat(time12hr) {
            const [time, modifier] = time12hr.split(' ');
            let [hours, minutes] = time.split(':');
            if (modifier === 'PM' && hours !== '12') hours = String(parseInt(hours) + 12);
            if (modifier === 'AM' && hours === '12') hours = '00';
            return `${hours}:${minutes}`;
        }

            // Update the end time dropdown based on the selected start time
        function updateEndTimeDropdown(startTime) {
           
            const startInt = parseInt(startTime.replace(':', ''));

         
            const endInt = startInt + 30;

          
            $('#end_time').val('');
            const endTimeOptions = $('#end_time option');
            endTimeOptions.hide();

          
            let foundValidOption = false;
            endTimeOptions.each(function () {
                const optionValue = parseInt($(this).val().replace(':', ''));

             
                if (optionValue > startInt) {
                    $(this).show();

                 
                    if (!foundValidOption) {
                        $(this).prop('selected', true);
                        foundValidOption = true;
                    }
                }
            });

        
            if (!foundValidOption) {
                $('#end_time').val(endTimeOptions.filter(':visible').first().val());
            }
        }


        // Clear the schedule grid
        function clearScheduleGrid() {
            $('.schedule-slot').html('').css({
                'background-color': '',
                'border': '1px solid #dee2e6'
            }).removeClass('occupied');
        }





    //search filter
    
    $('#scheduleSearch').on('keyup', function () {
        const searchValue = $(this).val().toLowerCase();

        // Iterate through each table row except the header
        $('#schedulesTable tbody tr').filter(function () {
            // Check if any of the table cells in the row contain the search value
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.includes(searchValue));
        });
    });


    // Delegated click event on occupied cells to open the details modal
    $('#scheduleGridTable').on('click', '.occupied', function () {
        const scheduleId = $(this).data('schedule-id');
        console.log(`Clicked cell with schedule ID: ${scheduleId}`);
      

        // Find the clicked schedule
        const schedule = schedules.find(s => s.id === scheduleId);
        if (!schedule) {
            console.error(`Schedule with ID ${scheduleId} not found.`);
            return;
        }

        selectedScheduleId = schedule.id; // Store the schedule ID for further actions

        // Populate modal with schedule details
        $('#detailsSubjectCode').text(schedule.subject.subject_code);
        $('#detailsDescription').text(schedule.subject.description);
        $('#detailsClassTime').text(schedule.time);
        $('#detailsClassDays').text(schedule.days);
        $('#detailsInstructorName').text(schedule.teacher.FullName);
        $('#detailsRoom').text(schedule.room);

        // Show the modal
        $('#scheduleDetailsModal').modal('show');
    });

    // Edit button click event in the Schedule Details modal
    $('#editScheduleBtn').on('click', function () {
        $('#scheduleDetailsModal').modal('hide');
        const schedule = schedules.find(s => s.id === selectedScheduleId);
        if (!schedule) return;

        $('#edit_schedule_id').val(schedule.id);
        $('#edit_subject_id').val(schedule.subject_id).change();
        $('#edit_teacher_id').val(schedule.teacher_id).change();
        $('#edit_year_level_id').val(schedule.year_level_id).change();
        $('#edit_section_id').val(schedule.section_id).change();
        $('#edit_room').val(schedule.room);
        $('#edit_days').val(schedule.days);
        $('#edit_start_time').val(schedule.start_time);
        $('#edit_end_time').val(schedule.end_time);

        $('#editScheduleModal').modal('show');
    });

    // Submit the edited schedule data with conflict checking
    $('#editScheduleForm').on('submit', function (e) {
        e.preventDefault();
        const scheduleId = $('#edit_schedule_id').val();

        // Create an object representing the updated schedule
        const updatedSchedule = {
            id: scheduleId,
            start_time: $('#edit_start_time').val(),
            end_time: $('#edit_end_time').val(),
            days: $('#edit_days').val(),
            subject_id: $('#edit_subject_id').val(),
            teacher_id: $('#edit_teacher_id').val(),
            year_level_id: $('#edit_year_level_id').val(),
            section_id: $('#edit_section_id').val(),
            room: $('#edit_room').val(),
        };

        // Check for schedule conflicts before proceeding
        if (checkScheduleConflict(updatedSchedule)) {
            showToast('Schedule conflict detected. Please choose a different time or room.', 'error');
            return;
        }

        // Proceed with the AJAX request if no conflict is detected
        $.ajax({
            url: `/phead/schedules/update/${scheduleId}`,
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                showToast('Schedule updated successfully.', 'success');
                updateScheduleInGrid(response.schedule);
                $('#editScheduleModal').modal('hide');
            },
            error: function (xhr) {
                showToast('Failed to update schedule. Please try again.', 'error');
                console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
            }
        });
    });


    



 // Delete schedule function with dynamic clearing of visual details
 $('#deleteScheduleBtn').on('click', function () {
       
      // Show a confirmation toast
      Toastify({
            text: "Are you sure you want to delete this schedule?",
            duration: -1, // Toast won't disappear automatically
            close: false,
            gravity: "top",
            position: "center",
            backgroundColor: "rgba(255, 255, 255, 0.9)", // Semi-transparent white background
            className: "confirm-delete-toast",
            stopOnFocus: true,
            onClick: function() {} // Prevent closing on click
        }).showToast();

        // Add confirmation and cancel buttons to the toast
        const toastElement = document.querySelector('.confirm-delete-toast');
        toastElement.style.padding = '15px';
        toastElement.style.borderRadius = '8px';
        toastElement.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        toastElement.style.color = '#333'; // Dark text color for better visibility
        toastElement.style.fontWeight = 'bold'; // Make the text bold
        toastElement.style.fontSize = '16px'; // Increase font size for better readability


        const confirmButton = document.createElement('button');
        confirmButton.textContent = 'Confirm';
        confirmButton.className = 'toast-confirm-btn';
        confirmButton.style.marginRight = '10px';
        confirmButton.style.marginLeft = '10px';
        confirmButton.style.padding = '8px 16px';
        confirmButton.style.backgroundColor = '#4CAF50';
        confirmButton.style.color = 'white';
        confirmButton.style.border = 'none';
        confirmButton.style.borderRadius = '4px';
        confirmButton.style.cursor = 'pointer';
        confirmButton.style.fontWeight = 'bold';
        confirmButton.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.2)';
        confirmButton.style.marginTop = '10px'; // Add some space between text and buttons

        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Cancel';
        cancelButton.className = 'toast-cancel-btn';
        cancelButton.style.padding = '8px 16px';
        cancelButton.style.backgroundColor = '#f44336';
        cancelButton.style.color = 'white';
        cancelButton.style.border = 'none';
        cancelButton.style.borderRadius = '4px';
        cancelButton.style.cursor = 'pointer';
        cancelButton.style.fontWeight = 'bold';
        cancelButton.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.2)';
        cancelButton.style.marginTop = '10px'; // Add some space between text and buttons

        toastElement.appendChild(confirmButton);
        toastElement.appendChild(cancelButton);
        // Handle confirmation
        confirmButton.addEventListener('click', function() {
            $.ajax({
            url: `/phead/schedules/${selectedScheduleId}`,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                showToast(response.success || 'Schedule deleted successfully.', 'success');

                // Remove the specific schedule from the global `schedules` array
                schedules = schedules.filter(s => s.id !== selectedScheduleId);

                // Clear only the specific schedule from the grid
                clearScheduleFromGrid(selectedScheduleId);

                // Close the details modal
                $('#scheduleDetailsModal').modal('hide');
            },
            error: function (xhr) {
                showToast('Failed to delete the schedule. Please try again.', 'error');
                console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
            }
            });
            toastElement.remove();
        });

        // Handle cancellation
        cancelButton.addEventListener('click', function() {
            toastElement.remove();
        });
    });




    //add schedule modal form
    $('#scheduleForm').on('submit', function (e) {
        e.preventDefault();
        const schedule = {
            start_time: $('#start_time').val(),
            end_time: $('#end_time').val(),
            days: $('#days').val(),
        };

        if (checkScheduleConflict(schedule)) {
            showToast('Schedule conflict detected. Please choose a different time or room.', 'error');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#plotScheduleModal').modal('hide');
                $('#scheduleForm')[0].reset();
                
                // Add the new schedule to the global schedules array
                schedules.push(response.schedule);

                // Plot the new schedule and update event listener
                plotSchedule(response.schedule);

                showToast('Schedule added successfully!', 'success');
            },
            error: function () {
                showToast('Schedule conflict with another department. Please choose a different time or room.', 'error');
            }
        });
    });





    // Filter year level
    $('#filterYearLevel').on('change', function () {
        const yearLevelId = $(this).val();
        const $filterSection = $('#filterSection');
        const $scheduleGridTable = $('#scheduleGridTable');
        clearScheduleGrid(); // Clear any previously plotted schedules

        // Clear section dropdown and schedule grid
        $filterSection.html('<option value="">-- Select Section --</option>').prop('disabled', true);
        clearScheduleGrid();

        if (yearLevelId) {
            $.get(`/phead/get-sections/${yearLevelId}`, function (response) {
                response.sections.forEach(section => {
                    $filterSection.append(`<option value="${section.id}">${section.name}</option>`);
                });
                $filterSection.prop('disabled', false);
            });
        } else {
            $scheduleGridTable.addClass('d-none');
        }
    });

    $('#filterSection').on('change', function () {
    const sectionId = $(this).val();
    const $scheduleGridTable = $('#scheduleGridTable');
    const $message = $('#noRecordSection');
    
    clearScheduleGrid(); // Clear any previously plotted schedules

    if (sectionId) {
        // Show the schedule grid and hide the message
        $scheduleGridTable.removeClass('d-none');
        $message.addClass('d-none');
        
       
        
        schedules.forEach(schedule => {
            if (schedule.year_level_id == $('#filterYearLevel').val() && schedule.section_id == sectionId) {
                plotSchedule(schedule);
               
            }
        });

        
       
    } else {
        // Show the message and hide the schedule grid if no section is selected
        $message.removeClass('d-none');
        $scheduleGridTable.addClass('d-none');
    }
});


    
          
        // Show modal for plotting schedule
        $('.schedule-slot').on('click', function () {
            if (!$(this).hasClass('occupied')) {
                const selectedDay = $(this).data('day');
                const selectedTime = $(this).data('time');
                $('#days').val(selectedDay).change();
                $('#start_time').val(convertTo24HourFormat(selectedTime));
                $('#plotScheduleModal').modal('show');
            }
        });

        $('#plotScheduleModal').on('show.bs.modal', function () {
            $('#year_level_id').val($('#filterYearLevel').val());
            $('#section_id').val($('#filterSection').val());

            // Update the end time dropdown based on the selected start time
            const startTime = $('#start_time').val();
            if (startTime) {
                updateEndTimeDropdown(startTime);
            }
        });

       


});

   
   </script>



@endsection