<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('work-orders', WorkOrderController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('sales-orders', SalesOrderController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
