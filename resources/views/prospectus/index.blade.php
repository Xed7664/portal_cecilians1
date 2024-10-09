@extends('layouts.app')

@section('title', $student->Course . ' Subject Prospectus')

@section('content')
<main id="main" class="main">
    <div class="container-fluid py-4">
        
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">{{ $student->Course }} Prospectus</h2>
            </div>
        </div>

        @if(!empty($prospectus))
            @foreach($prospectus as $year_level_name => $semesters)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header mb-3 bg-white border-bottom">
                        <h2 class="h4 mb-0 font-weight-bolder text-black" style="font-weight: 900;">
                            {{ $year_level_name }}
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach($semesters as $semester => $subjects)
                            <h3 class="h5 mb-3 text-center font-weight-bold">
                                {{ $semester == 3 ? 'Summer' : ($semester . '') }}
                            </h3>
                            <div class="table-responsive mb-4">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Final Grade</th>
                                            <th>Course Code</th>
                                            <th>Course Description</th>
                                            <th>Lec Units</th>
                                            <th>Lab Units</th>
                                            <th>Total Units</th>
                                            <th>Pre-requisite</th>
                                            <th>Total Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjects as $subject)
                                            <tr>
                                                <td>
                                                    @if(isset($studentGrades[$subject->id]))
                                                        {{ number_format($studentGrades[$subject->id], 1) }}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{ $subject->subject_code }}</td>
                                                <td>{{ $subject->description }}</td>
                                                <td>{{ $subject->lec_units }}</td>
                                                <td>{{ $subject->lab_units }}</td>
                                                <td>{{ $subject->total_units }}</td>
                                                <td>{{ $subject->pre_requisite ?? 'None' }}</td>
                                                <td>{{ $subject->total_hours }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        @php
                                            $total = $semesterTotals[$year_level_name][$semester];
                                        @endphp
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td><strong>{{ $total['total_lec_units'] }}</strong></td>
                                            <td><strong>{{ $total['total_lab_units'] }}</strong></td>
                                            <td><strong>{{ $total['total_units'] }}</strong></td>
                                            <td></td>
                                            <td><strong>{{ $total['total_hours'] }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <section class="text-center py-5">
                        <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid mb-4" alt="No Record Found" style="max-width: 300px;">
                        <h2 class="h4 mb-3">No prospectus available</h2>
                        <p class="text-muted">We couldn't find your prospectus for the selected school year and semester. Try changing the year or semester, or contact support for help.</p>
                    </section>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection