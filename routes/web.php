<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FeaturedController as AdminFeaturedController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('product.show');

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/actualizar', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{productId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/cotizacion', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/cotizacion', [QuoteController::class, 'store'])->name('quote.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/mi-cuenta', [AccountController::class, 'dashboard'])->name('customer.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::delete('products/bulk', [AdminProductController::class, 'bulkDestroy'])->name('products.bulk.destroy');
    Route::get('products/import', [AdminProductController::class, 'importForm'])->name('products.import');
    Route::post('products/import', [AdminProductController::class, 'importStoreXlsx'])->name('products.import.store');
    Route::get('products/import/template', [AdminProductController::class, 'importTemplateXlsx'])->name('products.import.template');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::delete('categories/bulk', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk.destroy');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('promotions', AdminPromotionController::class)->except(['show']);
    Route::get('featured', [AdminFeaturedController::class, 'index'])->name('featured.index');
    Route::post('featured', [AdminFeaturedController::class, 'update'])->name('featured.update');
    Route::get('quotes/export', [AdminQuoteController::class, 'export'])->name('quotes.export');
    Route::post('quotes/{quote}/reply', [AdminQuoteController::class, 'reply'])->name('quotes.reply');
    Route::patch('quotes/{quote}/status', [AdminQuoteController::class, 'updateStatus'])->name('quotes.status');
    Route::resource('quotes', AdminQuoteController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
