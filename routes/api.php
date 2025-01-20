<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/list-products', [ProductController::class, 'index']);
Route::post('/products/store', [ProductController::class, 'store']);
Route::get('/products/show/{id}', [ProductController::class, 'show']);
Route::put('/products/update/{id}', [ProductController::class, 'update']);
Route::delete('/products/delet/{id}', [ProductController::class, 'destroy']);

Route::get('/list/posts', [PostController::class, 'index']);
Route::post('/post/store', [PostController::class, 'store']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/create', [UserController::class, 'store']);
Route::post('/users/login', [UserController::class, 'login']);

