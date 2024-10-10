@foreach($subjectsEnrolled as $subjectEnrolled)
    <div class="col-md-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                @php
                    $cleanSubjectCode = preg_replace('/\s*\(.*?\)\s*/', '', $subjectEnrolled->subject->subject_code);
                @endphp
                <h5 class="card-title">{{ $subjectEnrolled->subject->department->code }}-{{ $cleanSubjectCode }} - {{ $subjectEnrolled->section->name }}</h5>
                <p class="card-text">{{ $subjectEnrolled->subject->description }}</p>
                <a href="{{ route('teacher.subject.grades', ['subjectEnrolledId' => $subjectEnrolled->id]) }}" class="btn btn-primary">View Grades</a>
            </div>
        </div>
    </div>
@endforeach
