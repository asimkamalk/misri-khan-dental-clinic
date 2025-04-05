<?php
// app/Http/Controllers/Admin/ServiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
        $this->middleware('permission:services_access');
        $this->middleware('permission:services_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:services_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:services_delete', ['only' => ['destroy']]);

        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the services.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $services = Service::orderBy('display_order')->get();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'long_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'services',
                800,
                600,
                true
            );
        }

        // Create the service
        Service::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'image' => $imagePath,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\View\View
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Service $service)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'long_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = $service->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                $this->fileUploadService->deleteFile($service->image);
            }

            $imagePath = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'services',
                800,
                600,
                true
            );
        }

        // Update the service
        $service->update([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'image' => $imagePath,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service)
    {
        // Delete service image if exists
        if ($service->image) {
            $this->fileUploadService->deleteFile($service->image);
        }

        // Delete the service
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Reorder services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'integer|exists:services,id',
        ]);

        $services = $request->input('items');

        foreach ($services as $order => $serviceId) {
            Service::where('id', $serviceId)->update(['display_order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}