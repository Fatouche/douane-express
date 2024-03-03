<?php

use App\Http\Controllers\API\OrdersController;
use App\Http\Controllers\API\OrderServiceController;
use App\Http\Controllers\API\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('services', [ServicesController::class, 'add']);
Route::get('services', [ServicesController::class, 'getAll']);
Route::delete('services/{id}', [ServicesController::class, 'delete']);
Route::get('services/{id}', [ServicesController::class, 'getOne']);
Route::patch('services/{id}', [ServicesController::class, 'update']);
Route::post('services/{id}/upload-image', [ServicesController::class, 'uploadImage']);

Route::post('orders', [OrdersController::class, 'add']);
Route::get('orders', [OrdersController::class, 'getAll']);
Route::delete('orders/{id}', [OrdersController::class, 'delete']);
Route::get('orders/{id}', [OrdersController::class, 'getOne']);
Route::patch('orders/{id}', [OrdersController::class, 'update']);

Route::post('orders/{id}/services', [OrderServiceController::class, 'addServiceToOrder']);
Route::patch('orders/{postId}/services/{serviceId}', [OrderServiceController::class, 'updateServiceToOrder']);
Route::delete('orders/{postId}/services/{serviceId}', [OrderServiceController::class, 'deleteServiceToOrder']);
