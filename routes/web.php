<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
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


// Route cho Account
Route::controller(AccountController::class)->group(function () {
    Route::get('register', 'register')->name('register.form');
    Route::post('register', 'register_')->name('register');

    Route::get('login', 'login')->name('login.form');
    Route::post('login', 'login_')->name('login');

    Route::get('password/forgot', 'rspassword')->name('password.forgot.form');
    Route::post('password/forgot', 'rspassword_')->name('password.forgot');

    Route::get('password/reset/{token}', 'updatepassword')->name('password.reset');
    Route::post('password/reset', 'updatepassword_')->name('password.update');

    Route::post('logout', 'logout')->name('logout');
});

//Các route cho client
Route::controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('clients/shop', 'shop')->name('shop');
    Route::get('clients/carts', 'carts')->name('carts');
    Route::get('clients/contact', 'contact')->name('contact');
    Route::get('clients/about', 'about')->name('about');
    Route::get('clients/detail', 'detail')->name('detail');
    Route::get('clients/checkout', 'checkout')->name('checkout');
    Route::get('ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
});

// Route cho Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('admin/dashboard',  'index')->name('admin.dashboard');
    Route::resource('admin/categories', CategoryController::class);
    Route::resource('admin/tickets', TicketController::class);
});


// Route cho User
Route::controller(UserController::class)->group(function () {
    Route::get('user/dashboard', 'user')->name('user.dashboard');

    Route::get('user/change-password', 'changepass')->name('user.changepass.form');
    Route::post('user/change-password', 'changepass_')->name('user.password.change');

    Route::get('user/edit', 'edit')->name('user.edit');
    Route::post('user/update', 'update')->name('user.update');
});