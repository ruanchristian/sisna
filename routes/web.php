<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => FALSE]);

Route::get('/', function () {
    return view('auth.login');
});

Route::controller(UserController::class)->middleware('auth')->prefix('users')->group(function () {
    Route::name('user.')->group(function () {
      Route::get('/', 'index')->name('index');

        Route::middleware('can:isAdmin,App\Models\User')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::put('/edit/{id}', 'update')->name('update');
            Route::get('/request/{id}', 'getUserById')->name('request');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

