<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\OrderApiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;

// In a real application, these should be protected via 'auth:sanctum' and role checking middleware.

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Customer Checkout
Route::post('/checkout', [OrderApiController::class, 'checkout']);

// Admin Dashboard
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

// Seller Dashboard
Route::get('/seller/{store_id}/dashboard', [SellerDashboardController::class, 'index']);
