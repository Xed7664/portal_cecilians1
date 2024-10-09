@extends('layouts.app')

@section('title', 'Debug Page')

@section('content')
<main id="main" class="main">

@php
    $rawTimeRanges = '7:30-12:30 NN,3:30-5:00PM';

    $timeRanges = explode(',', $rawTimeRanges);

    $formattedTimeRanges = []; // Initialize an array to store formatted time ranges

    foreach ($timeRanges as $rawTimeRange) {
        list($startTime, $endTime) = explode('-', $rawTimeRange);

        $startHasMeridiem = stripos($startTime, 'AM') !== false || stripos($startTime, 'PM') !== false || stripos($startTime, 'NN') !== false;
        $endHasMeridiem = stripos($endTime, 'AM') !== false || stripos($endTime, 'PM') !== false || stripos($endTime, 'NN') !== false;

        $formattedStartTime = $startTime; // Initialize the variables
        $formattedEndTime = $endTime;

        // If endTime has NN on it, set the startTime to AM
        if (stripos($endTime, 'NN') !== false) {
            $endTime = str_replace('NN', 'PM', $endTime);
            if(!$startHasMeridiem && $endHasMeridiem){
                $guide = 'AM';
                $startTime =  \Carbon\Carbon::parse(trim($startTime . ' ' . $guide))->format('h:i A');
            }
        } else {
            // Replace 'NN' with 'PM' in both start and end times
            $startTime = str_replace('NN', 'PM', $startTime);
            $endTime = str_replace('NN', 'PM', $endTime);
        }

        // Rerun test
        $startHasMeridiem = stripos($startTime, 'AM') !== false || stripos($startTime, 'PM') !== false || stripos($startTime, 'NN') !== false;
        $endHasMeridiem = stripos($endTime, 'AM') !== false || stripos($endTime, 'PM') !== false || stripos($endTime, 'NN') !== false;

        if ($startHasMeridiem && !$endHasMeridiem) {
            // If start time has meridiem and end time has none, assign end time with meridiem nearer to start time
            $guide = strtoupper(substr(trim($startTime), -2));
            $formattedEndTime = \Carbon\Carbon::parse(trim($endTime . ' ' . $guide))->format('h:i A');
        } elseif (!$startHasMeridiem && $endHasMeridiem) {
            // If end time has meridiem and start time has none, assign start time with meridiem nearer to end time
            $guide = strtoupper(substr(trim($endTime), -2));
            $formattedStartTime = \Carbon\Carbon::parse(trim($startTime . ' ' . $guide))->format('h:i A');
        } else {
            // If both start time and end time have meridiem, or both have none, no need to change
            $formattedStartTime = \Carbon\Carbon::parse(trim($startTime))->format('h:i A');
            $formattedEndTime = \Carbon\Carbon::parse(trim($endTime))->format('h:i A');
        }

        $formattedTimeRanges[] = "$formattedStartTime - $formattedEndTime";
    }

    // Join the formatted time ranges back into a comma-separated string
    $formattedResult = implode(', ', $formattedTimeRanges);

    echo $formattedResult;
@endphp


    <div class="pagetitle mb-5">
        <h1>My Permissions</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active">Blank</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        
    <div class="mb-4 col-xl-12 col-sm-12">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">User Permissions</h5>
                    
                    <p>Here are the permissions that is assigned to your account.</p>
                    @if(Auth::check())
    @php
        // Enable query logging
        \DB::enableQueryLog();

        // Retrieve the user's permissions
        $permissions = Auth::user()->getUserPermissions();

        // Get the logged queries
        $queries = \DB::getQueryLog();

        // Display the user's permissions
        dump($permissions);
    @endphp
                        
@else
    <!-- User is not authenticated -->
@endif



                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">Role Permissions</h5>
                    <p>Here are the permissions that is assigned to your account.</p>
                    @php
                        $currentPermissions = Auth::user()->getRolePermissions();
                        dump($currentPermissions); // Use dump to display the contents
                    @endphp
                    </div>
                </div>
            </div>

            
        </div>
    </section>
</main>
@endsection
