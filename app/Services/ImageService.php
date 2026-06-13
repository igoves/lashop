<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Gd\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class ImageService
{
    public function store(UploadedFile $file, string $directory): string
    {
        $filename = Str::random(24).'.jpg';
        $manager = new ImageManager(new GdDriver);
        $dir = public_path("uploads/{$directory}");

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $manager->decodePath($file->getRealPath())
            ->cover(400, 300)
            ->encode(new JpegEncoder(85))
            ->save("{$dir}/{$filename}");

        return $filename;
    }

    public function delete(string $filename, string $directory): void
    {
        $path = public_path("uploads/{$directory}/{$filename}");
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Check if GD driver is available.
     */
    public static function isAvailable(): bool
    {
        return extension_loaded('gd');
    }
}
