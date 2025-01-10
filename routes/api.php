<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});
