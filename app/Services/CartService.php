<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;

class CartService
{
    public function __construct(private Session $session)
    {
    }

    public function items(): array
    {
        return $this->session->get('cart.items', []);
    }

    public function add(Product $product, int $qty = 1): void
    {
        $items = $this->items();
        $id = (string) $product->id;

        if (isset($items[$id])) {
            $items[$id]['qty'] += $qty;
        } else {
            $items[$id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price_estimate,
                'qty' => $qty,
                'image' => $product->images[0] ?? null,
            ];
        }

        $this->session->put('cart.items', $items);
    }

    public function update(int $productId, int $qty): void
    {
        $items = $this->items();
        $id = (string) $productId;

        if (! isset($items[$id])) {
            return;
        }

        if ($qty <= 0) {
            unset($items[$id]);
        } else {
            $items[$id]['qty'] = $qty;
        }

        $this->session->put('cart.items', $items);
    }

    public function remove(int $productId): void
    {
        $items = $this->items();
        $id = (string) $productId;

        if (isset($items[$id])) {
            unset($items[$id]);
        }

        $this->session->put('cart.items', $items);
    }

    public function clear(): void
    {
        $this->session->forget('cart.items');
        $this->session->forget('cart.notes');
    }

    public function subtotal(): float
    {
        $total = 0.0;
        foreach ($this->items() as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return $total;
    }

    public function notes(): ?string
    {
        return $this->session->get('cart.notes');
    }

    public function setNotes(?string $notes): void
    {
        if ($notes) {
            $this->session->put('cart.notes', $notes);
        } else {
            $this->session->forget('cart.notes');
        }
    }
}
