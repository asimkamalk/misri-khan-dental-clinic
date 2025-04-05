<?php
// app/Http/Controllers/Admin/DoctorController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Branch;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected $fileUploadService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\FileUploadService $fileUploadService
     * @return void
     */
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->middleware('auth');
        $this->middleware('permission:doctors_access');
        $this->middleware('permission:doctors_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:doctors_edit', ['only' => ['edit', 'update', 'updateBranches']]);
        $this->middleware('permission:doctors_delete', ['only' => ['destroy']]);

        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the doctors.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctors = Doctor::with('branches')->orderBy('name')->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        return view('admin.doctors.create', compact('branches'));
    }

    /**
     * Store a newly created doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'doctors',
                400,
                400,
                true
            );
        }

        // Create the doctor
        $doctor = Doctor::create([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync branches
        $doctor->branches()->sync($request->branch_ids);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    /**
     * Show the form for editing the specified doctor.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\View\View
     */
    public function edit(Doctor $doctor)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $doctor->load('branches');

        return view('admin.doctors.edit', compact('doctor', 'branches'));
    }

    /**
     * Update the specified doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Doctor $doctor)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = $doctor->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($doctor->image) {
                $this->fileUploadService->deleteFile($doctor->image);
            }

            $imagePath = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'doctors',
                400,
                400,
                true
            );
        }

        // Update the doctor
        $doctor->update([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync branches
        $doctor->branches()->sync($request->branch_ids);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified doctor from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Doctor $doctor)
    {
        // Check if doctor has any appointments
        if ($doctor->appointments()->count() > 0) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Cannot delete doctor because they have appointments.');
        }

        // Delete doctor image if exists
        if ($doctor->image) {
            $this->fileUploadService->deleteFile($doctor->image);
        }

        // Delete the doctor
        $doctor->branches()->detach();
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }

    /**
     * Update doctor branches.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBranches(Request $request, Doctor $doctor)
    {
        $request->validate([
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'exists:branches,id',
        ]);

        $doctor->branches()->sync($request->branch_ids);

        return response()->json([
            'success' => true,
            'message' => 'Doctor branches updated successfully.'
        ]);
    }
}