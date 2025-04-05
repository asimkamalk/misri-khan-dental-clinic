{{-- resources/views/admin/appointments/calendar.blade.php --}}
@extends('layouts.admin')

@section('title', 'Appointment Calendar')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        #calendar {
            height: 700px;
        }

        .fc-event {
            cursor: pointer;
        }

        .appointment-details {
            margin-bottom: 15px;
        }

        .appointment-details dt {
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Calendar</h5>
            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"
                aria-expanded="false" aria-controls="filterCollapse">
                <i class="fas fa-filter"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form id="calendarFilter">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select select2" id="branch_id" name="branch_id">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select select2" id="doctor_id" name="doctor_id">
                                <option value="">All Doctors</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" id="resetFilters" class="btn btn-secondary me-2">Reset</button>
                        <button type="button" id="applyFilters" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Appointment Calendar</h5>
            <div>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-info me-2">
                    <i class="fas fa-list"></i> List View
                </a>
                @can('appointments_create')
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Appointment
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Appointment Details Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row appointment-details">
                        <dt class="col-sm-4">Patient:</dt>
                        <dd class="col-sm-8" id="modal-patient-name"></dd>

                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8" id="modal-patient-email"></dd>

                        <dt class="col-sm-4">Phone:</dt>
                        <dd class="col-sm-8" id="modal-patient-phone"></dd>

                        <dt class="col-sm-4">Date & Time:</dt>
                        <dd class="col-sm-8" id="modal-datetime"></dd>

                        <dt class="col-sm-4">Branch:</dt>
                        <dd class="col-sm-8" id="modal-branch"></dd>

                        <dt class="col-sm-4">Doctor:</dt>
                        <dd class="col-sm-8" id="modal-doctor"></dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <select class="form-select" id="modal-status">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </dd>
                    </dl>

                    <div class="mb-3">
                        <label for="modal-notes" class="form-label">Notes:</label>
                        <p id="modal-notes" class="border p-2 rounded"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="modal-appointment-id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" class="btn btn-info" id="modal-edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @can('appointments_edit')
                        <button type="button" class="btn btn-primary" id="modal-save-status">
                            <i class="fas fa-save"></i> Save Status
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Initialize FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: function(info, successCallback, failureCallback) {
                    const branch_id = $('#branch_id').val();
                    const doctor_id = $('#doctor_id').val();

                    $.ajax({
                        url: '{{ route('api.appointments.calendar') }}',
                        type: 'GET',
                        data: {
                            start: info.startStr,
                            end: info.endStr,
                            branch_id: branch_id,
                            doctor_id: doctor_id
                        },
                        success: function(response) {
                            successCallback(response);
                        },
                        error: function(xhr) {
                            console.error('Error loading appointments:', xhr);
                            failureCallback(xhr);
                        }
                    });
                },
                eventClick: function(info) {
                    showAppointmentModal(info.event);
                },
                navLinks: true,
                dayMaxEvents: true,
                height: 'auto',
            });

            calendar.render();

            // Show appointment details modal
            function showAppointmentModal(event) {
                const appointmentId = event.id;
                const title = event.title;
                const start = event.start;
                const props = event.extendedProps;

                $('#modal-appointment-id').val(appointmentId);
                $('#appointmentModalLabel').text(title);
                $('#modal-patient-name').text(title.split(' with Dr.')[0]);
                $('#modal-patient-email').text(props.patient_email);
                $('#modal-patient-phone').text(props.patient_phone);
                $('#modal-datetime').text(new Date(start).toLocaleString());
                $('#modal-branch').text(props.branch);
                $('#modal-doctor').text(props.doctor);
                $('#modal-status').val(props.status);
                $('#modal-notes').text(props.notes || 'No notes available');
                $('#modal-edit-btn').attr('href', `/admin/appointments/${appointmentId}/edit`);

                $('#appointmentModal').modal('show');
            }

            // Update appointment status
            $('#modal-save-status').click(function() {
                const appointmentId = $('#modal-appointment-id').val();
                const status = $('#modal-status').val();

                $.ajax({
                    url: `/admin/appointments/${appointmentId}/status`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            // Close modal
                            $('#appointmentModal').modal('hide');

                            // Refresh calendar
                            calendar.refetchEvents();

                            // Show success message
                            alert('Appointment status updated successfully');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating status:', xhr);
                        alert('Error updating appointment status');
                    }
                });
            });

            // Apply filters
            $('#applyFilters').click(function() {
                calendar.refetchEvents();
            });

            // Reset filters
            $('#resetFilters').click(function() {
                $('#branch_id').val('').trigger('change');
                $('#doctor_id').val('').trigger('change');
                calendar.refetchEvents();
            });
        });
    </script>
@endpush
