<div class="row">
    @foreach($departments as $department)
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $department->code }} - {{ $department->name }}</h5>
                    <p class="card-text">{{ $department->description }}</p>
                    <a href="/department/{{ $department->id }}" class="btn btn-primary">Manage Department</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
