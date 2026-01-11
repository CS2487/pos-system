<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
// Language Switcher Route
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');


// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // POS - accessible by both admin and cashier
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    
    // Orders - view only for cashiers, full access for admin
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');
    Route::post('/orders/{id}/return', [OrderController::class, 'returnOrder'])->name('orders.return');
    
    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        // Products
        Route::resource('products', ProductController::class);
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Customers
        Route::resource('customers', CustomerController::class);
        
        // Suppliers
        Route::resource('suppliers', SupplierController::class);
        
        // Purchases
        Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
        
        // Orders - admin can update/delete
        Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
