<?php
// app/Http/Controllers/AppointmentController.php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $this->appointmentService = $appointmentService;
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $doctors = Doctor::where('is_active', true)->get();

        return view('appointment.create', compact('branches', 'doctors'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Check if the selected time slot is available
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
                ->with('error', 'The selected time slot is no longer available. Please choose another time.');
        }

        // Create the appointment with a default status of 'pending'
        $validated['status'] = 'pending';
        $appointment = Appointment::create($validated);

        // Send email confirmation to patient (commented out for now)
        // Mail::to($appointment->patient_email)->send(new AppointmentConfirmation($appointment));

        // Store appointment in session for confirmation page
        session(['appointment' => $appointment]);

        // Flash success message to the session
        return redirect()->route('appointment.success')
            ->with('success', 'Your appointment has been booked successfully.');
    }

    /**
     * Display appointment success page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function success()
    {
        // Check if coming from the appointment form
        if (!session('success') && !session('appointment')) {
            return redirect()->route('appointment.create');
        }

        return view('appointment.success');
    }


    // This is an addition to the existing AppointmentController.php

    /**
     * Export appointment as iCal file.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function exportIcal($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            // Check if user is authorized to export this appointment
            // For admin panel, check permission
            if (auth()->check()) {
                if (!auth()->user()->can('appointments_access')) {
                    abort(403, 'Unauthorized action.');
                }
            }
            // For frontend, check if the email matches
            else {
                // Verify using a token or hash to prevent unauthorized access
                $token = request('token');
                $validToken = hash('sha256', $appointment->id . $appointment->patient_email . env('APP_KEY'));

                if ($token !== $validToken) {
                    abort(403, 'Invalid token.');
                }
            }

            $calendarService = app(CalendarService::class);
            $icalContent = $calendarService->generateICalForAppointment($id);

            // Return as a downloadable file
            return response($icalContent)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="appointment_' . $id . '.ics"');
        } catch (\Exception $e) {
            if (auth()->check()) {
                return redirect()->back()->with('error', 'Error exporting appointment: ' . $e->getMessage());
            } else {
                abort(404, 'Appointment not found.');
            }
        }
    }
}