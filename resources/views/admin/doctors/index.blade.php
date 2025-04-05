{{-- resources/views/admin/doctors/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Doctors')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Doctors</h5>
            @can('doctors_create')
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Doctor
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Photo</th>
                            <th width="20%">Name</th>
                            <th>Specialization</th>
                            <th>Branches</th>
                            <th width="10%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doctors as $doctor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($doctor->image)
                                        <img src="{{ asset('storage/' . $doctor->image) }}" alt="{{ $doctor->name }}"
                                            class="rounded-circle" width="60" height="60">
                                    @else
                                        <div class="text-center">
                                            <span class="avatar rounded-circle bg-primary text-white"
                                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                                {{ substr($doctor->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $doctor->name }}</strong>
                                    @if ($doctor->email)
                                        <div class="small text-muted">{{ $doctor->email }}</div>
                                    @endif
                                    @if ($doctor->phone)
                                        <div class="small text-muted">{{ $doctor->phone }}</div>
                                    @endif
                                </td>
                                <td>{{ $doctor->specialization }}</td>
                                <td>
                                    @foreach ($doctor->branches as $branch)
                                        <span class="badge bg-info mb-1">{{ $branch->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($doctor->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @can('doctors_edit')
                                            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endcan

                                        @can('doctors_delete')
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $doctor->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $doctor->id }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel{{ $doctor->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $doctor->id }}">
                                                                Confirm Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete doctor
                                                            <strong>{{ $doctor->name }}</strong>?
                                                            <p class="text-danger mt-2">This action cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('admin.doctors.destroy', $doctor) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No doctors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
