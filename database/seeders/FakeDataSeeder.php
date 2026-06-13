<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\Page;
use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        // Brands
        $brandData = [
            'Apple' => 'Apple Inc. designs, manufactures, and markets smartphones, personal computers, tablets, wearables, and accessories worldwide.',
            'Samsung' => 'Samsung Electronics Co., Ltd. is a South Korean multinational electronics corporation headquartered in Suwon, South Korea.',
            'Nike' => 'Nike, Inc. is an American multinational corporation that is engaged in the design, development, manufacturing, and worldwide marketing of footwear, apparel, equipment, accessories, and services.',
            'Adidas' => 'Adidas AG is a German multinational corporation, founded and headquartered in Herzogenaurach, Bavaria, that designs and manufactures shoes, clothing, and accessories.',
            'Sony' => 'Sony Group Corporation is a Japanese multinational conglomerate corporation headquartered in Minato, Tokyo, Japan.',
            'LG' => 'LG Electronics Inc. is a South Korean multinational electronics company headquartered in Yeouido-dong, Seoul, South Korea.',
            'Bosch' => 'Robert Bosch GmbH is a German multinational engineering and technology company headquartered in Gerlingen, Baden-Württemberg.',
            'IKEA' => 'IKEA is a Swedish-origin Dutch multinational conglomerate that designs and sells ready-to-assemble furniture, kitchen appliances, and home accessories.',
        ];
        $brands = collect();
        foreach ($brandData as $name => $description) {
            $brands->push(Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['title' => $name, 'fulldesc' => $description]
            ));
        }

        // Categories (root + children)
        $rootCategories = collect();
        $categories = collect();

        $roots = [
            'Electronics' => ['Phones', 'Laptops', 'Tablets'],
            'Clothing' => ['Men', 'Women', 'Kids'],
            'Home & Garden' => ['Furniture', 'Lighting', 'Kitchen'],
            'Sports' => ['Fitness', 'Outdoor', 'Team Sports'],
            'Books' => ['Fiction', 'Non-Fiction', 'Science'],
        ];

        foreach ($roots as $rootTitle => $children) {
            $root = Category::updateOrCreate(
                ['slug' => Str::slug($rootTitle)],
                [
                    'title' => $rootTitle,
                    'fulldesc' => fake()->paragraph(),
                ]
            );
            $rootCategories->push($root);
            $categories->push($root);

            foreach ($children as $childTitle) {
                $child = Category::updateOrCreate(
                    ['slug' => Str::slug($childTitle)],
                    [
                        'parent_id' => $root->id,
                        'title' => $childTitle,
                        'fulldesc' => fake()->paragraph(),
                    ]
                );
                $categories->push($child);
            }
        }

        // Products (2–4 per category)
        $products = collect();
        foreach ($categories as $category) {
            $count = fake()->numberBetween(2, 4);
            for ($i = 0; $i < $count; $i++) {
                $products->push(Product::create([
                    'cat_id' => $category->id,
                    'brand_id' => $brands->random()->id,
                    'title' => fake()->unique()->words(3, true),
                    'slug' => Str::slug(fake()->unique()->words(3, true)),
                    'fulldesc' => fake()->paragraphs(2, true),
                    'cost' => fake()->randomFloat(2, 5, 999),
                    'status' => fake()->boolean(85) ? 1 : 0,
                ]));
            }
        }

        // Pages
        $pages = ['About Us', 'Delivery', 'Payment', 'Returns', 'Contacts', 'Privacy Policy', 'Terms of Service'];
        foreach ($pages as $title) {
            $inFooter = in_array($title, ['Delivery', 'Payment', 'Returns', 'Privacy Policy', 'Terms of Service']);
            Page::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'show_in_footer' => $inFooter,
                    'fulldesc' => fake()->paragraphs(3, true),
                ]
            );
        }

        // News articles
        $newsData = [
            'Summer Sale Announcement' => 'We are excited to announce our biggest summer sale! Enjoy up to 50% off on selected items across all categories. Don\'t miss out on these amazing deals.',
            'New Product Line Launched' => 'Introducing our latest collection of premium products. From cutting-edge electronics to stylish clothing, discover what\'s new in our store.',
            'Free Shipping on Orders Over $50' => 'Good news for our customers! We now offer free shipping on all orders over $50. Shop now and save on delivery costs.',
            'Holiday Store Hours' => 'Planning your holiday shopping? Check our updated store hours for the festive season. We\'re here to help you find the perfect gifts.',
            'Customer Appreciation Week' => 'Thank you for being a valued customer! Join us for exclusive discounts and special offers during our Customer Appreciation Week.',
        ];
        foreach ($newsData as $title => $content) {
            $createdAt = fake()->dateTimeBetween('-3 months', 'now');
            News::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'fulldesc' => $content,
                    'status' => 1,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );
        }

        // Orders with items (spread across past 6 months)
        $statuses = ['New', 'In Progress', 'Done'];
        $deliveryMethods = ['Pickup', 'Courier', 'Post'];
        $paymentMethods = ['Cash on delivery', 'Bank transfer', 'Card'];

        for ($i = 0; $i < 15; $i++) {
            $orderProducts = $products->random(fake()->numberBetween(1, 5));
            $rate = (float) setting('rate', 1);
            $total = 0;

            $createdAt = fake()->dateTimeBetween('-6 months', 'now');

            $order = Order::create([
                'name' => fake()->name(),
                'email' => fake()->optional(0.8)->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'comment' => fake()->optional(0.6)->sentence(),
                'status' => $statuses[array_rand($statuses)],
                'delivery_method' => $deliveryMethods[array_rand($deliveryMethods)],
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'total' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($orderProducts as $product) {
                $qty = fake()->numberBetween(1, 3);
                $subtotal = round((float) $product->cost * $qty * $rate, 2);
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->cost,
                    'qty' => $qty,
                ]);
            }

            $order->update(['total' => $total]);
        }
    }
}
