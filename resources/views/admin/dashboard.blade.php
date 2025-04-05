{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <!-- Total Appointments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $stats['total_appointments'] }}</div>
                            <div class="stats-text">Total Appointments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon success">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $stats['today_appointments'] }}</div>
                            <div class="stats-text">Today's Appointments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Appointments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $stats['pending_appointments'] }}</div>
                            <div class="stats-text">Pending Appointments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Prescriptions -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon info">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $stats['total_prescriptions'] }}</div>
                            <div class="stats-text">Total Prescriptions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Monthly Appointments Chart
            var ctx1 = document.getElementById('monthlyAppointmentsChart').getContext('2d');
            var monthlyChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyAppointments['labels']) !!},
                    datasets: [{
                        label: 'Number of Appointments',
                        data: {!! json_encode($monthlyAppointments['data']) !!},
                        backgroundColor: '#4e73df',
                        borderColor: '#3a5ccc',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Appointment Status Chart
            var ctx2 = document.getElementById('appointmentStatusChart').getContext('2d');
            var statusChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($appointmentStatuses['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($appointmentStatuses['data']) !!},
                        backgroundColor: {!! json_encode($appointmentStatuses['backgroundColors']) !!},
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush

    <div class="row">
        <!-- Monthly Appointments Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Monthly Appointments ({{ date('Y') }})</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyAppointmentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Status Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Appointment Status</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="appointmentStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Upcoming Appointments -->
        <div class="col-xl-6 col-lg-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Upcoming Appointments</h6>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date & Time</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->patient_name }}</td>
                                        <td>
                                            {{ $appointment->appointment_date->format('M d, Y') }} <br>
                                            <small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                        </td>
                                        <td>{{ $appointment->branch->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $appointment->status }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No upcoming appointments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="col-xl-6 col-lg-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Recent Appointments</h6>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date & Time</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->patient_name }}</td>
                                        <td>
                                            {{ $appointment->appointment_date->format('M d, Y') }} <br>
                                            <small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                        </td>
                                        <td>{{ $appointment->branch->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $appointment->status }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No recent appointments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Clinic Summary -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Clinic Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Branches</h5>
                                    <h2 class="text-center my-3">{{ $stats['total_branches'] }}</h2>
                                    <p class="card-text text-center">Active Clinic Branches</p>
                                    <div class="text-center">
                                        <a href="{{ route('admin.branches.index') }}" class="btn btn-sm btn-primary">Manage
                                            Branches</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Doctors</h5>
                                    <h2 class="text-center my-3">{{ $stats['total_doctors'] }}</h2>
                                    <p class="card-text text-center">Registered Doctors</p>
                                    <div class="text-center">
                                        <a href="{{ route('admin.doctors.index') }}"
                                            class="btn btn-sm btn-primary">Manage Doctors</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Services</h5>
                                    <h2 class="text-center my-3">{{ $stats['total_services'] }}</h2>
                                    <p class="card-text text-center">Available Services</p>
                                    <div class="text-center">
                                        <a href="{{ route('admin.services.index') }}"
                                            class="btn btn-sm btn-primary">Manage Services</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
