<?php
// app/Helpers/helpers.php

if (!function_exists('getSetting')) {
    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function getSetting($key, $default = null)
    {
        $setting = \App\Models\Setting::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('formatTime')) {
    /**
     * Format time.
     *
     * @param string $time
     * @return string
     */
    function formatTime($time)
    {
        return date('h:i A', strtotime($time));
    }
}

if (!function_exists('getUploadPath')) {
    /**
     * Get upload path.
     *
     * @param string $type
     * @return string
     */
    function getUploadPath($type = 'images')
    {
        return storage_path("app/public/{$type}");
    }
}

if (!function_exists('getUploadUrl')) {
    /**
     * Get upload URL.
     *
     * @param string $path
     * @return string
     */
    function getUploadUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        // Check if the path already contains the storage path
        if (strpos($path, 'storage/') === 0) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}

if (!function_exists('uploadImage')) {
    /**
     * Upload image.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string|null $oldFile
     * @return string
     */
    function uploadImage($file, $directory = 'images', $oldFile = null)
    {
        // Delete old file if exists
        if ($oldFile) {
            $oldFilePath = public_path('storage/' . $oldFile);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Create directory if not exists
        $path = storage_path("app/public/{$directory}");
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Generate file name
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Optimize and save the image
        $img = \Intervention\Image\Facades\Image::make($file->getRealPath());
        $img->save("{$path}/{$fileName}");

        return "{$directory}/{$fileName}";
    }
}

if (!function_exists('getStatusBadge')) {
    /**
     * Get status badge.
     *
     * @param string $status
     * @return string
     */
    function getStatusBadge($status)
    {
        $badges = [
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
        ];

        return $badges[$status] ?? 'badge-secondary';
    }
}

if (!function_exists('getStatusName')) {
    /**
     * Get status name.
     *
     * @param string $status
     * @return string
     */
    function getStatusName($status)
    {
        return ucfirst($status);
    }
}

if (!function_exists('sanitizeInput')) {
    /**
     * Sanitize input.
     *
     * @param string $input
     * @return string
     */
    function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}