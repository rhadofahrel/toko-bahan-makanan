<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ── AUTH routes (guest only) ─────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ── User / Customer routes (login required) ───────────────────────────────────
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'getProducts'])->name('products.get');
    Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [ProductController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/update', [ProductController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [ProductController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/checkout', [ProductController::class, 'checkout'])->name('checkout');
    Route::post('/cart/reset', [ProductController::class, 'resetTransaction'])->name('cart.reset');
});

// ── Admin routes (login + admin role required) ────────────────────────────────
Route::prefix('admin')->middleware(['auth.custom', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.home');
    Route::post('/products', [AdminController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{id}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/all', [AdminController::class, 'getAll'])->name('admin.products.getAll');
});

use Illuminate\Support\Facades\Response;

Route::get('/products/{filename}', function ($filename) {
    $path = public_path('products/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
});