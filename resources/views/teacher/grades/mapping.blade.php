<!-- resources/views/teacher/mapping.blade.php -->
@extends('layouts.app')
@section('title', 'Mapping')
@section('content')
<main id="main" class="main">
    <h4>Map Your File Headers to System Fields</h4>
    <form action="{{ route('teacher.grades.mapHeaders') }}" method="POST">
        @csrf
        @foreach($fileHeaders as $header)
            <div class="form-group">
                <label for="map_{{ $header }}">Map '{{ $header }}' to:</label>
                <select name="mappings[{{ $header }}]" class="form-control">
                    <option value="">--Select Field--</option>
                    <option value="student_school_id">Student School ID</option>
                    <option value="student_name">Student Name</option>
                    <option value="subject_code">Subject Code</option>            
                    <option value="subject_description">Subject Description</option>                 
                    <option value="prelim">Prelim</option>
                    <option value="midterm">Midterm</option>
                    <option value="prefinal">Prefinal</option>
                    <option value="final">Final</option>
                    <option value="remarks">Remarks</option>
                </select>
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Submit Mappings</button>
    </form>
</main>
@endsection
