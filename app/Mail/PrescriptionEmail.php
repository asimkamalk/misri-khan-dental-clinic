<?php
// app/Mail/PrescriptionEmail.php

namespace App\Mail;

use App\Models\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PrescriptionEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $prescription;
    protected $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param Prescription $prescription
     * @param string $pdfPath
     * @return void
     */
    public function __construct(Prescription $prescription, string $pdfPath)
    {
        $this->prescription = $prescription;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Your Medical Prescription from ' . getSetting('site_title', 'Misri Khan Dental Clinic');
        $fileName = 'prescription_' . $this->prescription->id . '.pdf';

        return $this->subject($subject)
            ->markdown('emails.prescriptions.prescription')
            ->with([
                'prescription' => $this->prescription,
                'clinicName' => getSetting('site_title', 'Misri Khan Dental Clinic'),
                'doctorName' => $this->prescription->doctor->name,
                'branchName' => $this->prescription->branch->name,
                'branchPhone' => $this->prescription->branch->phone,
            ])
            ->attachFromStorage('public/' . $this->pdfPath, $fileName, [
                'mime' => 'application/pdf',
            ]);
    }
}