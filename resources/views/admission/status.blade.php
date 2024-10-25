@extends('layouts.auth')

@section('title', 'Check Admission Status')

@section('content')
<div class="card-body pt-4 border border-danger border-opacity-25 border-25 border-top-0 bg-body-tertiary registration-form">
    <div class="container">
        <h2>Check Admission Status</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admission.status.check') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tracker_code">Tracker Code</label>
                <input type="text" class="form-control" id="tracker_code" name="tracker_code" required>
            </div>

            <button type="submit" class="btn btn-primary">Check Status</button>
        </form>
    </div>
</div>
@endsection
