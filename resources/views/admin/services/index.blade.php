{{-- resources/views/admin/services/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Services')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <style>
        .service-item {
            cursor: pointer;
        }

        .service-item:hover {
            background-color: #f8f9fa;
        }

        .ui-sortable-helper {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .service-image {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }

        .sortable-placeholder {
            height: 80px;
            background-color: #f8f9fa;
            border: 2px dashed #ccc;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Services</h5>
            <div>
                @can('services_edit')
                    <button id="saveOrder" class="btn btn-success me-2 d-none">
                        <i class="fas fa-save"></i> Save Order
                    </button>
                @endcan

                @can('services_create')
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Service
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if (count($services) > 0)
                <p class="text-muted mb-3">
                    <i class="fas fa-info-circle"></i> Drag and drop the services to change their display order.
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Image</th>
                                <th width="20%">Name</th>
                                <th>Short Description</th>
                                <th width="10%">Status</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-services">
                            @foreach ($services as $service)
                                <tr class="service-item" data-id="{{ $service->id }}">
                                    <td>{{ $service->display_order }}</td>
                                    <td>
                                        @if ($service->image)
                                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                                class="service-image">
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ Str::limit($service->short_description, 100) }}</td>
                                    <td>
                                        @if ($service->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @can('services_edit')
                                                <a href="{{ route('admin.services.edit', $service) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endcan

                                            @can('services_delete')
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $service->id }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $service->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel{{ $service->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deleteModalLabel{{ $service->id }}">Confirm Delete
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the service
                                                                <strong>{{ $service->name }}</strong>?
                                                                <p class="text-danger mt-2">This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('admin.services.destroy', $service) }}"
                                                                    method="POST" class="d-inline">
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
            @else
                <div class="alert alert-info">
                    No services found. Please add a service to get started.
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            let orderChanged = false;

            @can('services_edit')
                // Make the table rows sortable
                $("#sortable-services").sortable({
                    items: "tr",
                    cursor: "move",
                    handle: "td:first-child",
                    placeholder: "sortable-placeholder",
                    update: function(event, ui) {
                        orderChanged = true;
                        $("#saveOrder").removeClass("d-none");
                    }
                });

                // Save the new order
                $("#saveOrder").click(function() {
                    const items = [];

                    $("#sortable-services tr").each(function(index) {
                        items.push($(this).data("id"));
                        $(this).find("td:first-child").text(index + 1);
                    });

                    $.ajax({
                        url: "{{ route('admin.services.reorder') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            items: items
                        },
                        success: function(response) {
                            if (response.success) {
                                orderChanged = false;
                                $("#saveOrder").addClass("d-none");

                                // Show success message
                                const alert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Service order updated successfully.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;

                                $(".card-body").prepend(alert);

                                // Auto dismiss the alert after 3 seconds
                                setTimeout(function() {
                                    $(".alert").alert('close');
                                }, 3000);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);

                            // Show error message
                            const alert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            An error occurred while updating the service order.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                            $(".card-body").prepend(alert);
                        }
                    });
                });

                // Warn about unsaved changes when leaving the page
                $(window).on('beforeunload', function() {
                    if (orderChanged) {
                        return "You have unsaved changes. Are you sure you want to leave?";
                    }
                });
            @endcan
        });
    </script>
@endpush
