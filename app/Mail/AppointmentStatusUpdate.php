<?php
// app/Mail/AppointmentStatusUpdate.php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusUpdate extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $appointment;
    public $oldStatus;

    /**
     * Create a new message instance.
     *
     * @param Appointment $appointment
     * @param string $oldStatus
     * @return void
     */
    public function __construct(Appointment $appointment, string $oldStatus)
    {
        $this->appointment = $appointment;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $statusText = ucfirst($this->appointment->status);

        $subject = "Your Appointment Status is Now {$statusText} - " . getSetting('site_title', 'Misri Khan Dental Clinic');

        return $this->subject($subject)
            ->markdown('emails.appointments.status-update')
            ->with([
                'appointment' => $this->appointment,
                'oldStatus' => $this->oldStatus,
                'clinicName' => getSetting('site_title', 'Misri Khan Dental Clinic'),
                'clinicPhone' => getSetting('contact_phone'),
                'clinicEmail' => getSetting('contact_email'),
            ]);
    }
}