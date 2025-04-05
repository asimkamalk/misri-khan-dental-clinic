{{-- resources/views/admin/prescriptions/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'View Prescription')

@push('styles')
    <style>
        .prescription-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .prescription-footer {
            border-top: 1px solid #e3e6f0;
            padding-top: 15px;
            margin-top: 20px;
        }

        .prescription-section {
            margin-bottom: 20px;
        }

        .prescription-section h6 {
            color: #4e73df;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .prescription-meta {
            background-color: #f8f9fc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .prescription-meta p {
            margin-bottom: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Prescription Details</h5>
            <div>
                @can('prescriptions_edit')
                    <a href="{{ route('admin.prescriptions.edit', $prescription) }}" class="btn btn-info me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endcan

                <div class="dropdown d-inline-block">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="prescriptionActions"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="prescriptionActions">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.prescriptions.print', $prescription) }}"
                                target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.prescriptions.download', $prescription) }}">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </li>
                        @if ($prescription->appointment && $prescription->appointment->patient_email)
                            <li>
                                <form action="{{ route('admin.prescriptions.email', $prescription) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-envelope"></i> Email to Patient
                                    </button>
                                </form>
                            </li>
                        @endif
                        @can('prescriptions_edit')
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.prescriptions.edit', $prescription) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="prescription-header">
                <div class="row">
                    <div class="col-md-4">
                        <h4>{{ $prescription->branch->name }}</h4>
                        <p>{{ $prescription->branch->address }}</p>
                        <p>Phone: {{ $prescription->branch->phone }}</p>
                        @if ($prescription->branch->email)
                            <p>Email: {{ $prescription->branch->email }}</p>
                        @endif
                    </div>

                    <div class="col-md-4 text-center">
                        <h3 class="text-primary">Medical Prescription</h3>
                        <p>Date: {{ $prescription->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="col-md-4 text-end">
                        <h4>Dr. {{ $prescription->doctor->name }}</h4>
                        <p>{{ $prescription->doctor->specialization }}</p>
                        @if ($prescription->doctor->email)
                            <p>{{ $prescription->doctor->email }}</p>
                        @endif
                        @if ($prescription->doctor->phone)
                            <p>{{ $prescription->doctor->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="prescription-meta">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Patient Name:</strong> {{ $prescription->patient_name }}</p>
                        @if ($prescription->patient_age)
                            <p><strong>Age:</strong> {{ $prescription->patient_age }} years</p>
                        @endif
                        @if ($prescription->patient_gender)
                            <p><strong>Gender:</strong> {{ ucfirst($prescription->patient_gender) }}</p>
                        @endif
                    </div>

                    <div class="col-md-4">
                        @if ($prescription->appointment)
                            <p><strong>Appointment Date:</strong>
                                {{ $prescription->appointment->appointment_date->format('M d, Y') }}</p>
                            <p><strong>Appointment Time:</strong>
                                {{ Carbon\Carbon::parse($prescription->appointment->appointment_time)->format('h:i A') }}
                            </p>
                        @endif
                    </div>

                    <div class="col-md-4 text-end">
                        <p><strong>Prescription ID:</strong> #{{ $prescription->id }}</p>
                        <p><strong>Created:</strong> {{ $prescription->created_at->format('M d, Y h:i A') }}</p>
                        @if ($prescription->followup_date)
                            <p><strong>Follow-up Date:</strong> {{ $prescription->followup_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="prescription-section">
                <h6>Diagnosis</h6>
                <p>{{ $prescription->diagnosis }}</p>
            </div>

            <div class="prescription-section">
                <h6>Treatment</h6>
                <p>{!! nl2br(e($prescription->treatment)) !!}</p>
            </div>

            @if ($prescription->medications)
                <div class="prescription-section">
                    <h6>Medications</h6>
                    <p>{!! nl2br(e($prescription->medications)) !!}</p>
                </div>
            @endif

            @if ($prescription->notes)
                <div class="prescription-section">
                    <h6>Additional Notes</h6>
                    <p>{!! nl2br(e($prescription->notes)) !!}</p>
                </div>
            @endif

            <div class="prescription-footer">
                <div class="row">
                    <div class="col-md-8">
                        @if ($prescription->followup_date)
                            <p><strong>Please Return For Follow-up:</strong>
                                {{ $prescription->followup_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-0">Dr. {{ $prescription->doctor->name }}</p>
                        <p>{{ $prescription->doctor->specialization }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
