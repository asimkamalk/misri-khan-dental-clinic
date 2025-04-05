<?php
// app/Mail/AppointmentReminder.php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     *
     * @param Appointment $appointment
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Reminder: Your Dental Appointment Tomorrow - " . getSetting('site_title', 'Misri Khan Dental Clinic');

        return $this->subject($subject)
            ->markdown('emails.appointments.reminder')
            ->with([
                'appointment' => $this->appointment,
                'clinicName' => getSetting('site_title', 'Misri Khan Dental Clinic'),
                'clinicPhone' => getSetting('contact_phone'),
                'clinicEmail' => getSetting('contact_email'),
            ]);
    }
}