<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/products/search', [ProductController::class, 'search']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
});

Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function() {
    Route::get('/admin/dashboard', function() {
        return response()->json(["message" => 'message salah']);
    });
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/categories', CategoryController::class);
});
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/sign-in', [AuthController::class, 'signIn']);
Route::get('/categories/{category}/products', [CategoryController::class, 'filterByCategory']);
