{{-- resources/views/admin/branches/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Branches')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Branches</h5>
            @can('branches_create')
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Branch
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Hours</th>
                            <th>Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branches as $branch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->address }}</td>
                                <td>
                                    <strong>Phone:</strong> {{ $branch->phone }}<br>
                                    @if ($branch->email)
                                        <strong>Email:</strong> {{ $branch->email }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</td>
                                <td>
                                    @if ($branch->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @can('branches_edit')
                                            <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endcan

                                        @can('branches_delete')
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $branch->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $branch->id }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel{{ $branch->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $branch->id }}">
                                                                Confirm Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete the branch
                                                            <strong>{{ $branch->name }}</strong>?
                                                            <p class="text-danger mt-2">This action cannot be undone and will
                                                                remove all records associated with this branch.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('admin.branches.destroy', $branch) }}"
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
                                <td colspan="7" class="text-center">No branches found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
