<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/



//Products
Route::get('product', [ProductController::class, 'index']);
Route::post('product', [ProductController::class, 'create']);
Route::get('product/{find}', [ProductController::class, 'show']);


//Order
Route::post('order', [OrderController::class, 'create']);
Route::get('order/{id}', [OrderController::class, 'show']);
Route::delete('order/{id}', [OrderController::class, 'destroy']);


