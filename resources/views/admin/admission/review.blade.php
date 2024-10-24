@extends('layouts.app') <!-- Assuming you have a common admin layout -->
@section('title', 'Review Admission Application')
@section('content')
<main id="main" class="main">
    <section class="newsfeed-container">
        <div class="row">
    <div class="container mt-4">
        <h2>Review Admission Application</h2>

        <!-- Display Admission Details -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Admission Application of {{ $admission->full_name }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $admission->email }}</p>
                <p><strong>Birthday:</strong> {{ $admission->birthday }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($admission->gender) }}</p>
                <p><strong>Address:</strong> {{ $admission->address }}</p>
                <p><strong>Student Type:</strong> {{ ucfirst($admission->student_type) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($admission->status) }}</p>

                <!-- Picture Preview -->
                @if ($admission->picture)
                    <p><strong>Picture:</strong></p>
                    <img src="{{ asset('storage/' . $admission->picture) }}" alt="Picture" class="img-thumbnail" width="150">
                @endif

                <!-- Formcard and Certifications Links -->
                @if ($admission->formcard)
                    <p><strong>Form Card:</strong> <a href="{{ asset('storage/' . $admission->formcard) }}" target="_blank">View File</a></p>
                @endif
                @if ($admission->certifications)
                    <p><strong>Certifications:</strong> <a href="{{ asset('storage/' . $admission->certifications) }}" target="_blank">View File</a></p>
                @endif
            </div>
        </div>

        <!-- Approval/Reject Section -->
        <form action="{{ route('admin.admission.approve', $admission->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Approve Admission</button>
        </form>

        <form action="{{ route('admin.admissions.reject', $admission->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">Reject Admission</button>
        </form>

        <!-- Back Button -->
        <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Back to Admission List</a>
    </div>
</div>
</section>
</main>
@endsection
