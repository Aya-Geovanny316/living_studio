<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return Inertia::render('Public/Cart', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'notes' => $cart->notes(),
        ]);
    }

    public function add(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $cart->add($product, $data['qty'] ?? 1);

        return redirect()->back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($data['items'] as $productId => $qty) {
            $cart->update((int) $productId, (int) $qty);
        }

        $cart->setNotes($data['notes'] ?? null);

        return redirect()->route('cart.index')->with('success', 'Carrito actualizado.');
    }

    public function remove(int $productId, CartService $cart)
    {
        $cart->remove($productId);

        return redirect()->route('cart.index')->with('success', 'Producto eliminado.');
    }
}
