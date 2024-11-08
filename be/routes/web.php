<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NFTController;
use App\Http\Controllers\TicketController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create-collection-form', [NFTController::class, 'createCollectionForm']);
Route::post('/create-collection', [NFTController::class, 'createCollection']);
Route::get('/create', [NFTController::class, 'createForm']);
Route::get('/create-nft', [NFTController::class, 'createNFT']);



Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::resource('categories', CategoryController::class);
Route::resource('tickets', TicketController::class);