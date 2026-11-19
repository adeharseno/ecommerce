<?php

use App\Http\Controllers\Admin\OrderCmsController;
use App\Http\Controllers\Admin\ProductCmsController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\ProductController;
use Illuminate\Support\Facades\Route;

Route::pattern('invoice', 'INV\/[0-9]{8}\/[0-9]+|[A-Za-z0-9\-_]+');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{invoice}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/proof/{invoice}', [CheckoutController::class, 'uploadProof'])->name('checkout.proof');

// Static pages
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Admin CMS Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/products');
    Route::resource('products', ProductCmsController::class)->except(['show']);

    Route::get('/orders', [OrderCmsController::class, 'index'])->name('orders.index');
    Route::get('/orders/{invoice}', [OrderCmsController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{invoice}/status', [OrderCmsController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{invoice}/proof', [OrderCmsController::class, 'proof'])->name('orders.proof');
});
