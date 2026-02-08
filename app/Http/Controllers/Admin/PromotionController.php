<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionRequest;
use App\Models\Promotion;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderBy('sort_order')->paginate(15);

        return Inertia::render('Admin/Promotions/Index', [
            'promotions' => $promotions,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Promotions/Create', [
            'products' => \App\Models\Product::orderBy('name')->get(['id', 'name', 'price_estimate', 'slug']),
        ]);
    }

    public function store(PromotionRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['discount_percent'] = $request->filled('discount_percent')
            ? (int) $request->input('discount_percent')
            : null;
        $data['image_path'] = $this->storeImage($request);
        if (empty($data['link']) && ! empty($data['product_id'])) {
            $product = \App\Models\Product::find($data['product_id']);
            if ($product) {
                $data['link'] = url('/producto/' . $product->slug);
            }
        }

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promocion creada.');
    }

    public function edit(Promotion $promotion)
    {
        return Inertia::render('Admin/Promotions/Edit', [
            'promotion' => $promotion,
            'products' => \App\Models\Product::orderBy('name')->get(['id', 'name', 'price_estimate', 'slug']),
        ]);
    }

    public function update(PromotionRequest $request, Promotion $promotion)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['discount_percent'] = $request->filled('discount_percent')
            ? (int) $request->input('discount_percent')
            : null;

        $imagePath = $this->storeImage($request);
        if ($imagePath) {
            $data['image_path'] = $imagePath;
        }
        if (empty($data['link']) && ! empty($data['product_id'])) {
            $product = \App\Models\Product::find($data['product_id']);
            if ($product) {
                $data['link'] = url('/producto/' . $product->slug);
            }
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promocion actualizada.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promocion eliminada.');
    }

    private function storeImage(PromotionRequest $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('promotions', 'public');

        return '/storage/' . ltrim($path, '/');
    }
}
