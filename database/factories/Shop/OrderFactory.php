<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'comment' => fake()->sentence(),
            'total' => fake()->randomFloat(2, 1, 5000),
            'legacy_order' => null,
        ];
    }
}
