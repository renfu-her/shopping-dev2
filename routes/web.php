<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\MemberLoginController;
use App\Http\Controllers\Auth\MemberRegisterController;
use App\Http\Controllers\Auth\MemberPasswordResetController;

// Home page
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('products.category');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Member authentication routes
Route::middleware('guest:member')->group(function () {
    Route::get('/login', [MemberLoginController::class, 'showLoginForm'])->name('member.login');
    Route::post('/login', [MemberLoginController::class, 'login']);
    Route::get('/register', [MemberRegisterController::class, 'showRegistrationForm'])->name('member.register');
    Route::post('/register', [MemberRegisterController::class, 'register']);
    Route::get('/forgot-password', [MemberPasswordResetController::class, 'showLinkRequestForm'])->name('member.password.request');
    Route::post('/forgot-password', [MemberPasswordResetController::class, 'sendResetLinkEmail'])->name('member.password.email');
    Route::get('/reset-password/{token}', [MemberPasswordResetController::class, 'showResetForm'])->name('member.password.reset');
    Route::post('/reset-password', [MemberPasswordResetController::class, 'reset'])->name('member.password.update');
});

// Protected member routes
Route::middleware('auth:member')->group(function () {
    Route::post('/logout', [MemberLoginController::class, 'logout'])->name('member.logout');
    Route::get('/profile', [MemberLoginController::class, 'profile'])->name('member.profile');
    Route::put('/profile', [MemberLoginController::class, 'updateProfile'])->name('member.profile.update');
    
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Payment callback routes (no CSRF protection)
Route::post('/payment/result', [CheckoutController::class, 'paymentResult'])
    ->name('payment.result')
    ->middleware('restore.member.session');
Route::post('/payment/notify', [CheckoutController::class, 'paymentNotify'])
    ->name('payment.notify')
    ->middleware('restore.member.session');
