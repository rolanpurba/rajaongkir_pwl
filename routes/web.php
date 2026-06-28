<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Halaman utama toko (publik, tidak perlu login)
Route::get('/', [ProductController::class, 'home'])->name('home');

// Routes yang butuh login
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/search-destination', [CheckoutController::class, 'searchDestination'])->name('checkout.searchDestination');
    Route::get('/checkout/cost', [CheckoutController::class, 'getCost'])->name('checkout.cost');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPayment'])->name('orders.payment');
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirmPayment'])->name('orders.confirm');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/confirm', [OrderController::class, 'confirmPayment'])->name('orders.confirm');
});

require __DIR__.'/auth.php';
