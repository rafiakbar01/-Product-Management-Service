<?php

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Redirect root to products catalog
Route::redirect('/', '/products');

// Web UI Routes for Product Management
Route::resource('products', ProductController::class);

// Monitoring and Telemetry Dashboard
Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

// RESTful API Routes (V1)
Route::prefix('api/v1')->group(function () {
    Route::get('products', [ProductApiController::class, 'index'])->name('api.products.index');
    Route::post('products', [ProductApiController::class, 'store'])->name('api.products.store');
    Route::get('products/statistics', [ProductApiController::class, 'statistics'])->name('api.products.statistics');
    Route::get('products/alerts/low-stock', [ProductApiController::class, 'alerts'])->name('api.products.alerts');
    Route::get('products/{id}', [ProductApiController::class, 'show'])->name('api.products.show');
    Route::put('products/{id}', [ProductApiController::class, 'update'])->name('api.products.update');
    Route::delete('products/{id}', [ProductApiController::class, 'destroy'])->name('api.products.destroy');
});
