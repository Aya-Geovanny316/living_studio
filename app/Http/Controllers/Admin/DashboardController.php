<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Quote;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'quotes_new' => Quote::where('status', 'new')->count(),
            'products_active' => Product::where('is_active', true)->count(),
            'categories_active' => Category::where('is_active', true)->count(),
            'promotions_active' => Promotion::where('is_active', true)->count(),
        ];

        $latestQuotes = Quote::latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => $metrics,
            'latestQuotes' => $latestQuotes,
        ]);
    }
}
