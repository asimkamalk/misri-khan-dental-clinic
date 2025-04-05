<?php
// app/Services/FileUploadService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload an image file.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param int $width
     * @param int $height
     * @param bool $maintainAspectRatio
     * @return string|null
     */
    public function uploadImage(
        UploadedFile $file,
        string $folder = 'images',
        int $width = 800,
        int $height = 600,
        bool $maintainAspectRatio = true
    ): ?string {
        try {
            // Generate unique filename
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Process image with Intervention Image
            $img = Image::make($file->getRealPath());

            // Resize image
            if ($maintainAspectRatio) {
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $img->fit($width, $height);
            }

            // Create directory if it doesn't exist
            $path = storage_path("app/public/{$folder}");
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Save the file
            $img->save("{$path}/{$fileName}");

            return "{$folder}/{$fileName}";
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Delete a file from storage.
     *
     * @param string|null $filePath
     * @return bool
     */
    public function deleteFile(?string $filePath): bool
    {
        if (empty($filePath)) {
            return false;
        }

        try {
            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->delete($filePath);
            }
            return false;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Get file URL.
     *
     * @param string|null $filePath
     * @return string|null
     */
    public function getFileUrl(?string $filePath): ?string
    {
        if (empty($filePath)) {
            return null;
        }

        return Storage::disk('public')->url($filePath);
    }

    /**
     * Validate an image file.
     *
     * @param UploadedFile $file
     * @param int $maxSize (in KB)
     * @return bool
     */
    public function validateImage(UploadedFile $file, int $maxSize = 2048): bool
    {
        // Check if it's a valid image
        if (!in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return false;
        }

        // Check file size (convert KB to bytes)
        if ($file->getSize() > ($maxSize * 1024)) {
            return false;
        }

        return true;
    }
}