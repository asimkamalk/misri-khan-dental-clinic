{{-- resources/views/admin/prescriptions/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Prescription')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Prescription</h5>
            <div>
                <a href="{{ route('admin.prescriptions.print', $prescription) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-print"></i> Print Prescription
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.prescriptions.update', $prescription) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="branch_id" class="form-label required">Branch</label>
                        <select class="form-select select2 @error('branch_id') is-invalid @enderror" id="branch_id"
                            name="branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id', $prescription->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="doctor_id" class="form-label required">Doctor</label>
                        <select class="form-select select2 @error('doctor_id') is-invalid @enderror" id="doctor_id"
                            name="doctor_id" required>
                            <option value="">Select Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ old('doctor_id', $prescription->doctor_id) == $doctor->id ? 'selected' : '' }}>
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
                            id="patient_name" name="patient_name"
                            value="{{ old('patient_name', $prescription->patient_name) }}" required>
                        @error('patient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="patient_age" class="form-label">Age</label>
                        <input type="number" class="form-control @error('patient_age') is-invalid @enderror"
                            id="patient_age" name="patient_age"
                            value="{{ old('patient_age', $prescription->patient_age) }}" min="0" max="150">
                        @error('patient_age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="patient_gender" class="form-label">Gender</label>
                        <select class="form-select @error('patient_gender') is-invalid @enderror" id="patient_gender"
                            name="patient_gender">
                            <option value="">Select Gender</option>
                            <option value="male"
                                {{ old('patient_gender', $prescription->patient_gender) == 'male' ? 'selected' : '' }}>Male
                            </option>
                            <option value="female"
                                {{ old('patient_gender', $prescription->patient_gender) == 'female' ? 'selected' : '' }}>
                                Female</option>
                            <option value="other"
                                {{ old('patient_gender', $prescription->patient_gender) == 'other' ? 'selected' : '' }}>
                                Other</option>
                        </select>
                        @error('patient_gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="diagnosis" class="form-label required">Diagnosis</label>
                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" id="diagnosis" name="diagnosis" rows="3"
                        required>{{ old('diagnosis', $prescription->diagnosis) }}</textarea>
                    @error('diagnosis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="treatment" class="form-label required">Treatment</label>
                    <textarea class="form-control @error('treatment') is-invalid @enderror" id="treatment" name="treatment" rows="4"
                        required>{{ old('treatment', $prescription->treatment) }}</textarea>
                    @error('treatment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="medications" class="form-label">Medications</label>
                    <textarea class="form-control @error('medications') is-invalid @enderror" id="medications" name="medications"
                        rows="4">{{ old('medications', $prescription->medications) }}</textarea>
                    <small class="text-muted">Include name, dosage, and instructions for each medication</small>
                    @error('medications')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="followup_date" class="form-label">Follow-up Date</label>
                        <input type="date" class="form-control @error('followup_date') is-invalid @enderror"
                            id="followup_date" name="followup_date"
                            value="{{ old('followup_date', $prescription->followup_date ? $prescription->followup_date->format('Y-m-d') : '') }}">
                        @error('followup_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $prescription->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Prescription</button>
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
                        let options = '<option value="">Select Doctor</option>';
                        let currentDoctorId = '{{ $prescription->doctor_id }}';

                        if (response.doctors && response.doctors.length > 0) {
                            response.doctors.forEach(function(doctor) {
                                const selected = doctor.id == currentDoctorId ?
                                    'selected' : '';
                                options +=
                                    `<option value="${doctor.id}" ${selected}>${doctor.name} - ${doctor.specialization}</option>`;
                            });
                        }

                        $('#doctor_id').html(options);
                    },
                    error: function(xhr) {
                        console.error('Error loading doctors:', xhr);
                    }
                });
            });
        });
    </script>
@endpush
