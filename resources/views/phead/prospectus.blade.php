@extends('layouts.app')

@section('title', 'Department Prospectus')

@section('content')
<main id="main" class="main">
    <section class="section">
        
<main class="py-4">
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
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">{{ $yearLevel->name }}</h2>
            </div>
            
            @foreach($semesters as $semester)
            <div class="card-body">
                <h3 class="h6 text-muted mb-3">{{ $semester->name }} Semester</h3>
                
                @if(isset($subjects[$yearLevel->id][$semester->id]))
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Course Code</th>
                                <th>Description</th>
                                <th class="text-center">Lec Units</th>
                                <th class="text-center">Lab Units</th>
                                <th class="text-center">Total Units</th>
                                <th>Pre-requisite</th>
                                <th class="text-center">Hours/Week</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalLecUnits = 0;
                                $totalLabUnits = 0;
                                $totalUnits = 0;
                                $totalHours = 0;
                            @endphp
                            
                            @foreach($subjects[$yearLevel->id][$semester->id] as $subjectProspectus)
                            <tr>
                                <td><strong>{{ $subjectProspectus->subject->subject_code }}</strong></td>
                                <td>{{ $subjectProspectus->subject->description }}</td>
                                <td class="text-center">{{ $subjectProspectus->subject->lec_units }}</td>
                                <td class="text-center">{{ $subjectProspectus->subject->lab_units }}</td>
                                <td class="text-center">{{ $subjectProspectus->subject->lec_units + $subjectProspectus->subject->lab_units }}</td>
                                <td>{{ $subjectProspectus->subject->pre_requisite ?? 'None' }}</td>
                                <td class="text-center">{{ ($subjectProspectus->subject->lec_units + $subjectProspectus->subject->lab_units) * 3 }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subjectProspectus->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="archiveSubject({{ $subjectProspectus->id }})">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $totalLecUnits += $subjectProspectus->subject->lec_units;
                                $totalLabUnits += $subjectProspectus->subject->lab_units;
                                $totalUnits += $subjectProspectus->subject->lec_units + $subjectProspectus->subject->lab_units;
                                $totalHours += ($subjectProspectus->subject->lec_units + $subjectProspectus->subject->lab_units) * 3;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="2">Semester Total</td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('phead.prospectus.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Subject</label>
                                <select class="form-select" name="subject_id" id="subjectSelect" required>
                                    <option value="">Choose a subject...</option>
                                    @foreach($departmentSubjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                            data-code="{{ $subject->subject_code }}"
                                            data-description="{{ $subject->description }}"
                                            data-lec="{{ $subject->lec_units }}"
                                            data-lab="{{ $subject->lab_units }}"
                                            data-total-hours="{{ $subject->total_hours }}"
                                            data-pre-requisite="{{ $subject->pre_requisite }}">
                                        {{ $subject->subject_code }} - {{ $subject->description }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
    
                            <!-- Additional Fields Here -->
                            <div class="col-md-6">
                                <label class="form-label">Course Code</label>
                                <input type="text" class="form-control" id="courseCode" name="subject_code" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" id="courseDescription" name="description" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Lecture Units</label>
                                <input type="number" class="form-control" id="lecUnits" name="lec_units" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Laboratory Units</label>
                                <input type="number" class="form-control" id="labUnits" name="lab_units" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Hours</label>
                                <input type="number" class="form-control" id="totalHours" name="total_hours" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Pre-requisite</label>
                                <input type="text" class="form-control" id="preRequisite" name="pre_requisite">
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
                        <form action="{{ route('phead.prospectus.update', $subjectProspectus->id) }}" method="POST">
                            @csrf
                            @method('PUT')
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
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subjectSelect');
    const form = document.querySelector('form');

    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                // Fetch data from the selected option's data attributes
                const subjectCode = selectedOption.dataset.code || '';
                const description = selectedOption.dataset.description || '';
                const lecUnits = selectedOption.dataset.lec || '';
                const labUnits = selectedOption.dataset.lab || '';
                const totalHours = selectedOption.dataset.totalHours || '';
                const preRequisite = selectedOption.dataset.preRequisite || '';

                // Populate each field with the corresponding data
                populateField('courseCode', subjectCode);
                populateField('courseDescription', description);
                populateField('lecUnits', lecUnits);
                populateField('labUnits', labUnits);
                populateField('totalHours', totalHours);
                populateField('preRequisite', preRequisite);
            } else {
                // Clear fields if no valid subject is selected
                clearFields();
            }
        });
    } else {
        console.error("Subject select element not found.");
    }

    function populateField(id, value) {
        const field = document.getElementById(id);
        if (field) {
            field.value = value;
        } else {
            console.warn(`Field with id '${id}' not found.`);
        }
    }

    function clearFields() {
        ['courseCode', 'courseDescription', 'lecUnits', 'labUnits', 'totalHours', 'preRequisite'].forEach(id => {
            populateField(id, '');
        });
    }

    // Prevent form submission if no subject is selected
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!subjectSelect.value) {
                event.preventDefault();
                alert('Please select a subject before submitting.');
            }
        });
    }
});


</script>
@endpush

@endsection