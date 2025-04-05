<?php
// app/Services/PrescriptionService.php

namespace App\Services;

use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use PDF;

class PrescriptionService
{
    /**
     * Create a new prescription from appointment.
     *
     * @param int $appointmentId
     * @param array $data
     * @return Prescription
     */
    public function createFromAppointment(int $appointmentId, array $data): Prescription
    {
        $appointment = Appointment::with(['branch', 'doctor'])->findOrFail($appointmentId);

        // Create prescription with data from appointment
        $prescription = new Prescription();
        $prescription->appointment_id = $appointmentId;
        $prescription->doctor_id = $appointment->doctor_id ?? $data['doctor_id'];
        $prescription->branch_id = $appointment->branch_id;
        $prescription->patient_name = $appointment->patient_name;
        $prescription->patient_age = $data['patient_age'] ?? null;
        $prescription->patient_gender = $data['patient_gender'] ?? null;
        $prescription->diagnosis = $data['diagnosis'];
        $prescription->treatment = $data['treatment'];
        $prescription->medications = $data['medications'] ?? null;
        $prescription->notes = $data['notes'] ?? null;
        $prescription->followup_date = $data['followup_date'] ?? null;
        $prescription->save();

        // Update appointment status to completed
        $appointment->status = 'completed';
        $appointment->save();

        return $prescription;
    }

    /**
     * Create a new prescription without appointment.
     *
     * @param array $data
     * @return Prescription
     */
    public function createWithoutAppointment(array $data): Prescription
    {
        $prescription = Prescription::create($data);

        return $prescription;
    }

    /**
     * Generate PDF for prescription.
     *
     * @param int $prescriptionId
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(int $prescriptionId)
    {
        $prescription = Prescription::with(['branch', 'doctor', 'appointment'])->findOrFail($prescriptionId);

        // Get clinic settings
        $clinicName = $prescription->branch->name;
        $clinicAddress = $prescription->branch->address;
        $clinicPhone = $prescription->branch->phone;

        // Generate PDF
        $pdf = PDF::loadView('admin.prescriptions.pdf', [
            'prescription' => $prescription,
            'clinicName' => $clinicName,
            'clinicAddress' => $clinicAddress,
            'clinicPhone' => $clinicPhone
        ]);

        // Set PDF options
        $pdf->setPaper('a4');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf;
    }

    /**
     * Get prescriptions for a patient.
     *
     * @param string $patientName
     * @param string|null $patientEmail
     * @param string|null $patientPhone
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPrescriptionsForPatient(
        string $patientName,
        ?string $patientEmail = null,
        ?string $patientPhone = null
    ) {
        $query = Prescription::with(['branch', 'doctor'])
            ->where('patient_name', 'like', '%' . $patientName . '%');

        if ($patientEmail) {
            $query->whereHas('appointment', function ($q) use ($patientEmail) {
                $q->where('patient_email', $patientEmail);
            });
        }

        if ($patientPhone) {
            $query->whereHas('appointment', function ($q) use ($patientPhone) {
                $q->where('patient_phone', $patientPhone);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get prescriptions by doctor.
     *
     * @param int $doctorId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPrescriptionsByDoctor(int $doctorId, int $limit = 10)
    {
        return Prescription::with(['branch', 'appointment'])
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get prescriptions by branch.
     *
     * @param int $branchId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPrescriptionsByBranch(int $branchId, int $limit = 10)
    {
        return Prescription::with(['doctor', 'appointment'])
            ->where('branch_id', $branchId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}