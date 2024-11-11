<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', [ClientController::class, 'index'])->name('index');
Route::get('/shop', [ClientController::class, 'shop'])->name('shop');
Route::get('/carts', [ClientController::class, 'carts'])->name('carts');
Route::get('/contact', [ClientController::class, 'contact'])->name('contact');
Route::get('/about', [ClientController::class, 'about'])->name('about');
Route::get('/detail', [ClientController::class, 'detail'])->name('detail');


Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::resource('categories', CategoryController::class);
Route::resource('tickets', TicketController::class);
