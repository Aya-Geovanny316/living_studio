<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function home()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug'])
            ->filter(fn ($category) => $category->name && $category->slug)
            ->values();

        $featuredProducts = Product::query()
            ->where('is_active', true)
            ->where('featured', true)
            ->latest()
            ->take(6)
            ->get();

        $promotions = Promotion::query()
            ->with('product')
            ->where('is_active', true)
            ->where('type', 'promo')
            ->orderBy('sort_order')
            ->get();

        $novedades = Promotion::query()
            ->with('product')
            ->where('is_active', true)
            ->where('type', 'novedad')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Public/Home', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'promotions' => $promotions,
            'novedades' => $novedades,
        ]);
    }

    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $brands = Product::query()
            ->where('is_active', true)
            ->whereNotNull('short_description')
            ->where('short_description', '!=', '')
            ->distinct()
            ->orderBy('short_description')
            ->pluck('short_description')
            ->values();

        $query = Product::query()
            ->where('is_active', true)
            ->with([
                'category',
                'promotions' => fn ($q) => $q->where('is_active', true)->whereNotNull('discount_percent'),
            ]);

        if ($request->filled('category')) {
            $categorySlugs = $request->input('category');
            $categorySlugs = is_array($categorySlugs) ? $categorySlugs : [$categorySlugs];
            $categorySlugs = array_values(array_filter($categorySlugs));
            if (count($categorySlugs)) {
                $query->whereHas('category', function ($q) use ($categorySlugs) {
                    $q->whereIn('slug', $categorySlugs);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('stock_status', 'like', "%{$search}%");
            });
        }

        $sort = $request->string('sort')->toString();
        if ($sort === 'price_asc') {
            $query->orderBy('price_estimate');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price_estimate');
        } else {
            $query->latest();
        }

        if ($request->boolean('featured')) {
            $query->where('featured', true);
        }

        if ($request->boolean('discounted')) {
            $query->whereHas('promotions', function ($q) {
                $q->where('is_active', true)->whereNotNull('discount_percent');
            });
        }

        if ($request->filled('brand')) {
            $brandsFilter = $request->input('brand');
            $brandsFilter = is_array($brandsFilter) ? $brandsFilter : [$brandsFilter];
            $brandsFilter = array_values(array_filter($brandsFilter));
            if (count($brandsFilter)) {
                $query->whereIn('short_description', $brandsFilter);
            }
        }

        $products = $query->paginate(12)->withQueryString();
        $products->getCollection()->transform(function ($product) {
            $discount = $product->promotions->max('discount_percent') ?? 0;
            $product->setAttribute('discount_percent', (int) $discount);
            return $product;
        });

        $filters = $request->only(['category', 'search', 'sort', 'featured', 'discounted', 'brand']);
        if (isset($filters['category']) && ! is_array($filters['category'])) {
            $filters['category'] = [$filters['category']];
        }
        if (isset($filters['brand']) && ! is_array($filters['brand'])) {
            $filters['brand'] = [$filters['brand']];
        }

        return Inertia::render('Public/Catalog', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'filters' => $filters,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::query()
            ->with([
                'category',
                'promotions' => fn ($q) => $q->where('is_active', true)->whereNotNull('discount_percent'),
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $discount = $product->promotions->max('discount_percent') ?? 0;
        $product->setAttribute('discount_percent', (int) $discount);

        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return Inertia::render('Public/Product', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
