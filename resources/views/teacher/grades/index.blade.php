@extends('layouts.app')

@section('title', 'Grade')

@section('content')
<main id="main" class="main">
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 px-4 py-3">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered border-top pt-3 border-light-subtle border-opacity-10 border-1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="manage-grades-tab" data-bs-toggle="tab" href="#manage-grades" role="tab" aria-controls="manage-grades" aria-selected="true">
                                <i class="bx bx-book-bookmark me-1"></i> Manage Grades
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="my-departments-tab" data-bs-toggle="tab" href="#my-departments" role="tab" aria-controls="my-departments" aria-selected="false">
                                <i class="bx bx-building me-1"></i> My Departments
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="my-students-tab" data-bs-toggle="tab" href="#my-students" role="tab" aria-controls="my-students" aria-selected="false">
                                <i class="bx bx-group me-1"></i> My Students
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="my-subjects-tab" data-bs-toggle="tab" href="#my-subjects" role="tab" aria-controls="my-subjects" aria-selected="false">
                                <i class="bx bx-book-open me-1"></i> My Subjects
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content pt-3">
                        <!-- Manage Grades Tab Content -->
                        <div class="tab-pane fade show active" id="manage-grades" role="tabpanel" aria-labelledby="manage-grades-tab">
                            <!-- Filters and Subject Content -->
                            <div class="mb-4">
                                <form id="filters-form">
                                    <div class="row g-3 align-items-center">
                                        <!-- School Year Filter -->
                                        <div class="col-md-3">
                                            <label for="school_year_id" class="form-label">School Year</label>
                                            <select name="school_year_id" id="school_year_id" class="form-control">
                                                <option value="">Select School Year</option>
                                                @foreach($schoolYears as $schoolYear)
                                                    <option value="{{ $schoolYear->id }}" {{ ($selectedSchoolYearId == $schoolYear->id) ? 'selected' : '' }}>
                                                        {{ $schoolYear->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Semester Filter -->
                                        <div class="col-md-3" id="semester-container">
                                            <label for="semester_id" class="form-label">Semester</label>
                                            <select name="semester_id" id="semester_id" class="form-control">
                                                <option value="">Select Semester</option>
                                                @foreach($semesters->unique('name') as $semester)
                                                    <option value="{{ $semester->id }}" {{ ($selectedSemesterId == $semester->id) ? 'selected' : '' }}>
                                                        {{ $semester->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Department Filter -->
                                        <div class="col-md-3">
                                            <label for="department" class="form-label">Filter by Department</label>
                                            <select name="department_id" id="department" class="form-select">
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ ($selectedDepartmentId == $department->id) ? 'selected' : '' }}>
                                                        {{ $department->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Section Filter -->
                                        <div class="col-md-3">
                                            <label for="section" class="form-label">Filter by Section</label>
                                            <select name="section_id" id="section" class="form-select">
                                                <option value="">Select Section</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" {{ ($selectedSectionId == $section->id) ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Subjects Content -->
                            <div class="row g-4" id="subjects-container">
                                <div id="loading-spinner" class="d-none text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- My Departments Tab Content -->
                        <div class="tab-pane fade" id="my-departments" role="tabpanel" aria-labelledby="my-departments-tab">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Departments</h5>
                                    @if($departmentsHandled->isEmpty())
                                        <p>No departments found for the subjects you're teaching.</p>
                                    @else
                                        <div class="row">
                                            @foreach($departmentsHandled as $department)
                                                <div class="col-md-4">
                                                    <div class="department-card mb-3 shadow-sm text-center p-3" style="background-color: {{ $department->color_code }}; color: white; border-radius: 8px;">
                                                        <h5 class="mb-1">{{ $department->name }}</h5>
                                                        <p>Code: {{ $department->code }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- My Students Tab Content -->
                        <div class="tab-pane fade" id="my-students" role="tabpanel" aria-labelledby="my-students-tab">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Students</h5>
                                    <p class="card-text">This section displays all students you are responsible for.</p>
                                </div>
                            </div>
                        </div>

                        <!-- My Subjects Tab Content -->
                        <div class="tab-pane fade" id="my-subjects" role="tabpanel" aria-labelledby="my-subjects-tab">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Subjects</h5>
                                    <p class="card-text">This section displays all subjects assigned to you.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Optional additional CSS to style the department and subject cards -->
<style>
    /* Department Card Styling */
    .department-card {
        background-color: #64B5F6;
        transition: transform 0.2s ease-in-out;
    }
    
    .department-card:hover {
        transform: scale(1.05);
        background-color: #64B5F6;
    }

    /* Subject Card Styling */
    .subject-card {
        border-radius: 10px;
        transition: transform 0.2s ease-in-out;
        overflow: hidden;
        border: 1px solid #D3D3D3; /* Light border added */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Smooth shadow */
    }

    .subject-card:hover {
        transform: scale(1.05);
        border-color: #64B5F6; /* Change border color on hover */
    }

    /* Subject Card Header */
    .subject-card-header {
        padding: 15px;
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
        border-bottom: 3px solid #1565C0;
    }

    /* Card Body */
    .subject-card .card-body {
        padding: 20px;
        text-align: center;
        background-color: #F5F5F5;
    }

    /* View Grades Button */
    .btn-view-grades {
        background-color: #64B5F6;
        color: white;
        border-radius: 20px;
        padding: 10px 20px;
        font-weight: bold;
        text-transform: uppercase;
        transition: background-color 0.3s;
    }

    .btn-view-grades:hover {
        background-color: #1565C0;
    }
</style>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filtersForm = document.getElementById('filters-form');
    const subjectsContainer = document.getElementById('subjects-container');
    const loadingSpinner = document.getElementById('loading-spinner');
    const searchInput = document.createElement('input');
    const paginationContainer = document.createElement('nav');
    let cache = {};
    let currentPage = 1;
    const itemsPerPage = 3;
    let filteredSubjects = [];

    searchInput.type = 'text';
    searchInput.className = 'form-control mb-3';
    searchInput.placeholder = 'Search subjects...';
    subjectsContainer.before(searchInput);

    paginationContainer.className = 'd-flex justify-content-center mt-3';
    subjectsContainer.after(paginationContainer);

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        if (searchTerm) {
            filteredSubjects = cache.subjects.filter(subject =>
                subject.schedule.subject.subject_code.toLowerCase().includes(searchTerm) ||
                subject.schedule.subject.description.toLowerCase().includes(searchTerm)
            );
        } else {
            filteredSubjects = cache.subjects;
        }
        renderSubjects(currentPage);
    });

    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const fetchSubjects = debounce(function () {
        const schoolYearId = document.getElementById('school_year_id').value;
        const semesterId = document.getElementById('semester_id').value;
        const departmentId = document.getElementById('department').value;
        const sectionId = document.getElementById('section').value;

        if (!schoolYearId) {
            displayEmptyMessage('Please select a school year to begin.');
            return;
        }

        const formData = new FormData(filtersForm);
        const params = new URLSearchParams(formData).toString();

        loadingSpinner.classList.remove('d-none');

        if (cache[params]) {
            cache.subjects = cache[params];
            filteredSubjects = cache.subjects;
            renderSubjects(currentPage);
            loadingSpinner.classList.add('d-none');
            return;
        }

        fetch(`{{ route('fetch.subjects') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                cache[params] = data;
                cache.subjects = data;
                filteredSubjects = cache.subjects;
                renderSubjects(currentPage);
                loadingSpinner.classList.add('d-none');
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                displayEmptyMessage('An error occurred while fetching subjects. Please try again.');
                loadingSpinner.classList.add('d-none');
            });
    }, 300);

    function renderSubjects(page) {
        subjectsContainer.innerHTML = '';
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedSubjects = filteredSubjects.slice(startIndex, endIndex);

        if (paginatedSubjects.length === 0) {
            displayEmptyMessage('No subjects found.');
            return;
        }

        const departmentColors = {
    'BSIT': '#4CAF50',   // Green for BSIT
    'BSBA': '#FF9800',   // Orange for BSBA
    'BEED': '#03A9F4',   // Light Blue for BEED
    // Add more departments and colors here
};

paginatedSubjects.forEach(subjectEnrolled => {
    // Access the department code from schedule.program
    const departmentColor = departmentColors[subjectEnrolled.schedule.program.code] || '#607D8B'; // Default color if department not in map

    subjectsContainer.innerHTML += `
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm subject-card" style="border-left: 5px solid ${departmentColor}; background-color: #f9f9f9; border-radius: 8px;">
                <div class="card-header text-white" style="background-color: ${departmentColor}; border-radius: 8px 8px 0 0; padding: 10px;">
                    <h5 class="card-title mb-0" style="font-size: 1.2rem; font-weight: bold;">
                        ${subjectEnrolled.schedule.program.code} - ${subjectEnrolled.schedule.subject.subject_code} - ${subjectEnrolled.schedule.section.name}
                    </h5>
                </div>
                <div class="card-body" style="padding: 15px;">
                    <p class="card-text" style="font-size: 1rem; color: #555;">
                        ${subjectEnrolled.schedule.subject.description}
                    </p>
                    <a href="/teacher/subject/grades/${subjectEnrolled.id}" class="btn btn-primary btn-view-grades" style="background-color: ${departmentColor}; border: none;">
                        View Grades
                    </a>
                </div>
            </div>
        </div>`;
        });

        updatePagination(filteredSubjects.length);
    }

    function updatePagination(totalItems) {
        paginationContainer.innerHTML = '';
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('button');
            pageLink.textContent = i;
            pageLink.className = 'btn btn-secondary mx-1';
            if (i === currentPage) pageLink.classList.add('active');

            pageLink.addEventListener('click', () => {
                currentPage = i;
                renderSubjects(currentPage);
            });

            paginationContainer.appendChild(pageLink);
        }
    }

    function displayEmptyMessage(message) {
        subjectsContainer.innerHTML = `
            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <div class="col-12 text-center">
                    <p class="fs-6 text-center"><b>${message}</b></p>
                    <img src="{{ asset('img/svg/no-record.svg') }}" class="img-fluid py-5" alt="No Records Found">
                </div>
            </section>`;
    }

    ['school_year_id', 'semester_id', 'department', 'section'].forEach(id => {
        document.getElementById(id).addEventListener('change', fetchSubjects);
    });

    fetchSubjects();
});

</script>

@endsection
