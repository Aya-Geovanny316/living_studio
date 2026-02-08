<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuoteReplyRequest;
use App\Http\Requests\Admin\QuoteStatusRequest;
use App\Mail\QuoteResponse;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        $quotes = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Quotes/Index', [
            'quotes' => $quotes,
            'filters' => $request->only(['status', 'from', 'to']),
        ]);
    }

    public function show(Quote $quote)
    {
        $quote->load('items');

        return Inertia::render('Admin/Quotes/Show', [
            'quote' => $quote,
        ]);
    }

    public function updateStatus(QuoteStatusRequest $request, Quote $quote)
    {
        $quote->update(['status' => $request->string('status')]);

        return redirect()->back()->with('success', 'Estado actualizado.');
    }

    public function reply(QuoteReplyRequest $request, Quote $quote)
    {
        $data = $request->validated();

        $quote->update([
            'response_message' => $data['response_message'],
            'response_total_estimate' => $data['response_total_estimate'] ?? null,
            'status' => 'quoted',
        ]);

        Mail::to($quote->email)->send(new QuoteResponse($quote));

        return redirect()->back()->with('success', 'Respuesta enviada al cliente.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Quote::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $quotes = $query->with('items')->get();

        $response = new StreamedResponse(function () use ($quotes) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'quote_number',
                'name',
                'email',
                'phone',
                'status',
                'subtotal_estimate',
                'created_at',
                'items',
            ]);

            foreach ($quotes as $quote) {
                $items = $quote->items->map(function ($item) {
                    return "{$item->product_name_snapshot} x{$item->qty}";
                })->implode(' | ');

                fputcsv($handle, [
                    $quote->quote_number,
                    $quote->name,
                    $quote->email,
                    $quote->phone,
                    $quote->status,
                    $quote->subtotal_estimate,
                    $quote->created_at->format('Y-m-d'),
                    $items,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="quotes.csv"');

        return $response;
    }
}
