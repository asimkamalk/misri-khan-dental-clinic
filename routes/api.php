<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Appointment Time Slots
Route::get('/appointments/time-slots', [AppointmentController::class, 'getTimeSlots'])
    ->name('api.appointments.time-slots');

// Doctors by Branch
Route::get('/doctors/by-branch', [AppointmentController::class, 'getDoctorsByBranch'])
    ->name('api.doctors.by-branch');

// Calendar Appointments
Route::get('/appointments/calendar', [AppointmentController::class, 'getCalendarAppointments'])
    ->name('api.appointments.calendar');