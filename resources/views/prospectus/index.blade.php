@extends('layouts.app')

@section('title', $prospectus[key($prospectus)][key($prospectus[key($prospectus)])][0]['department_code'] . ' Subject Prospectus')

@section('content')
<main id="main" class="main">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">
                    {{ $prospectus[key($prospectus)][key($prospectus[key($prospectus)])][0]['department_code'] }} Prospectus
                    @if(isset($prospectus[key($prospectus)][key($prospectus[key($prospectus)])][0]['department_name']))
                        <small class="text-muted">
                            - {{ $prospectus[key($prospectus)][key($prospectus[key($prospectus)])][0]['department_name'] }}
                        </small>
                    @endif
                </h2>
            </div>
        </div>

        @if(!empty($prospectus))
            @foreach($prospectus as $year_level_name => $semesters)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header mb-3 bg-white border-bottom">
                        <h2 class="h4 mb-0 font-weight-bolder text-black">{{ $year_level_name }}</h2>
                    </div>
                    <div class="card-body">
                        @foreach($semesters as $semester => $subjects)
                            <h3 class="h5 mb-3 text-center font-weight-bold">
                                {{ $subjects[0]['semester_name'] == 'Summer' ? 'Summer' : ($subjects[0]['semester_name'] . ' Semester') }}
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
                                            <th>Pre-requisite/Co-Requisite</th>
                                            <th>Total Hours/Week</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjects as $subjectData)
                                            @php
                                                $subject = $subjectData['subject'];
                                                $subjectId = $subject->id ?? null;
                                                $gradeInfo = $studentGrades[$subjectId] ?? null;
                                                $finalGrade = $gradeInfo['grade'] ?? '--';
                                                $isPassing = $gradeInfo['pass_status'] ?? true;
                                                $gradeClass = $isPassing ? '' : 'failing-grade'; // Class for failing grades
                                                $hoverMessage = !$isPassing ? "I'm sorry, you did not pass this subject." : '';
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="{{ $gradeClass }}" data-hover-message="{{ $hoverMessage }}">
                                                        {{ is_numeric($finalGrade) ? number_format($finalGrade, 1) : $finalGrade }}
                                                    </span>
                                                </td>
                                                <td>{{ $subject->subject_code ?? '--' }}</td>
                                                <td>{{ $subject->description ?? '--' }}</td>
                                                <td>{{ $subject->lec_units ?? '--' }}</td>
                                                <td>{{ $subject->lab_units ?? '--' }}</td>
                                                <td>{{ $subject->total_units ?? '--' }}</td>
                                                <td>{{ $subject->pre_requisite ?? 'None' }}</td>
                                                <td>{{ $subject->total_hours ?? '--' }}</td>
                                            </tr>
                                        @endforeach



                                    </tbody>

                                    <tfoot class="table-light">
                                        @php
                                            $total = $semesterTotals[$year_level_name][$semester] ?? [
                                                'total_lec_units' => 0,
                                                'total_lab_units' => 0,
                                                'total_units' => 0,
                                                'total_hours' => 0
                                            ];
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
<style>

.failing-grade {
    color: red;
    position: relative;
    z-index: 2; /* Elevate failing grade above the table */
}
.tooltip-message {
    position: absolute;
    bottom: calc(100% + 5px);
    left: 0; /* Align tooltip to the left edge */
    transform: translateX(-1%);
    background-color: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 6px 10px;
    border-radius: 5px;
    white-space: nowrap;
    font-size: 0.875rem;
    font-weight: 500;
    display: none;
    opacity: 0;
    transition: opacity 0.2s ease;
    z-index: 1050;
    cursor: pointer;
}

.tooltip-message::after {
    content: '';
    position: absolute;
    top: 100%; /* Place arrow at the bottom of the tooltip */
    left: 5%; /* Position arrow at the left edge */
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
}


</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const failingGrades = document.querySelectorAll('.failing-grade');

        failingGrades.forEach(grade => {
            const tooltipMessage = document.createElement('div');
            tooltipMessage.classList.add('tooltip-message');
            tooltipMessage.textContent = grade.getAttribute('data-hover-message');
            grade.appendChild(tooltipMessage);

            // Show tooltip on hover
            grade.addEventListener('mouseenter', () => {
                tooltipMessage.style.display = 'block';
                setTimeout(() => {
                    tooltipMessage.style.opacity = 1;
                }, 50); // Small delay for smoother transition
            });

            // Hide tooltip when hover ends
            grade.addEventListener('mouseleave', () => {
                tooltipMessage.style.opacity = 0;
                setTimeout(() => {
                    tooltipMessage.style.display = 'none';
                }, 200); // Wait for transition to complete
            });
        });
    });
</script>
