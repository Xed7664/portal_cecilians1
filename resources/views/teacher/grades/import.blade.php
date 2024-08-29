@extends('layouts.app')
@section('title', 'Grades Import')
@section('content')
<main id="main" class="main">
    <div class="alert alert-success">
        {{ session('success') }}
    </div>

    <a href="{{ route('teacher.grades.show', ['section' => $sectionId]) }}" class="btn btn-primary">Go to Grade Table</a>
</main>
@endsection
