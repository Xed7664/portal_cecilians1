@extends('layouts.app')

@section('title', 'CMO')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="container">
            <h1>CHED Curriculum Memorandum Orders (CMOs)</h1>
            <!-- Add CMO Button -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cmoModal" onclick="openModal()">
                Add New CMO
            </button>

            <!-- CMO Table -->
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CMO Number</th>
                        <th>Description</th>
                        <th>Year Issued</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cmos as $cmo)
                    <tr>
                        <td>{{ $cmo->id }}</td>
                        <td>{{ $cmo->cmo_number }}</td>
                        <td>{{ $cmo->description }}</td>
                        <td>{{ $cmo->year_issued }}</td>
                        <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cmoModal" onclick="openModal({{ $cmo }})">
                                Edit
                            </button>
                            <!-- Delete Form -->
                            <form action="{{ route('phead.cmos.destroy', $cmo->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                             <!-- View Prospectus Button -->
                                <a href="{{ route('prospectus.index', ['cmo_id' => $cmo->id]) }}" class="btn btn-info btn-sm">
                                    View Prospectus
                                </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- CMO Modal -->
<div class="modal fade" id="cmoModal" tabindex="-1" aria-labelledby="cmoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cmoForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cmoModalLabel">Add/Edit CMO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" value="POST" id="cmoMethod">
                    <div class="mb-3">
                        <label for="cmo_number" class="form-label">CMO Number</label>
                        <input type="text" class="form-control" id="cmo_number" name="cmo_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="year_issued" class="form-label">Year Issued</label>
                        <input type="number" class="form-control" id="year_issued" name="year_issued" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Open modal for adding or editing CMO
    function openModal(cmo = null) {
        const form = document.getElementById('cmoForm');
        const methodInput = document.getElementById('cmoMethod');
        const action = cmo ? `{{ url('phead/cmos') }}/${cmo.id}` : `{{ route('phead.cmos.store') }}`;

        form.action = action;
        document.getElementById('cmoModalLabel').innerText = cmo ? 'Edit CMO' : 'Add New CMO';
        
        // Set form method for editing
        if (cmo) {
            methodInput.value = 'PUT';
            document.getElementById('cmo_number').value = cmo.cmo_number;
            document.getElementById('description').value = cmo.description;
            document.getElementById('year_issued').value = cmo.year_issued;
        } else {
            methodInput.value = 'POST';
            form.reset();
        }
    }
</script>
@endpush
