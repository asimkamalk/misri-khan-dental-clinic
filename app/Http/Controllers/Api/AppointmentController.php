<?php
// app/Http/Controllers/Api/AppointmentController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

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
     * Get available time slots for the specified date and branch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeSlots(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $branch_id = $request->branch_id;
        $date = $request->date;
        $doctor_id = $request->doctor_id;

        $available_slots = $this->appointmentService->getAvailableTimeSlots($date, $branch_id, $doctor_id);

        return response()->json([
            'success' => true,
            'available_slots' => $available_slots,
        ]);
    }

    /**
     * Get doctors by branch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsByBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branch_id = $request->branch_id;

        $doctors = Doctor::where('is_active', true)
            ->whereHas('branches', function ($query) use ($branch_id) {
                $query->where('branches.id', $branch_id);
            })
            ->select('id', 'name', 'specialization')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Get calendar appointments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarAppointments(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $branch_id = $request->input('branch_id');
        $doctor_id = $request->input('doctor_id');

        $appointments = $this->appointmentService->getAppointmentsByDateRange(
            $start,
            $end,
            $branch_id,
            $doctor_id
        );

        $events = [];

        foreach ($appointments as $appointment) {
            $backgroundColor = '#3788d8'; // Default color - pending

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