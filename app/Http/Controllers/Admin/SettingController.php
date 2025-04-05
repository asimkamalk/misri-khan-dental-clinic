<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\FileUploadService;

class SettingController extends Controller
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
        $this->fileUploadService = $fileUploadService;
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Group settings by category
        $generalSettings = Setting::where('group', 'general')->get();
        $contactSettings = Setting::where('group', 'contact')->get();
        $socialSettings = Setting::where('group', 'social')->get();
        $seoSettings = Setting::where('group', 'seo')->get();

        return view('admin.settings.index', compact(
            'generalSettings',
            'contactSettings',
            'socialSettings',
            'seoSettings'
        ));
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico|max:1024',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'google_analytics' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logo = $this->fileUploadService->uploadImage(
                $request->file('logo'),
                'settings',
                800,
                300,
                true
            );

            $this->updateSetting('logo', $logo);
        }

        if ($request->hasFile('favicon')) {
            $favicon = $this->fileUploadService->uploadImage(
                $request->file('favicon'),
                'settings',
                32,
                32,
                false
            );

            $this->updateSetting('favicon', $favicon);
        }

        // Update text settings
        $textSettings = [
            'site_title',
            'site_description',
            'contact_email',
            'contact_phone',
            'contact_address',
            'facebook',
            'twitter',
            'instagram',
            'linkedin',
            'youtube',
            'meta_keywords',
            'meta_description',
            'footer_text',
            'google_analytics',
        ];

        foreach ($textSettings as $key) {
            if ($request->has($key)) {
                $this->updateSetting($key, $request->input($key));
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Update or create a setting.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return void
     */
    protected function updateSetting(string $key, $value, string $group = 'general')
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}