@extends('layouts.app')

@section('title', 'Department Prospectus')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="container">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Department Prospectus</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('phead.prospectus.archived') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-archive"></i> View Archived
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-plus-lg"></i> Add Subject
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Courses...">
                </div>
                <div class="col-md-4">
                    <select id="yearLevelFilter" class="form-select">
                        <option value="">All Year Levels</option>
                        @foreach($yearLevels as $yearLevel)
                            <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="semesterFilter" class="form-select">
                        <option value="">All Semesters</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Year Level and Semester Cards -->
            @foreach($yearLevels as $yearLevel)
            <div class="card shadow-sm mb-4 prospectus-year" data-year-level="{{ $yearLevel->id }}">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">{{ $yearLevel->name }}</h2>
                </div>
                
                @foreach($semesters as $semester)
                <div class="card-body prospectus-semester" data-semester="{{ $semester->id }}">
                    <h3 class="h6 text-muted mb-3">{{ $semester->name }} Semester</h3>
                    
                    @if(isset($subjects[$yearLevel->id][$semester->id]))
                    <div class="table-responsive">
                        <table class="table table-hover align-middle prospectus-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Description</th>
                                    <th class="text-center">Lec Units</th>
                                    <th class="text-center">Lab Units</th>
                                    <th class="text-center">Total Units</th>
                                    <th>Pre-requisite</th>
                                    <th class="text-center">Total Hours</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalLecUnits = $totalLabUnits = $totalUnits = $totalHours = 0;
                                @endphp
                                
                                @foreach($subjects[$yearLevel->id][$semester->id] as $subjectProspectus)
                                <tr>
                                    <td><strong>{{ $subjectProspectus->subject->subject_code }}</strong></td>
                                    <td>{{ $subjectProspectus->subject->description }}</td>
                                    <td class="text-center">{{ $subjectProspectus->subject->lec_units }}</td>
                                    <td class="text-center">{{ $subjectProspectus->subject->lab_units }}</td>
                                    <td class="text-center">{{ $subjectProspectus->subject->lec_units + $subjectProspectus->subject->lab_units }}</td>
                                    <td>{{ $subjectProspectus->subject->pre_requisite ?? 'None' }}</td>
                                    <td class="text-center">{{ $subjectProspectus->subject->total_hours }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subjectProspectus->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('phead.prospectus.archive', $subjectProspectus->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $totalLecUnits += $subjectProspectus->subject->lec_units;
                                    $totalLabUnits += $subjectProspectus->subject->lab_units;
                                    $totalUnits += $subjectProspectus->subject->lec_units + $subjectProspectus->subject->lab_units;
                                    $totalHours += $subjectProspectus->subject->total_hours;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td colspan="2">Total</td>
                                    <td class="text-center">{{ $totalLecUnits }}</td>
                                    <td class="text-center">{{ $totalLabUnits }}</td>
                                    <td class="text-center">{{ $totalUnits }}</td>
                                    <td></td>
                                    <td class="text-center">{{ $totalHours }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No subjects found for this semester.
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
           <!-- Add Subject Modal -->
           <div class="modal fade" id="addSubjectModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('phead.prospectus.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Select Subject</label>
                                    <select class="form-select" name="subject_id" id="subjectSelect" required>
                                        <option value="">Choose a subject...</option>
                                        @foreach($departmentSubjects as $departmentSubject)
                                            <option value="{{ $departmentSubject->subject->id }}">
                                                {{ $departmentSubject->subject->subject_code }} - {{ $departmentSubject->subject->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Dropdowns for Year Level and Semester -->
                                <div class="col-md-6">
                                    <label class="form-label">Select Year Level</label>
                                    <select class="form-select" name="year_level_id" required>
                                        <option value="">Choose year level...</option>
                                        @foreach($yearLevels as $yearLevel)
                                        <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Select Semester</label>
                                    <select class="form-select" name="semester_id" required>
                                        <option value="">Choose semester...</option>
                                        @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Subject Modals -->
        @foreach($subjects as $yearLevelSubjects)
            @foreach($yearLevelSubjects as $semesterSubjects)
                @foreach($semesterSubjects as $subjectProspectus)
                <div class="modal fade" id="editSubjectModal{{ $subjectProspectus->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Subject</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('phead.prospectus.update', $subjectProspectus->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Course Code</label>
                                        <input type="text" class="form-control" name="subject_code" value="{{ $subjectProspectus->subject->subject_code }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description" value="{{ $subjectProspectus->subject->description }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Lecture Units</label>
                                            <input type="number" class="form-control" name="lec_units" value="{{ $subjectProspectus->subject->lec_units }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Laboratory Units</label>
                                            <input type="number" class="form-control" name="lab_units" value="{{ $subjectProspectus->subject->lab_units }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Total Hours</label>
                                            <input type="number" class="form-control" name="total_hours" value="{{ $subjectProspectus->subject->total_hours }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pre-requisite</label>
                                        <input type="text" class="form-control" name="pre_requisite" value="{{ $subjectProspectus->subject->pre_requisite }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforeach
        @endforeach
    </section>
</main>

<!-- Search and Filter Script -->
    <script>
        $(document).ready(function () {
            function filterProspectus() {
                var selectedYearLevel = $('#yearLevelFilter').val();
                var selectedSemester = $('#semesterFilter').val();
                var searchValue = $('#searchInput').val().toLowerCase();
                
                $('.prospectus-year').each(function () {
                    var yearMatch = (selectedYearLevel === '' || $(this).data('year-level') == selectedYearLevel);
                    $(this).toggle(yearMatch);

                    $(this).find('.prospectus-semester').each(function () {
                        var semesterMatch = (selectedSemester === '' || $(this).data('semester') == selectedSemester);
                        $(this).toggle(yearMatch && semesterMatch);
                    });
                    
                    $(this).find('.prospectus-table tbody tr').each(function () {
                        var textMatch = $(this).text().toLowerCase().includes(searchValue);
                        $(this).toggle(textMatch);
                    });
                });
            }

            $('#yearLevelFilter, #semesterFilter').on('change', filterProspectus);
            $('#searchInput').on('keyup', filterProspectus);
        });
    </script>
@endsection
