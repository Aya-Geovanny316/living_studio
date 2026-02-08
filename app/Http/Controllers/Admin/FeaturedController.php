<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeaturedController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderByDesc('featured')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'featured', 'category_id']);

        return Inertia::render('Admin/Featured/Index', [
            'products' => $products,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'featured_ids' => ['nullable', 'array'],
            'featured_ids.*' => ['integer', 'exists:products,id'],
        ]);

        $ids = $data['featured_ids'] ?? [];

        Product::query()->update(['featured' => false]);
        if (count($ids)) {
            Product::whereIn('id', $ids)->update(['featured' => true]);
        }

        return back()->with('success', 'Destacados actualizados.');
    }
}
