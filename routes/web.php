<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\PrescriptionController;
use App\Http\Controllers\Admin\PageManagementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/services/{id}', [HomeController::class, 'serviceDetail'])->name('service.detail');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Appointment Booking
Route::get('/appointment', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');
Route::get('/appointment/success', [AppointmentController::class, 'success'])->name('appointment.success');
Route::get('/appointment/{id}/export-ical', [AppointmentController::class, 'exportIcal'])->name('appointment.export-ical');

// Custom Pages
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Authentication Routes
Auth::routes(['register' => false, 'reset' => true, 'verify' => false]);

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'active'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Branches Management
    Route::resource('branches', BranchController::class);

    // Services Management
    Route::resource('services', ServiceController::class);
    Route::post('services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder');

    // Doctors Management
    Route::resource('doctors', DoctorController::class);
    Route::post('doctors/{doctor}/branches', [DoctorController::class, 'updateBranches'])->name('doctors.branches.update');

    // Appointments Management
    Route::resource('appointments', AdminAppointmentController::class);
    Route::get('appointments/calendar', [AdminAppointmentController::class, 'calendar'])->name('appointments.calendar');
    Route::post('appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.status.update');

    // Prescriptions Management
    Route::resource('prescriptions', PrescriptionController::class);
    Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescriptions.print');
    Route::get('prescriptions/{prescription}/download', [PrescriptionController::class, 'download'])->name('prescriptions.download');
    Route::post('prescriptions/{prescription}/email', [PrescriptionController::class, 'email'])->name('prescriptions.email');
    Route::get('prescriptions/create/{appointment?}', [PrescriptionController::class, 'create'])->name('prescriptions.create.from.appointment');

    // Content Management
    Route::resource('pages', PageManagementController::class);

    // Testimonials Management
    Route::resource('testimonials', TestimonialController::class);

    // Site Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // User Management (Admin Only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});

// Custom Error Pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});