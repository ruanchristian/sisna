<?php

use App\Http\Controllers\Result\ResultController;
use App\Http\Controllers\Audit\AuditController;
use App\Http\Controllers\Changelog\ChangelogController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\SelectiveProcess\SelectiveProcessController;
use App\Http\Controllers\SpecialConfig\SpecialConfigController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes(['register' => false]);

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'can:isAdmin, App\\Models\\User'], function() {

    Route::controller(ResultController::class)->prefix('results')->group(function() {
        Route::name('resultado.')->group(function() {
            Route::get('/{id}', 'index')->name('index');
            Route::post('/pdf', 'gerarPdf')->name('pdf');
        });
    });

    Route::controller(SelectiveProcessController::class)->prefix('processes')->group(function () {
        Route::name('process.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/create', 'store')->name('store');
            Route::put('/change-state/{id}', 'updateState')->name('update-state');
        });
    });
    
    Route::controller(CourseController::class)->prefix('courses')->group(function () {
        Route::name('course.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/request-course/{id}', 'getCourse');
            Route::post('/create-course', 'store')->name('store');
            Route::put('/edit-course/{id}', 'update')->name('update');
        });
    });

    Route::controller(ChangelogController::class)->prefix('changelog')->group(function () {
        Route::name('changelog.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::get('/edit/{changelog}', 'edit')->name('edit');
            Route::put('/edit/{changelog}', 'update')->name('update');
            Route::delete('/delete/{changelog}', 'destroy')->name('destroy');
        });
    });

    Route::controller(SpecialConfigController::class)->prefix('special-configs')->group(function () {
        Route::name('configs.')->group(function() {
            Route::get('/{id}', 'index')->name('index');
            Route::put('/save-order/{process}', 'update')->name('update');
        });     
    });

    Route::controller(AuditController::class)->prefix('audits')->group(function () {
        Route::get('/', 'index')->name('audit.index');
    });
});

Route::middleware('auth')->prefix('changelog')->name('changelog.')->group(function () {
    Route::get('/', [ChangelogController::class, 'index'])->name('index');
    Route::get('/{changelog}', [ChangelogController::class, 'show'])
        ->whereNumber('changelog')
        ->name('show');
});

Route::controller(UserController::class)->middleware('auth')->prefix('users')->group(function () {
    Route::name('user.')->group(function () {
        Route::get('/', 'index')->name('index');

        Route::middleware('can:isAdmin, App\\Models\\User')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/create', 'store')->name('store');
            Route::post('/checkpass', 'checkPassword');
            Route::put('/edit/{id}', 'update')->name('update');
            Route::get('/request/{id}', 'getUserById')->name('request');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
        });
    });
});

Route::controller(StudentController::class)->middleware('auth')->prefix('students')->group(function () {
    Route::name('student.')->group(function () {
        Route::middleware('can:isAdmin, App\\Models\\User')->group(function() {
            Route::get('/lotes/{id}', 'lotes')->name('lotes');
        });

        Route::get('/visualization/{processId}', 'viewStudents')->name('visualization');
        Route::get('/create/{processId}', 'index')->name('index');
        Route::get('/edit/{process}/{student}', 'edit')->name('edit');
        Route::post('/create/{process}', 'store')->name('create');
        Route::put('/update/{student}', 'update')->name('update');
    });
});