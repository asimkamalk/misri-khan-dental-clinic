<?php
// app/Services/ImageService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Upload and optimize an image file.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $options
     * @return string|null
     */
    public function uploadImage(
        UploadedFile $file,
        string $folder = 'images',
        array $options = []
    ): ?string {
        try {
            // Set default options
            $defaults = [
                'width' => 800,
                'height' => 600,
                'quality' => 80,
                'maintain_aspect_ratio' => true,
                'create_thumbnail' => false,
                'thumb_width' => 200,
                'thumb_height' => 200,
                'watermark' => false,
                'watermark_text' => getSetting('site_title', 'Misri Khan Dental Clinic'),
                'webp_convert' => true
            ];

            // Merge options with defaults
            $options = array_merge($defaults, $options);

            // Generate unique filename
            $extension = $options['webp_convert'] ? 'webp' : $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;

            // Process image with Intervention Image
            $img = Image::make($file->getRealPath());

            // Resize image
            if ($options['maintain_aspect_ratio']) {
                $img->resize($options['width'], $options['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $img->fit($options['width'], $options['height']);
            }

            // Add watermark if enabled
            if ($options['watermark']) {
                $img->text($options['watermark_text'], $img->width() - 20, $img->height() - 20, function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(24);
                    $font->color([255, 255, 255, 0.5]);
                    $font->align('right');
                    $font->valign('bottom');
                });
            }

            // Create directory if it doesn't exist
            $path = storage_path("app/public/{$folder}");
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Convert to WebP if enabled
            if ($options['webp_convert']) {
                $img->encode('webp', $options['quality']);
            } else {
                $img->encode($file->getClientOriginalExtension(), $options['quality']);
            }

            // Save the file
            $img->save("{$path}/{$fileName}");

            // Create thumbnail if enabled
            if ($options['create_thumbnail']) {
                $thumbFileName = 'thumb_' . $fileName;
                $thumbImg = Image::make($file->getRealPath());

                $thumbImg->fit($options['thumb_width'], $options['thumb_height']);

                if ($options['webp_convert']) {
                    $thumbImg->encode('webp', $options['quality']);
                } else {
                    $thumbImg->encode($file->getClientOriginalExtension(), $options['quality']);
                }

                $thumbImg->save("{$path}/{$thumbFileName}");
            }

            return "{$folder}/{$fileName}";
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Delete an image file from storage.
     *
     * @param string|null $filePath
     * @param bool $includeThumbnail
     * @return bool
     */
    public function deleteImage(?string $filePath, bool $includeThumbnail = true): bool
    {
        if (empty($filePath)) {
            return false;
        }

        try {
            $deleted = Storage::disk('public')->delete($filePath);

            // Delete thumbnail if it exists
            if ($includeThumbnail) {
                $pathInfo = pathinfo($filePath);
                $thumbPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }

            return $deleted;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Get optimized image URL.
     *
     * @param string|null $filePath
     * @param bool $thumbnail
     * @return string|null
     */
    public function getImageUrl(?string $filePath, bool $thumbnail = false): ?string
    {
        if (empty($filePath)) {
            return null;
        }

        try {
            if ($thumbnail) {
                $pathInfo = pathinfo($filePath);
                $thumbPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

                if (Storage::disk('public')->exists($thumbPath)) {
                    return Storage::disk('public')->url($thumbPath);
                }
            }

            return Storage::disk('public')->url($filePath);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Optimize existing images in storage.
     *
     * @param string $folder
     * @param int $quality
     * @param bool $convertToWebp
     * @return int
     */
    public function optimizeExistingImages(string $folder = 'images', int $quality = 80, bool $convertToWebp = false): int
    {
        try {
            $count = 0;
            $files = Storage::disk('public')->files($folder);

            foreach ($files as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                // Only process images
                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $fullPath = storage_path("app/public/{$file}");
                    $img = Image::make($fullPath);

                    if ($convertToWebp) {
                        $newFileName = pathinfo($file, PATHINFO_FILENAME) . '.webp';
                        $newPath = pathinfo($file, PATHINFO_DIRNAME) . '/' . $newFileName;

                        $img->encode('webp', $quality);
                        $img->save(storage_path("app/public/{$newPath}"));

                        // Delete the old file
                        Storage::disk('public')->delete($file);
                    } else {
                        $img->encode($extension, $quality);
                        $img->save($fullPath, $quality);
                    }

                    $count++;
                }
            }

            return $count;
        } catch (\Exception $e) {
            report($e);
            return 0;
        }
    }
}