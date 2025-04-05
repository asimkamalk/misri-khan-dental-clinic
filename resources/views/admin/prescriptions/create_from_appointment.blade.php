{{-- resources/views/admin/prescriptions/create_from_appointment.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Prescription for ' . $appointment->patient_name)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        .appointment-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .appointment-details h6 {
            margin-bottom: 10px;
            color: #4e73df;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Create Prescription from Appointment</h5>
        </div>
        <div class="card-body">
            <div class="appointment-details">
                <h6>Appointment Information</h6>
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Patient:</strong> {{ $appointment->patient_name }}</p>
                        <p><strong>Contact:</strong> {{ $appointment->patient_phone }}</p>
                        <p><strong>Email:</strong> {{ $appointment->patient_email }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                        <p><strong>Time:</strong>
                            {{ Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        <p><strong>Status:</strong> <span
                                class="badge badge-{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Branch:</strong> {{ $appointment->branch->name }}</p>
                        <p><strong>Doctor:</strong> {{ $appointment->doctor ? $appointment->doctor->name : 'Not Assigned' }}
                        </p>
                        @if ($appointment->notes)
                            <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.prescriptions.store') }}" method="POST">
                @csrf

                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                <input type="hidden" name="branch_id" value="{{ $appointment->branch_id }}">
                <input type="hidden" name="patient_name" value="{{ $appointment->patient_name }}">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="doctor_id" class="form-label required">Doctor</label>
                        <select class="form-select select2 @error('doctor_id') is-invalid @enderror" id="doctor_id"
                            name="doctor_id" required>
                            <option value="">Select Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} - {{ $doctor->specialization }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="patient_age" class="form-label">Age</label>
                        <input type="number" class="form-control @error('patient_age') is-invalid @enderror"
                            id="patient_age" name="patient_age" value="{{ old('patient_age') }}" min="0"
                            max="150">
                        @error('patient_age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="patient_gender" class="form-label">Gender</label>
                        <select class="form-select @error('patient_gender') is-invalid @enderror" id="patient_gender"
                            name="patient_gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('patient_gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('patient_gender') == 'female' ? 'selected' : '' }}>Female
                            </option>
                            <option value="other" {{ old('patient_gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('patient_gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="diagnosis" class="form-label required">Diagnosis</label>
                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" id="diagnosis" name="diagnosis" rows="3"
                        required>{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="treatment" class="form-label required">Treatment</label>
                    <textarea class="form-control @error('treatment') is-invalid @enderror" id="treatment" name="treatment" rows="4"
                        required>{{ old('treatment') }}</textarea>
                    @error('treatment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="medications" class="form-label">Medications</label>
                    <textarea class="form-control @error('medications') is-invalid @enderror" id="medications" name="medications"
                        rows="4">{{ old('medications') }}</textarea>
                    <small class="text-muted">Include name, dosage, and instructions for each medication</small>
                    @error('medications')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="followup_date" class="form-label">Follow-up Date</label>
                        <input type="date" class="form-control @error('followup_date') is-invalid @enderror"
                            id="followup_date" name="followup_date" value="{{ old('followup_date') }}">
                        @error('followup_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
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
        });
    </script>
@endpush
