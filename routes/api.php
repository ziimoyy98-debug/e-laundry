<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\OrderController;
Route::apiResource('orders', OrderController::class);
Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus']);
