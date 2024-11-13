@extends('layouts.app')

@section('title', 'Class Schedule')

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Class Schedule</h1>
    </div>

    <section class="section mt-4">
      <div class="card">
        <div class="card-body">
        <h5 class="card-title"><span>Last Updated: <b>{{ $formattedLatestDate }}</b></span></h5>

        <!-- Filters for School Year and Semester -->
        <form method="GET" action="{{ route('teacher.schedule') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="school_year_id">School Year</label>
                    <select name="school_year_id" id="school_year_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Select School Year</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" {{ $year->id == $selectedSchoolYearId ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="semester_id">Semester</label>
                    <select name="semester_id" id="semester_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Select Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ $semester->id == $selectedSemesterId ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <!-- Schedule Display -->
        <!-- Schedule Display -->
@if(!empty($organizedScheduleData))
            <div class="cd-schedule cd-schedule--loading margin-bottom-lg js-cd-schedule" style="width: calc(100% - 1.25em) !important;">
            <div class="cd-schedule__timeline" style="flex-basis: 10%;">
                <ul>
                    @foreach($timelineData as $time)
                        <li><span>{{ $time }}</span></li>
                    @endforeach
                </ul>
            </div>

    <div class="cd-schedule__events" style="flex-basis: 90%;">
            <ul>
                @foreach($organizedScheduleData as $day => $events)
                    <li class="cd-schedule__group">
                        <div class="cd-schedule__top-info"><span>{{ $day }}</span></div>
                        <ul>
                            @foreach($events as $event)
                                <li class="cd-schedule__event">
                                    <a 
                                        data-start="{{ $event['start_military_time'] }}" 
                                        data-end="{{ $event['end_military_time'] }}" 
                                        data-start-civilian="{{ $event['start_civilian_time'] }}" 
                                        data-end-civilian="{{ $event['end_civilian_time'] }}" 
                                        data-content="{{ $event['subject_id'] }}" 
                                        data-event="{{ $event['event_id'] }}" 
                                        href="javascript:void(0)">
                                        <em class="cd-schedule__time">{{ $event['corrected_time'] }}</em>
                                        <em class="cd-schedule__name">{{ $event['subject_code'] }}</em>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>

                
                <!-- Modal and Cover Layer -->
                <div class="cd-schedule-modal">
                    <!-- Modal Header and Body -->
                    <header class="cd-schedule-modal__header">
                        <div class="cd-schedule-modal__content">
                            <span class="cd-schedule-modal__date"></span>
                            <h3 class="cd-schedule-modal__name"></h3>
                        </div>
                        <div class="cd-schedule-modal__header-bg"></div>
                    </header>
                    
                    <div class="cd-schedule-modal__body">
                        <div class="cd-schedule-modal__event-info"></div>
                        <div class="cd-schedule-modal__body-bg"></div>
                    </div>
                    
                    <a href="#0" class="cd-schedule-modal__close text-replace">Close</a>
                </div>

                <div class="cd-schedule__cover-layer"></div>
            </div>
        @else
            <p>No schedules found for the selected school year and semester.</p>
        @endif
      </div>
    </section>
</main>
<style>
    .cd-schedule__timeline ul {
    padding: 0;
    list-style: none;
    text-align: left;
}

.cd-schedule__timeline li {
    font-size: 0.9em;
    padding: 10px 0;
}

.cd-schedule__events {
    display: flex;
}

.cd-schedule__group {
    flex: 1;
    border-left: 1px solid #ddd;
    padding: 10px;
}

.cd-schedule__top-info {
    background: #f8f9fa;
    padding: 10px;
    text-align: center;
    font-weight: bold;
}

.cd-schedule__event a {
    display: block;
    padding: 10px;
    margin: 5px 0;
    background: #4e73df;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.85em;
}

.cd-schedule__time {
    font-weight: bold;
    display: block;
}

.cd-schedule-modal {
    display: none;
}

</style>
@endsection
