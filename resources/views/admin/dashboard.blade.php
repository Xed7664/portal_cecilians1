@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<main id="main" class="main bg-light py-4">
    <div class="container-fluid">
        <div class="PageTitle">
            <h2>Data overview</h2>
            
        </div>
        

        <div class="row">
            <div class="col-lg-9">
                <div class="row">
                    <!-- Students Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Students</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentsCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-people fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teachers Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Teachers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $teachersCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-person-video3 fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Subjects</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjectsCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-book fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Admissions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Admissions</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('admin.admissions.index') }}">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAdmissions as $admission)
                                    <tr>
                                        <td>{{ $admission->student_type }}-{{ $admission->id }}</td>
                                        <td>{{ $admission->full_name }}</td>
                                        <td>{{ $admission->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $admission->status == 'approved' ? 'success' : ($admission->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($admission->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side column -->
            <div class="col-lg-3">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>
                        <div class="activity" id="recent-activity">
                            <!-- Activities will be dynamically inserted here -->
                        </div>
                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End Right side column -->
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    function fetchRecentActivity() {
        $.ajax({
            url: '{{ route("admin.recent-activity") }}',
            method: 'GET',
            success: function(response) {
                $('#recent-activity').html(response);
            },
            error: function(xhr) {
                console.log('Error fetching recent activity:', xhr);
            }
        });
    }

    // Fetch recent activity every 30 seconds
    setInterval(fetchRecentActivity, 30000);

    // Initial fetch
    fetchRecentActivity();
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}

</style>
@endsection