{{-- resources/views/admin/prescriptions/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Prescriptions')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Prescriptions</h5>
            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"
                aria-expanded="false" aria-controls="filterCollapse">
                <i class="fas fa-filter"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse {{ request()->hasAny(['branch_id', 'doctor_id', 'patient_name', 'date_from', 'date_to']) ? 'show' : '' }}"
            id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('admin.prescriptions.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id">
                                <option value="">All Doctors</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="patient_name" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name"
                                value="{{ request('patient_name') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary me-2">Clear</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Prescriptions</h5>
            @can('prescriptions_create')
                <a href="{{ route('admin.prescriptions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Prescription
                </a>
            @endcan
        </div>
        <div class="card-body">
            @if ($prescriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Patient Details</th>
                                <th>Doctor</th>
                                <th>Branch</th>
                                <th>Diagnosis</th>
                                <th>Date</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $prescription->patient_name }}</strong><br>
                                        @if ($prescription->patient_age)
                                            <small>Age: {{ $prescription->patient_age }} years</small><br>
                                        @endif
                                        @if ($prescription->patient_gender)
                                            <small>Gender: {{ ucfirst($prescription->patient_gender) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $prescription->doctor->name }}</td>
                                    <td>{{ $prescription->branch->name }}</td>
                                    <td>{{ Str::limit($prescription->diagnosis, 50) }}</td>
                                    <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.prescriptions.show', $prescription) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            <a href="{{ route('admin.prescriptions.print', $prescription) }}"
                                                class="btn btn-sm btn-success" target="_blank">
                                                <i class="fas fa-print"></i> Print
                                            </a>

                                            @can('prescriptions_edit')
                                                <a href="{{ route('admin.prescriptions.edit', $prescription) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endcan

                                            @can('prescriptions_delete')
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $prescription->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $prescription->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel{{ $prescription->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deleteModalLabel{{ $prescription->id }}">Confirm Delete
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this prescription for
                                                                <strong>{{ $prescription->patient_name }}</strong>?
                                                                <p class="text-danger mt-2">This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <form
                                                                    action="{{ route('admin.prescriptions.destroy', $prescription) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $prescriptions->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    No prescriptions found matching the specified criteria.
                </div>
            @endif
        </div>
    </div>
@endsection
