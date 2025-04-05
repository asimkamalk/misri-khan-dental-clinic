{{-- resources/views/appointment/create.blade.php --}}
@extends('layouts.frontend')

@section('title', 'Book Appointment')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        .branch-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .branch-card:hover {
            transform: translateY(-5px);
        }

        .branch-card.selected {
            border-color: var(--primary-color);
            background-color: rgba(26, 118, 209, 0.05);
        }

        .doctor-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
        }

        .doctor-card.selected {
            border-color: var(--primary-color);
            background-color: rgba(26, 118, 209, 0.05);
        }

        .time-slot {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            background-color: #f5f5f5;
        }

        .time-slot.selected {
            background-color: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .time-slot.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f5f5f5;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="background-image: url('{{ asset('images/appointment-bg.jpg') }}');">
        <div class="container">
            <h1 data-aos="fade-up">Book an Appointment</h1>
            <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Appointment</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Appointment Section -->
    <section class="section appointment-page">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Book Your Dental Appointment</h2>
                <p>Complete the form below to schedule your visit</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('appointment.store') }}" method="POST" id="appointmentForm">
                @csrf

                <!-- Step 1: Select Branch -->
                <div class="branch-selection" data-aos="fade-up">
                    <h3>Step 1: Select a Branch</h3>
                    <div class="row">
                        @foreach ($branches as $branch)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="branch-card card h-100 p-3 {{ old('branch_id') == $branch->id ? 'selected' : '' }}"
                                    data-branch-id="{{ $branch->id }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $branch->name }}</h5>
                                        <p class="card-text">{{ $branch->address }}</p>
                                        <p class="card-text"><small
                                                class="text-muted">{{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }}
                                                -
                                                {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}</small>
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-0">
                                        <div class="form-check">
                                            <input class="form-check-input branch-radio" type="radio" name="branch_id"
                                                id="branch{{ $branch->id }}" value="{{ $branch->id }}"
                                                {{ old('branch_id') == $branch->id ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="branch{{ $branch->id }}">
                                                Select this branch
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('branch_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Step 2: Select Doctor (Optional) -->
                <div class="doctor-selection mt-4" data-aos="fade-up">
                    <h3>Step 2: Select a Doctor (Optional)</h3>
                    <p class="text-muted">You can select a specific doctor or choose "Any Available Doctor"</p>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="doctor_option" id="anyDoctor" value="any"
                            {{ old('doctor_id') ? '' : 'checked' }}>
                        <label class="form-check-label" for="anyDoctor">
                            Any Available Doctor
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="doctor_option" id="specificDoctor"
                            value="specific" {{ old('doctor_id') ? 'checked' : '' }}>
                        <label class="form-check-label" for="specificDoctor">
                            Select a Specific Doctor
                        </label>
                    </div>

                    <div id="doctorSelectionContainer" class="mt-3 {{ old('doctor_id') ? '' : 'd-none' }}">
                        <div class="row" id="doctorList">
                            @foreach ($doctors as $doctor)
                                <div class="col-lg-6 mb-3 doctor-item"
                                    data-branches="{{ json_encode($doctor->branches->pluck('id')) }}">
                                    <div class="doctor-card d-flex align-items-center p-3 {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}"
                                        data-doctor-id="{{ $doctor->id }}">
                                        <div class="doctor-img me-3">
                                            @if ($doctor->image)
                                                <img src="{{ asset('storage/' . $doctor->image) }}"
                                                    alt="{{ $doctor->name }}" class="rounded-circle" width="60"
                                                    height="60">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 60px;">
                                                    <span class="fs-4">{{ substr($doctor->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="doctor-info">
                                            <h5 class="mb-1">Dr. {{ $doctor->name }}</h5>
                                            <p class="mb-0 text-muted">{{ $doctor->specialization }}</p>
                                        </div>
                                        <div class="ms-auto">
                                            <input type="radio" class="form-check-input doctor-radio" name="doctor_id"
                                                id="doctor{{ $doctor->id }}" value="{{ $doctor->id }}"
                                                {{ old('doctor_id') == $doctor->id ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('doctor_id')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Step 3: Select Date and Time -->
                <div class="appointment-form mt-4" data-aos="fade-up">
                    <h3>Step 3: Select Date and Time</h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="appointment_date" class="form-label">Appointment Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('appointment_date') is-invalid @enderror"
                                id="appointment_date" name="appointment_date"
                                value="{{ old('appointment_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Available Time Slots <span class="text-danger">*</span></label>
                        <div id="timeSlots" class="mt-2">
                            <p class="text-muted">Please select a branch and date to view available time slots</p>
                        </div>
                        <input type="hidden" id="appointment_time" name="appointment_time"
                            value="{{ old('appointment_time') }}">
                        @error('appointment_time')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Step 4: Your Information -->
                <div class="appointment-form mt-4" data-aos="fade-up">
                    <h3>Step 4: Your Information</h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patient_name" class="form-label">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('patient_name') is-invalid @enderror"
                                id="patient_name" name="patient_name" value="{{ old('patient_name') }}" required>
                            @error('patient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="patient_phone" class="form-label">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('patient_phone') is-invalid @enderror"
                                id="patient_phone" name="patient_phone" value="{{ old('patient_phone') }}" required>
                            @error('patient_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="patient_email" class="form-label">Email Address <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('patient_email') is-invalid @enderror"
                            id="patient_email" name="patient_email" value="{{ old('patient_email') }}" required>
                        @error('patient_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        <small class="text-muted">Please include any specific concerns or information you'd like us to know
                            before your appointment.</small>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Confirm Appointment</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
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

            // Branch selection
            $('.branch-card').click(function() {
                const branchId = $(this).data('branch-id');
                $('.branch-card').removeClass('selected');
                $(this).addClass('selected');
                $(`#branch${branchId}`).prop('checked', true);

                // Filter doctors by branch
                filterDoctorsByBranch(branchId);

                // Load time slots
                loadTimeSlots();
            });

            // Doctor selection option
            $('input[name="doctor_option"]').change(function() {
                if ($(this).val() === 'specific') {
                    $('#doctorSelectionContainer').removeClass('d-none');
                    // Enable the first visible doctor by default if none is selected
                    if ($('.doctor-radio:checked').length === 0) {
                        $('.doctor-item:visible:first .doctor-radio').prop('checked', true);
                        $('.doctor-item:visible:first .doctor-card').addClass('selected');
                    }
                } else {
                    $('#doctorSelectionContainer').addClass('d-none');
                    $('.doctor-radio').prop('checked', false);
                    $('.doctor-card').removeClass('selected');
                }

                // Load time slots
                loadTimeSlots();
            });

            // Doctor selection
            $('.doctor-card').click(function() {
                const doctorId = $(this).data('doctor-id');
                $('.doctor-card').removeClass('selected');
                $(this).addClass('selected');
                $(`#doctor${doctorId}`).prop('checked', true);

                // Load time slots
                loadTimeSlots();
            });

            // Date selection
            $('#appointment_date').change(function() {
                loadTimeSlots();
            });

            // Filter doctors by branch
            function filterDoctorsByBranch(branchId) {
                if (!branchId) return;

                $('.doctor-item').each(function() {
                    const doctorBranches = $(this).data('branches');
                    if (doctorBranches.includes(parseInt(branchId))) {
                        $(this).show();
                    } else {
                        $(this).hide();
                        // Unselect if currently selected
                        if ($(this).find('.doctor-radio').is(':checked')) {
                            $(this).find('.doctor-radio').prop('checked', false);
                            $(this).find('.doctor-card').removeClass('selected');
                        }
                    }
                });

                // If no doctors are visible for this branch
                if ($('.doctor-item:visible').length === 0) {
                    $('#specificDoctor').prop('disabled', true);
                    $('#anyDoctor').prop('checked', true);
                    $('#doctorSelectionContainer').addClass('d-none');
                } else {
                    $('#specificDoctor').prop('disabled', false);
                }
            }

            // Load time slots
            function loadTimeSlots() {
                const branchId = $('input[name="branch_id"]:checked').val();
                const date = $('#appointment_date').val();
                let doctorId = null;

                if ($('input[name="doctor_option"]:checked').val() === 'specific') {
                    doctorId = $('input[name="doctor_id"]:checked').val();
                }

                if (!branchId || !date) {
                    $('#timeSlots').html(
                        '<p class="text-muted">Please select a branch and date to view available time slots</p>'
                        );
                    return;
                }

                // Show loading
                $('#timeSlots').html('<p>Loading available time slots...</p>');

                // Get available time slots from server
                $.ajax({
                    url: '{{ route('api.appointments.time-slots') }}',
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        doctor_id: doctorId,
                        date: date
                    },
                    success: function(response) {
                        let html = '';

                        if (response.available_slots && response.available_slots.length > 0) {
                            html = '<div>';
                            response.available_slots.forEach(function(slot) {
                                const isSelected = slot === '{{ old('appointment_time') }}';
                                html +=
                                    `<div class="time-slot ${isSelected ? 'selected' : ''}" data-time="${slot}">${slot}</div>`;
                            });
                            html += '</div>';
                        } else {
                            html =
                                '<p class="text-danger">No time slots available for the selected date. Please choose another date.</p>';
                        }

                        $('#timeSlots').html(html);

                        // Time slot selection
                        $('.time-slot').click(function() {
                            $('.time-slot').removeClass('selected');
                            $(this).addClass('selected');
                            $('#appointment_time').val($(this).data('time'));
                        });

                        // Set selected value if it exists in old input
                        if ('{{ old('appointment_time') }}') {
                            $('#appointment_time').val('{{ old('appointment_time') }}');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading time slots:', xhr);
                        $('#timeSlots').html(
                            '<p class="text-danger">Error loading time slots. Please try again.</p>'
                            );
                    }
                });
            }

            // Initialize based on URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const branchIdParam = urlParams.get('branch_id');

            if (branchIdParam) {
                $(`.branch-card[data-branch-id="${branchIdParam}"]`).click();
            }

            // Initial load based on preselected values
            if ($('input[name="branch_id"]:checked').val()) {
                const branchId = $('input[name="branch_id"]:checked').val();
                filterDoctorsByBranch(branchId);

                if ($('input[name="doctor_id"]:checked').val()) {
                    $('.doctor-card').removeClass('selected');
                    $(`.doctor-card[data-doctor-id="${$('input[name="doctor_id"]:checked').val()}"]`).addClass(
                        'selected');
                    $('#specificDoctor').prop('checked', true);
                    $('#doctorSelectionContainer').removeClass('d-none');
                }

                loadTimeSlots();
            }

            // Form validation
            $('#appointmentForm').submit(function(e) {
                if (!$('input[name="branch_id"]:checked').val()) {
                    alert('Please select a branch');
                    e.preventDefault();
                    return false;
                }

                if (!$('#appointment_date').val()) {
                    alert('Please select an appointment date');
                    e.preventDefault();
                    return false;
                }

                if (!$('#appointment_time').val()) {
                    alert('Please select an appointment time');
                    e.preventDefault();
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
