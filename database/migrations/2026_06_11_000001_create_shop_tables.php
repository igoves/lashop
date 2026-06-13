<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()
                ->constrained('shop_categories')->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('fulldesc')->nullable();
            $table->string('logo')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')
                ->constrained('shop_categories')->restrictOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('fulldesc')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('photo')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('cat_id');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('fulldesc')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('comment')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            // archive: HTML/JSON content of the legacy `order` column (step 4),
            // new orders store items only in shop_order_items
            $table->text('legacy_order')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('shop_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()
                ->constrained('shop_products')->nullOnDelete();
            $table->string('title');          // snapshot of product name at order time
            $table->decimal('price', 10, 2);  // snapshot of unit price (before rate)
            $table->unsignedInteger('qty');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('shop_order_items');
        Schema::dropIfExists('shop_orders');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('shop_products');
        Schema::dropIfExists('shop_categories');
    }
};
