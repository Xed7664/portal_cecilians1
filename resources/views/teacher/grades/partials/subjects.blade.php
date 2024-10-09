<!-- resources/views/teacher/grades/partials/subjects.blade.php -->
@if($subjectsEnrolled->isEmpty())
    <p>No subjects found for the selected filters.</p>
@else
    @foreach($subjectsEnrolled as $subjectEnrolled)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $subjectEnrolled->subject->subject_code }} - {{ $subjectEnrolled->section->name }}</h5>
                    <p class="card-text">{{ $subjectEnrolled->subject->description }}</p>
                    <a href="{{ route('teacher.grades.show', [$subjectEnrolled->id]) }}" class="btn btn-primary">View Grades</a>
                </div>
            </div>
        </div>
    @endforeach
@endif
