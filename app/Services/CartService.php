<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Manages the shopping cart via PHP session.
 *
 * Session structure:
 *
 * @var array<int, array{
 *   id: int,
 *   name: string,
 *   slug: string,
 *   price: float,
 *   image: string,
 *   quantity: int,
 * }>
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, string $name, string $slug, float $price, string $image, int $qty = 1): void
    {
        $cart = $this->all();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $qty;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'name' => $name,
                'slug' => $slug,
                'price' => $price,
                'image' => $image,
                'quantity' => $qty,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function updateQuantity(int $productId, int $qty): void
    {
        $cart = $this->all();

        if (! isset($cart[$productId])) {
            return;
        }

        if ($qty < 1) {
            $this->remove($productId);

            return;
        }

        $cart[$productId]['quantity'] = $qty;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function total(): float
    {
        return collect($this->all())
            ->sum(fn (array $item): float => $item['price'] * $item['quantity']);
    }

    public function count(): int
    {
        return collect($this->all())->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }
}
