<?php
// app/Http/Controllers/Admin/PrescriptionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Doctor;
use App\Services\PrescriptionService;
use App\Services\PrescriptionPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PrescriptionController extends Controller
{
    protected $prescriptionService;

    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->middleware('auth');
        $this->middleware('permission:prescriptions_access');
        $this->middleware('permission:prescriptions_create', ['only' => ['create', 'createFromAppointment', 'store']]);
        $this->middleware('permission:prescriptions_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:prescriptions_delete', ['only' => ['destroy']]);

        $this->prescriptionService = $prescriptionService;
    }

    public function index(Request $request)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        $filters = [
            'branch_id' => $request->branch_id,
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        $query = Prescription::with(['branch', 'doctor', 'appointment']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('patient_name')) {
            $query->where('patient_name', 'like', '%' . $request->patient_name . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $prescriptions = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.prescriptions.index', compact('prescriptions', 'branches', 'doctors', 'filters'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        return view('admin.prescriptions.create', compact('branches', 'doctors'));
    }

    public function createFromAppointment(Appointment $appointment)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        $existingPrescription = Prescription::where('appointment_id', $appointment->id)->first();
        if ($existingPrescription) {
            return redirect()->route('admin.prescriptions.edit', $existingPrescription)
                ->with('info', 'A prescription already exists for this appointment.');
        }

        return view('admin.prescriptions.create_from_appointment', compact('appointment', 'branches', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'patient_name' => 'required|string|max:255',
            'patient_age' => 'nullable|integer',
            'patient_gender' => 'nullable|string|in:male,female,other',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'medications' => 'nullable|string',
            'notes' => 'nullable|string',
            'followup_date' => 'nullable|date',
        ]);

        if ($request->filled('appointment_id')) {
            $prescription = $this->prescriptionService->createFromAppointment(
                $request->appointment_id,
                $request->all()
            );
        } else {
            $prescription = $this->prescriptionService->createWithoutAppointment($request->all());
        }

        return redirect()->route('admin.prescriptions.index')
            ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['branch', 'doctor', 'appointment']);
        return view('admin.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('name')->get();

        $prescription->load(['branch', 'doctor', 'appointment']);
        return view('admin.prescriptions.edit', compact('prescription', 'branches', 'doctors'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_age' => 'nullable|integer',
            'patient_gender' => 'nullable|string|in:male,female,other',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'medications' => 'nullable|string',
            'notes' => 'nullable|string',
            'followup_date' => 'nullable|date',
        ]);

        $prescription->update($request->all());

        return redirect()->route('admin.prescriptions.index')
            ->with('success', 'Prescription updated successfully.');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('admin.prescriptions.index')
            ->with('success', 'Prescription deleted successfully.');
    }

    public function print(Prescription $prescription)
    {
        $pdfService = app(PrescriptionPdfService::class);
        $pdf = $pdfService->generatePdf($prescription->id);

        $filename = 'prescription_' . $prescription->id . '_' . str_replace(' ', '_', $prescription->patient_name) . '.pdf';

        return $pdf->stream($filename);
    }

    public function download(Prescription $prescription)
    {
        $pdfService = app(PrescriptionPdfService::class);
        $pdf = $pdfService->generatePdf($prescription->id);

        $filename = 'prescription_' . $prescription->id . '_' . str_replace(' ', '_', $prescription->patient_name) . '.pdf';

        return $pdf->download($filename);
    }

    public function email(Prescription $prescription)
    {
        try {
            $pdfService = app(PrescriptionPdfService::class);
            $pdfPath = $pdfService->savePdf($prescription->id);

            if (!$pdfPath) {
                return redirect()->back()->with('error', 'Failed to generate the prescription PDF.');
            }

            $email = null;
            if ($prescription->appointment) {
                $email = $prescription->appointment->patient_email;
            }

            if (!$email) {
                return redirect()->back()->with('error', 'No patient email found for this prescription.');
            }

            Mail::to($email)->send(new \App\Mail\PrescriptionEmail($prescription, $pdfPath));

            return redirect()->back()->with('success', 'Prescription has been emailed to the patient.');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Failed to email prescription: ' . $e->getMessage());
        }
    }
}
