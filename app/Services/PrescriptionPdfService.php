<?php
// app/Services/PrescriptionPdfService.php

namespace App\Services;

use App\Models\Prescription;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionPdfService
{
    /**
     * Generate a prescription PDF.
     *
     * @param int $prescriptionId
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(int $prescriptionId)
    {
        // Get prescription with related data
        $prescription = Prescription::with(['branch', 'doctor', 'appointment'])->findOrFail($prescriptionId);

        // Get clinic settings from the specified branch
        $clinicName = $prescription->branch->name;
        $clinicAddress = $prescription->branch->address;
        $clinicPhone = $prescription->branch->phone;
        $clinicEmail = $prescription->branch->email;

        // Add clinic logo if available
        $logoPath = null;
        $siteLogo = getSetting('logo');
        if ($siteLogo) {
            $logoPath = public_path('storage/' . $siteLogo);
        }

        // Custom header and footer
        $headerHtml = View::make('admin.prescriptions.pdf.header', [
            'clinicName' => $clinicName,
            'clinicAddress' => $clinicAddress,
            'clinicPhone' => $clinicPhone,
            'clinicEmail' => $clinicEmail,
            'logoPath' => $logoPath,
        ])->render();

        $footerHtml = View::make('admin.prescriptions.pdf.footer', [
            'clinicName' => $clinicName,
            'doctorName' => $prescription->doctor->name,
            'doctorSpecialization' => $prescription->doctor->specialization,
        ])->render();

        // Generate PDF with header and footer
        $pdf = PDF::loadView('admin.prescriptions.pdf.template', [
            'prescription' => $prescription,
            'clinicName' => $clinicName,
            'clinicAddress' => $clinicAddress,
            'clinicPhone' => $clinicPhone,
            'clinicEmail' => $clinicEmail,
            'headerHtml' => $headerHtml,
            'footerHtml' => $footerHtml,
        ]);

        // Set PDF options
        $pdf->setOption('header-html', $headerHtml);
        $pdf->setOption('footer-html', $footerHtml);
        $pdf->setPaper('a4');
        $pdf->setOption('margin-top', 45);
        $pdf->setOption('margin-bottom', 40);
        $pdf->setOption('margin-left', 15);
        $pdf->setOption('margin-right', 15);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf;
    }

    /**
     * Save prescription PDF to storage and return the path.
     *
     * @param int $prescriptionId
     * @return string
     */
    public function savePdf(int $prescriptionId)
    {
        $pdf = $this->generatePdf($prescriptionId);
        $prescription = Prescription::findOrFail($prescriptionId);

        // Generate a file name
        $filename = 'prescription_' . $prescriptionId . '_' . time() . '.pdf';
        $path = 'prescriptions/' . $filename;

        // Save PDF to storage
        $pdf->save(storage_path('app/public/' . $path));

        return $path;
    }
}