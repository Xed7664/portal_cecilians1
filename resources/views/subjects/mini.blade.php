<div class="cd-schedule-modal__event-info">
    <div>
        <h2><b>{{ $courseData->description }}</b></h2>
        <div class="margin-top-sm margin-bottom-md">
            <p class="margin-top-xs color-dark">Instructor: <b>{{ $courseData->instructor_name }}</b></p>
            <p class="margin-top-xs color-dark">Room Name: <b>{{ $courseData->room_name }}</b></p>
            <p class="margin-top-xs color-dark">Class Days: <b>{{ $courseData->corrected_day }}</b></p>
            <p class="margin-top-xs color-dark">Class Time: <b>{{ $courseData->corrected_time }}</b></p>
        </div>

        <div class="margin-top-xl">
            <p class="margin-bottom-lg color-dark text-sm"><b>Note: </b>This information was last updated on <b>{{ $courseData->updatedDate }}</b>.</p>
            
            <a href="{{ route('subject.details', ['subject_id' => $courseData->id]) }}" target="">
                <button type="button" class="btn btn--{{ $theme == 'light' ? 'danger' : 'primary' }}">View</button>
            </a>

            @if(!empty($courseData->CUSTOM_GoogleClassroom))
                <a href="{{ $courseData->CUSTOM_GoogleClassroom }}" target="_blank"><button type="button" class="btn btn--success">Google Classroom</button></a>
            @endif

            <a href="{{ $courseData->CUSTOM_GoogleClassroom }}" target="_blank" class="visually-hidden"><button type="button" class="btn btn--{{ $theme == 'light' ? 'danger' : 'primary' }}">Hello World!</button></a>
        </div>
    </div>
</div>