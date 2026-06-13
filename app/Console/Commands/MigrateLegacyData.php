<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PDO;

class MigrateLegacyData extends Command
{
    protected $signature = 'legacy:migrate
                            {--source=docs/reference/database.sqlite : Path to the legacy SQLite dump}
                            {--fresh : Truncate target tables before importing}';

    protected $description = 'Import data from the legacy SQLite dump (Step 4)';

    public function handle(): int
    {
        $source = base_path($this->option('source'));

        if (! file_exists($source)) {
            $this->error("Source file not found: {$source}");

            return self::FAILURE;
        }

        $legacy = new PDO("sqlite:{$source}");
        $legacy->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        if ($this->option('fresh')) {
            $this->warn('Truncating target tables…');
            // FK checks off: categories self-reference parent_id, and on
            // SQLite truncate is a plain DELETE checked row by row
            Schema::withoutForeignKeyConstraints(function () {
                OrderItem::truncate();
                Order::truncate();
                Product::truncate();
                Category::truncate();
                Page::truncate();
            });
        }

        $this->migrateCategories($legacy);
        $this->migrateProducts($legacy);
        $this->migratePages($legacy);
        $this->migrateOrders($legacy);

        $this->info('Legacy data migration complete.');

        return self::SUCCESS;
    }

    private function migrateCategories(PDO $db): void
    {
        $rows = $db->query('SELECT * FROM shop_categories ORDER BY id')->fetchAll();
        $count = 0;

        foreach ($rows as $row) {
            // parent_id 0 (old sentinel) → NULL (FK-safe)
            $parentId = $row['parent_id'] > 0 ? (int) $row['parent_id'] : null;

            Category::updateOrCreate(
                ['id' => $row['id']],
                [
                    'parent_id' => $parentId,
                    'title' => stripslashes((string) $row['title']),
                    'slug' => (string) $row['slug'],
                    'fulldesc' => $this->cleanHtml(stripslashes((string) ($row['fulldesc'] ?? ''))),
                ]
            );

            $count++;
        }

        $this->info("Categories: {$count} imported.");
    }

    private function migrateProducts(PDO $db): void
    {
        $rows = $db->query('SELECT * FROM shop_products ORDER BY id')->fetchAll();
        $count = 0;

        foreach ($rows as $row) {
            // Normalize slug: old system sometimes used the numeric id as slug
            $slug = (string) $row['slug'];
            if ($slug === '' || is_numeric($slug)) {
                $slug = Str::slug((string) $row['title']) ?: 'product-'.$row['id'];
            }

            Product::updateOrCreate(
                ['id' => $row['id']],
                [
                    'cat_id' => (int) $row['cat_id'],
                    'title' => stripslashes((string) $row['title']),
                    'slug' => $slug,
                    'fulldesc' => $this->cleanHtml(stripslashes((string) ($row['fulldesc'] ?? ''))),
                    'cost' => (float) $row['cost'],
                    'photo' => $row['photo'] ?: null,
                    'status' => (int) ($row['status'] ?? 1),
                    'meta_description' => stripslashes((string) ($row['meta_desc'] ?? '')),
                    'meta_keywords' => stripslashes((string) ($row['meta_key'] ?? '')),
                ]
            );

            $count++;
        }

        $this->info("Products: {$count} imported.");
    }

    private function migratePages(PDO $db): void
    {
        $rows = $db->query('SELECT * FROM pages ORDER BY id')->fetchAll();
        $count = 0;

        foreach ($rows as $row) {
            Page::updateOrCreate(
                ['id' => $row['id']],
                [
                    'title' => stripslashes((string) $row['title']),
                    'slug' => (string) $row['slug'],
                    // HTML content: sanitize embedded data URIs and keep as-is
                    'fulldesc' => $this->cleanHtml(stripslashes((string) ($row['fulldesc'] ?? ''))),
                    'meta_description' => stripslashes((string) ($row['meta_desc'] ?? '')),
                    'meta_keywords' => stripslashes((string) ($row['meta_key'] ?? '')),
                ]
            );

            $count++;
        }

        $this->info("Pages: {$count} imported.");
    }

    private function migrateOrders(PDO $db): void
    {
        // Old column is literally named "order" (SQL reserved word)
        $rows = $db->query('SELECT id, name, email, phone, comment, total, "order" AS legacy_raw, created_at FROM shop_orders ORDER BY id')->fetchAll();
        $count = 0;

        foreach ($rows as $row) {
            Order::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => stripslashes((string) ($row['name'] ?? '')),
                    'email' => stripslashes((string) ($row['email'] ?? '')),
                    'phone' => stripslashes((string) ($row['phone'] ?? '')),
                    'comment' => stripslashes((string) ($row['comment'] ?? '')),
                    'total' => (float) ($row['total'] ?? 0),
                    // Store raw legacy order text as archive field; do not parse
                    'legacy_order' => $row['legacy_raw'] ?: null,
                    'created_at' => $row['created_at'] ?: now(),
                    'updated_at' => $row['created_at'] ?: now(),
                ]
            );

            $count++;
        }

        $this->info("Orders: {$count} imported.");
    }

    /**
     * Remove data URI images (base64) and strip unsafe attributes.
     * Keeps the HTML structure intact for frontend rendering.
     */
    private function cleanHtml(string $html): string
    {
        if ($html === '') {
            return '';
        }

        // Remove <img> tags with base64 data URIs — they bloat the DB and are useless
        $html = preg_replace('/<img[^>]+src=["\']data:[^"\']+["\'][^>]*\/?>/i', '', $html) ?? $html;

        // Remove inline event handlers (onerror, onclick, etc.)
        $html = preg_replace('/\s+on\w+=["\'][^"\']*["\']/i', '', $html) ?? $html;

        return trim($html);
    }
}
