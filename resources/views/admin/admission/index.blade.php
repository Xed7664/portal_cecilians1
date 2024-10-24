<!-- resources/views/admin/admission/index.blade.php -->
@extends('layouts.app')
@section('title', 'list of Admission')
@section('content')
<main id="main" class="main">
    <section class="newsfeed-container">
        <div class="row">
    <h1>Admissions</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admissions as $admission)
            <tr>
                <td>{{ $admission->id }}</td>
                <td>{{ $admission->full_name }}</td>
                <td>{{ $admission->email }}</td>
                <td>{{ $admission->status }}</td>
                <td>
                    <a href="{{ route('admin.admission.review', $admission->id) }}" class="btn btn-primary">Review</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</section>
</main>
@endsection
