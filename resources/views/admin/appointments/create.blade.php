{{-- resources/views/admin/appointments/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Book New Appointment')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Book New Appointment</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.appointments.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="branch_id" class="form-label required">Branch</label>
                        <select class="form-select select2 @error('branch_id') is-invalid @enderror" id="branch_id"
                            name="branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="doctor_id" class="form-label">Doctor (Optional)</label>
                        <select class="form-select select2 @error('doctor_id') is-invalid @enderror" id="doctor_id"
                            name="doctor_id">
                            <option value="">Any Available Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} - {{ $doctor->specialization }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="patient_name" class="form-label required">Patient Name</label>
                        <input type="text" class="form-control @error('patient_name') is-invalid @enderror"
                            id="patient_name" name="patient_name" value="{{ old('patient_name') }}" required>
                        @error('patient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="patient_email" class="form-label required">Email Address</label>
                        <input type="email" class="form-control @error('patient_email') is-invalid @enderror"
                            id="patient_email" name="patient_email" value="{{ old('patient_email') }}" required>
                        @error('patient_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="patient_phone" class="form-label required">Phone Number</label>
                        <input type="text" class="form-control @error('patient_phone') is-invalid @enderror"
                            id="patient_phone" name="patient_phone" value="{{ old('patient_phone') }}" required>
                        @error('patient_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="appointment_date" class="form-label required">Appointment Date</label>
                        <input type="date" class="form-control @error('appointment_date') is-invalid @enderror"
                            id="appointment_date" name="appointment_date"
                            value="{{ old('appointment_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="appointment_time" class="form-label required">Appointment Time</label>
                        <select class="form-select @error('appointment_time') is-invalid @enderror" id="appointment_time"
                            name="appointment_time" required disabled>
                            <option value="">First select branch and date</option>
                        </select>
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : 'selected' }}>Pending
                            </option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    <small class="text-muted">Include any special instructions or patient concerns</small>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Available time slots
            function loadTimeSlots() {
                const branchId = $('#branch_id').val();
                const doctorId = $('#doctor_id').val();
                const appointmentDate = $('#appointment_date').val();

                if (!branchId || !appointmentDate) {
                    $('#appointment_time').html('<option value="">First select branch and date</option>');
                    $('#appointment_time').prop('disabled', true);
                    return;
                }

                // Show loading
                $('#appointment_time').html('<option value="">Loading available times...</option>');

                // Get available time slots from server
                $.ajax({
                    url: '{{ route('api.appointments.time-slots') }}',
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        doctor_id: doctorId,
                        date: appointmentDate
                    },
                    success: function(response) {
                        let options = '<option value="">Select Time</option>';

                        if (response.available_slots && response.available_slots.length > 0) {
                            response.available_slots.forEach(function(slot) {
                                options += `<option value="${slot}">${slot}</option>`;
                            });
                            $('#appointment_time').prop('disabled', false);
                        } else {
                            options = '<option value="">No time slots available</option>';
                            $('#appointment_time').prop('disabled', true);
                        }

                        $('#appointment_time').html(options);

                        // Set selected value if it exists in old input
                        @if (old('appointment_time'))
                            $('#appointment_time').val('{{ old('appointment_time') }}');
                        @endif
                    },
                    error: function(xhr) {
                        console.error('Error loading time slots:', xhr);
                        $('#appointment_time').html(
                            '<option value="">Error loading time slots</option>');
                        $('#appointment_time').prop('disabled', true);
                    }
                });
            }

            // Load time slots when branch, doctor, or date changes
            $('#branch_id, #doctor_id, #appointment_date').change(function() {
                loadTimeSlots();
            });

            // Load time slots on page load if values are set
            if ($('#branch_id').val() && $('#appointment_date').val()) {
                loadTimeSlots();
            }

            // Filter doctors by branch
            $('#branch_id').change(function() {
                const branchId = $(this).val();

                if (!branchId) {
                    return;
                }

                $.ajax({
                    url: '{{ route('api.doctors.by-branch') }}',
                    type: 'GET',
                    data: {
                        branch_id: branchId
                    },
                    success: function(response) {
                        let options = '<option value="">Any Available Doctor</option>';

                        if (response.doctors && response.doctors.length > 0) {
                            response.doctors.forEach(function(doctor) {
                                options +=
                                    `<option value="${doctor.id}">${doctor.name} - ${doctor.specialization}</option>`;
                            });
                        }

                        $('#doctor_id').html(options);
                        $('#doctor_id').trigger('change');
                    },
                    error: function(xhr) {
                        console.error('Error loading doctors:', xhr);
                    }
                });
            });
        });
    </script>
@endpush
