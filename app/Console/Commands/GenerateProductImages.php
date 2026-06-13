<?php

namespace App\Console\Commands;

use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateProductImages extends Command
{
    protected $signature = 'products:generate-images';

    protected $description = 'Generate placeholder images for products, brands, and categories without photos';

    public function handle(): int
    {
        $this->generateForBrands();
        $this->generateForCategories();
        $this->generateForProducts();

        return self::SUCCESS;
    }

    private function generateForBrands(): void
    {
        $brands = Brand::whereNull('logo')->get();

        if ($brands->isEmpty()) {
            $this->info('All brands already have logos.');

            return;
        }

        $this->info("Generating logos for {$brands->count()} brands...");

        foreach ($brands as $brand) {
            $filename = $this->generatePlaceholder($brand->title, 'brands', 400, 300);
            $brand->update(['logo' => $filename]);
            $this->line("  <info>✔</info> #{$brand->id} {$brand->title}");
        }

        $this->info("Done. Generated logos for {$brands->count()} brands.");
    }

    private function generateForCategories(): void
    {
        $categories = Category::whereNull('logo')->get();

        if ($categories->isEmpty()) {
            $this->info('All categories already have logos.');

            return;
        }

        $this->info("Generating logos for {$categories->count()} categories...");

        foreach ($categories as $category) {
            $filename = $this->generatePlaceholder($category->title, 'categories', 400, 300);
            $category->update(['logo' => $filename]);
            $this->line("  <info>✔</info> #{$category->id} {$category->title}");
        }

        $this->info("Done. Generated logos for {$categories->count()} categories.");
    }

    private function generateForProducts(): void
    {
        $products = Product::whereNull('photo')->get();

        if ($products->isEmpty()) {
            $this->info('All products already have photos.');

            return;
        }

        $this->info("Generating images for {$products->count()} products...");

        $sizes = [
            'big' => [800, 600],
            'medium' => [400, 300],
            'small' => [120, 90],
        ];

        foreach ($products as $product) {
            $filename = Str::random(24) . '.jpg';

            foreach ($sizes as $size => [$w, $h]) {
                $dir = public_path("uploads/products/{$size}");
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                $image = $this->createPlaceholderImage($product->title, $w, $h);
                imagejpeg($image, "{$dir}/{$filename}", 85);
                imagedestroy($image);
            }

            $product->update(['photo' => $filename]);
            $this->line("  <info>✔</info> #{$product->id} {$product->title}");
        }

        $this->info("Done. Generated images for {$products->count()} products.");
    }

    private function generatePlaceholder(string $title, string $directory, int $w, int $h): string
    {
        $dir = public_path("uploads/{$directory}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = Str::random(24) . '.jpg';
        $image = $this->createPlaceholderImage($title, $w, $h);
        imagejpeg($image, "{$dir}/{$filename}", 85);
        imagedestroy($image);

        return $filename;
    }

    private function createPlaceholderImage(string $title, int $w, int $h): \GdImage
    {
        $image = imagecreatetruecolor($w, $h);

        $hash = crc32($title);
        $r = ($hash >> 0) & 0xFF;
        $g = ($hash >> 8) & 0xFF;
        $b = ($hash >> 16) & 0xFF;
        $bg = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, 0, 0, $w - 1, $h - 1, $bg);

        $lr = min(255, $r + 80);
        $lg = min(255, $g + 80);
        $lb = min(255, $b + 80);
        $overlay = imagecolorallocate($image, $lr, $lg, $lb);
        $padX = (int) ($w * 0.1);
        $padY = (int) ($h * 0.3);
        imagefilledrectangle($image, $padX, $padY, $w - $padX, $h - $padY, $overlay);

        $white = imagecolorallocate($image, 255, 255, 255);
        $text = Str::limit($title, 12);
        $fontSize = max(5, (int) ($w / 30));
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = (int) (($w - $textWidth) / 2);
        $y = (int) (($h - $textHeight) / 2);
        imagestring($image, $fontSize, $x, $y, $text, $white);

        return $image;
    }
}
