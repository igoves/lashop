<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Gd\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class ProductImageService
{
    /**
     * Accepts an UploadedFile, saves 3 versions to
     * public/uploads/products/{big|medium|small}/{filename}.jpg
     * Returns the filename (without path).
     */
    public function store(UploadedFile $file): string
    {
        $filename = Str::random(24).'.jpg';

        $manager = new ImageManager(new GdDriver);

        $sizes = $this->sizes();

        foreach ($sizes as $size => [$w, $h]) {
            $dir = public_path(config('shop.image_path')."/{$size}");
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $manager->decodePath($file->getRealPath())
                ->cover($w, $h)
                ->encode(new JpegEncoder(85))
                ->save("{$dir}/{$filename}");
        }

        return $filename;
    }

    /**
     * Deletes all 3 versions of a file (if they exist).
     */
    public function delete(string $filename): void
    {
        foreach (array_keys($this->sizes()) as $size) {
            $path = public_path(config('shop.image_path')."/{$size}/{$filename}");
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Image sizes: admin settings override config defaults.
     *
     * @return array<string, array{int, int}>
     */
    private function sizes(): array
    {
        $defaults = config('shop.image_sizes');

        $parse = function (string $slug, array $fallback): array {
            $raw = setting($slug);
            if ($raw && preg_match('/^(\d+)\s*[x×]\s*(\d+)$/i', trim($raw), $m)) {
                return [(int) $m[1], (int) $m[2]];
            }

            return $fallback;
        };

        return [
            'big' => $parse('image_size_big', $defaults['big']),
            'medium' => $parse('image_size_medium', $defaults['medium']),
            'small' => $parse('image_size_small', $defaults['small']),
        ];
    }
}
