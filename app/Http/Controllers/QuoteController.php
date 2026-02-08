<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Mail\QuoteSubmitted;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\CartService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class QuoteController extends Controller
{
    public function create(CartService $cart)
    {
        if (count($cart->items()) === 0) {
            return redirect()->route('cart.index')->with('error', 'Agrega productos antes de solicitar una cotizacion.');
        }

        return Inertia::render('Public/Quote', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'notes' => $cart->notes(),
        ]);
    }

    public function store(StoreQuoteRequest $request, CartService $cart)
    {
        $items = $cart->items();
        if (count($items) === 0) {
            return redirect()->route('cart.index')->with('error', 'Agrega productos antes de solicitar una cotizacion.');
        }

        $quoteNumber = $this->generateQuoteNumber();

        $quote = Quote::create([
            'quote_number' => $quoteNumber,
            'user_id' => $request->user()?->id,
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'address' => $request->filled('address') ? $request->string('address')->toString() : null,
            'notes' => $request->filled('notes') ? $request->string('notes')->toString() : null,
            'status' => 'new',
            'subtotal_estimate' => $cart->subtotal(),
        ]);

        foreach ($items as $item) {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $item['product_id'],
                'product_name_snapshot' => $item['name'],
                'unit_price_estimate' => $item['price'],
                'qty' => $item['qty'],
                'line_total_estimate' => $item['price'] * $item['qty'],
            ]);
        }

        $adminEmail = config('mail.quotes_to', config('mail.from.address'));
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new QuoteSubmitted($quote));
        }

        if ($quote->email) {
            Mail::to($quote->email)->send(new QuoteSubmitted($quote, true));
        }

        $cart->clear();

        return Inertia::render('Public/QuoteSuccess', ['quote' => $quote]);
    }

    private function generateQuoteNumber(): string
    {
        do {
            $candidate = 'LS-' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while (Quote::where('quote_number', $candidate)->exists());

        return $candidate;
    }
}
