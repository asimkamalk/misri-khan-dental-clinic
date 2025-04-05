<?php
// app/Services/AppointmentEmailService.php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentReminder;
use App\Mail\AppointmentStatusUpdate;

class AppointmentEmailService
{
    /**
     * Send appointment confirmation email.
     *
     * @param int $appointmentId
     * @return bool
     */
    public function sendConfirmationEmail(int $appointmentId)
    {
        try {
            $appointment = Appointment::with(['branch', 'doctor'])->findOrFail($appointmentId);

            Mail::to($appointment->patient_email)
                ->send(new AppointmentConfirmation($appointment));

            // Optional: Send a copy to the clinic admin
            // Mail::to(getSetting('contact_email'))->send(new AppointmentConfirmation($appointment, true));

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Send appointment status update email.
     *
     * @param int $appointmentId
     * @param string $oldStatus
     * @return bool
     */
    public function sendStatusUpdateEmail(int $appointmentId, string $oldStatus)
    {
        try {
            $appointment = Appointment::with(['branch', 'doctor'])->findOrFail($appointmentId);

            // Only send if status actually changed
            if ($oldStatus != $appointment->status) {
                Mail::to($appointment->patient_email)
                    ->send(new AppointmentStatusUpdate($appointment, $oldStatus));

                return true;
            }

            return false;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Send appointment reminder email.
     *
     * @param int $appointmentId
     * @return bool
     */
    public function sendReminderEmail(int $appointmentId)
    {
        try {
            $appointment = Appointment::with(['branch', 'doctor'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->findOrFail($appointmentId);

            Mail::to($appointment->patient_email)
                ->send(new AppointmentReminder($appointment));

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Send reminders for tomorrow's appointments.
     *
     * @return int Number of reminders sent
     */
    public function sendTomorrowReminders()
    {
        $tomorrow = now()->addDay()->format('Y-m-d');

        $appointments = Appointment::with(['branch', 'doctor'])
            ->whereDate('appointment_date', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            if ($this->sendReminderEmail($appointment->id)) {
                $count++;
            }
        }

        return $count;
    }
}