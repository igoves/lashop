<?php

namespace App\Services;

use App\Models\Shop\Product;
use Illuminate\Support\Collection;

/**
 * Shopping cart backed by the native session: [product_id => qty].
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Add a product (adding again increases the quantity).
     */
    public function add(int $productId, int $qty = 1): void
    {
        $cart = $this->raw();
        $cart[$productId] = ($cart[$productId] ?? 0) + max(1, $qty);

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);

        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Raw session contents: [product_id => qty].
     *
     * @return array<int, int>
     */
    public function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    /**
     * Cart lines with loaded products (active only).
     * Products deleted or hidden after being added are silently dropped.
     *
     * @return Collection<int, array{product: Product, qty: int, sum: float}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if ($cart === []) {
            return collect();
        }

        $rate = $this->rate();

        return Product::query()->active()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->map(fn (Product $product) => [
                'product' => $product,
                'qty' => $cart[$product->id],
                'sum' => round((float) $product->cost * $cart[$product->id] * $rate, 2),
            ])
            ->values();
    }

    /**
     * Cart total: Σ cost × qty × rate.
     */
    public function total(): float
    {
        return round($this->items()->sum('sum'), 2);
    }

    /**
     * Header counter: total number of units (no DB query).
     */
    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function isEmpty(): bool
    {
        return $this->raw() === [];
    }

    public function rate(): float
    {
        return (float) setting('rate', 1);
    }
}
