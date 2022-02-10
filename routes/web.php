<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');

Route::post('/products/update', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');

Route::get('products/delete/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('product.delete');

Route::get('/products/csv', [App\Http\Controllers\ProductController::class, 'productsExportCsv'])->name('products.csv');

Route::get('/products/search', [App\Http\Controllers\ProductController::class, 'search'])->name('products.search');

Auth::routes();

Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
