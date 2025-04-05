<?php
// app/Http/Controllers/Admin/AppointmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
// use App\Mail\AppointmentStatusUpdated;
// use App\Mail\AppointmentConfirmation;

class AppointmentController extends Controller
{
    protected $appointmentService;

    /**
     * Create a new controller instance.
     *
     * @param AppointmentService $appointmentService
     * @return void
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->middleware('auth');
        $this->middleware('permission:appointments_access');
        $this->middleware('permission:appointments_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:appointments_edit', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:appointments_delete', ['only' => ['destroy']]);

        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the appointments.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        // Get filters
        $filters = [
            'branch_id' => $request->branch_id,
            'doctor_id' => $request->doctor_id,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        // Build query
        $query = Appointment::with(['branch', 'doctor']);

        // Apply filters
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', Carbon::parse($request->date_to));
        }

        // Get appointments
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments', 'branches', 'doctors', 'filters'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        return view('admin.appointments.create', compact('branches', 'doctors'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // Check if the time slot is available
        if (
            !$this->appointmentService->isTimeSlotAvailable(
                $request->appointment_date,
                $request->appointment_time,
                $request->branch_id,
                $request->doctor_id
            )
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The selected time slot is not available. Please choose another time.');
        }

        // Create the appointment
        $appointment = Appointment::create([
            'branch_id' => $request->branch_id,
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name,
            'patient_email' => $request->patient_email,
            'patient_phone' => $request->patient_phone,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        // Send appointment confirmation email (commented out for now)
        // if ($appointment->status === 'confirmed') {
        //     Mail::to($appointment->patient_email)->send(new AppointmentConfirmation($appointment));
        // }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\View\View
     */
    public function edit(Appointment $appointment)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        return view('admin.appointments.edit', compact('appointment', 'branches', 'doctors'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validate the request
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // Check if the appointment date or time has changed
        $timeChanged = $appointment->appointment_date != $request->appointment_date ||
            $appointment->appointment_time != $request->appointment_time ||
            $appointment->branch_id != $request->branch_id ||
            $appointment->doctor_id != $request->doctor_id;

        // Check if the time slot is available for the new time
        if (
            $timeChanged && !$this->appointmentService->isTimeSlotAvailable(
                $request->appointment_date,
                $request->appointment_time,
                $request->branch_id,
                $request->doctor_id,
                $appointment->id
            )
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The selected time slot is not available. Please choose another time.');
        }

        // Save the old status for comparison
        $oldStatus = $appointment->status;

        // Update the appointment
        $appointment->update([
            'branch_id' => $request->branch_id,
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name,
            'patient_email' => $request->patient_email,
            'patient_phone' => $request->patient_phone,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        // Send status update email if status has changed (commented out for now)
        // if ($oldStatus !== $request->status) {
        //     Mail::to($appointment->patient_email)->send(new AppointmentStatusUpdated($appointment));
        // }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Update appointment status.
     *
     * @param  Request  $request
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        // Save the old status for comparison
        $oldStatus = $appointment->status;

        // Update the status
        $appointment->status = $request->status;
        $appointment->save();

        // Send status update email if status has changed (commented out for now)
        // if ($oldStatus !== $request->status) {
        //     Mail::to($appointment->patient_email)->send(new AppointmentStatusUpdated($appointment));
        // }

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully.',
        ]);
    }

    /**
     * Display appointment calendar view.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function calendar(Request $request)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        $branch_id = $request->input('branch_id');
        $doctor_id = $request->input('doctor_id');

        return view('admin.appointments.calendar', compact('branches', 'doctors', 'branch_id', 'doctor_id'));
    }

    /**
     * Get appointments for calendar.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarAppointments(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $branch_id = $request->input('branch_id');
        $doctor_id = $request->input('doctor_id');

        $query = Appointment::with(['branch', 'doctor'])
            ->whereBetween('appointment_date', [$start, $end]);

        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        }

        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }

        $appointments = $query->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $backgroundColor = '#3788d8'; // default - pending

            if ($appointment->status === 'confirmed') {
                $backgroundColor = '#4e73df';
            } elseif ($appointment->status === 'completed') {
                $backgroundColor = '#1cc88a';
            } elseif ($appointment->status === 'cancelled') {
                $backgroundColor = '#e74a3b';
            }

            $doctorName = $appointment->doctor ? ' with Dr. ' . $appointment->doctor->name : '';

            $events[] = [
                'id' => $appointment->id,
                'title' => $appointment->patient_name . $doctorName,
                'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $backgroundColor,
                'extendedProps' => [
                    'patient_email' => $appointment->patient_email,
                    'patient_phone' => $appointment->patient_phone,
                    'branch' => $appointment->branch->name,
                    'doctor' => $appointment->doctor ? $appointment->doctor->name : 'Not Assigned',
                    'status' => $appointment->status,
                    'notes' => $appointment->notes,
                ]
            ];
        }

        return response()->json($events);
    }
}