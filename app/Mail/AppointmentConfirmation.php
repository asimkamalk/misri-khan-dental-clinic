<?php
// app/Mail/AppointmentConfirmation.php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $appointment;
    public $isAdminCopy;

    /**
     * Create a new message instance.
     *
     * @param Appointment $appointment
     * @param bool $isAdminCopy
     * @return void
     */
    public function __construct(Appointment $appointment, bool $isAdminCopy = false)
    {
        $this->appointment = $appointment;
        $this->isAdminCopy = $isAdminCopy;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isAdminCopy
            ? 'New Appointment Booking: ' . $this->appointment->patient_name
            : 'Your Appointment Confirmation - ' . getSetting('site_title', 'Misri Khan Dental Clinic');

        return $this->subject($subject)
            ->markdown('emails.appointments.confirmation')
            ->with([
                'appointment' => $this->appointment,
                'isAdminCopy' => $this->isAdminCopy,
                'clinicName' => getSetting('site_title', 'Misri Khan Dental Clinic'),
                'clinicPhone' => getSetting('contact_phone'),
                'clinicEmail' => getSetting('contact_email'),
            ]);
    }


    // This is an addition to the existing AppointmentConfirmation.php file

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isAdminCopy
            ? 'New Appointment Booking: ' . $this->appointment->patient_name
            : 'Your Appointment Confirmation - ' . getSetting('site_title', 'Misri Khan Dental Clinic');

        // Generate token for accessing iCal export
        $token = hash('sha256', $this->appointment->id . $this->appointment->patient_email . env('APP_KEY'));
        $icalUrl = route('appointment.export-ical', ['id' => $this->appointment->id, 'token' => $token]);

        // Generate iCal attachment
        $calendarService = app(\App\Services\CalendarService::class);
        $icalContent = $calendarService->generateICalForAppointment($this->appointment->id);

        return $this->subject($subject)
            ->markdown('emails.appointments.confirmation')
            ->with([
                'appointment' => $this->appointment,
                'isAdminCopy' => $this->isAdminCopy,
                'clinicName' => getSetting('site_title', 'Misri Khan Dental Clinic'),
                'clinicPhone' => getSetting('contact_phone'),
                'clinicEmail' => getSetting('contact_email'),
                'icalUrl' => $icalUrl,
            ])
            ->attachData($icalContent, 'appointment.ics', [
                'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
            ]);
    }
}

