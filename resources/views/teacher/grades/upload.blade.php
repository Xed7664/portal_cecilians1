<!-- resources/views/teacher/grades/upload.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Upload and Map Grading Sheet</h2>

    <!-- File Upload Form -->
    <form action="{{ route('grades.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Upload Grading Sheet</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <!-- Display Column Mapping Interface After File Upload -->
    @if(isset($headers))
        <form action="{{ route('grades.map') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="mapping">Map Columns</label>
                <div class="row">
                    @foreach($headers as $index => $header)
                        <div class="col-md-4">
                            <label for="mapping[{{ $index }}]">{{ $header }}</label>
                            <select class="form-control" name="mapping[{{ $index }}]">
                                <option value="">-- Select Field --</option>
                                <option value="student_school_id">Student School ID</option>
                                <option value="student_name">Student Name</option>
                                <option value="subject_code">Subject Code</option>
                                <option value="prelim">Prelim</option>
                                <option value="midterm">Midterm</option>
                                <option value="prefinal">Prefinal</option>
                                <option value="final">Final</option>
                                <option value="remarks">Remarks</option>
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-success">Map and Import</button>
        </form>
    @endif
</div>
@endsection
