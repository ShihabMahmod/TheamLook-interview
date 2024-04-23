<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/pos', [ProductController::class, 'index'])->name('pos');
Route::post('/checkout', [ProductController::class, 'order'])->name('checkout');
Route::get('/order-list', [ProductController::class, 'orderList'])->name('order-list');