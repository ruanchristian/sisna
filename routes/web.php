<?php

use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\SelectiveProcess\SelectiveProcessController;
use App\Http\Controllers\SpecialConfig\SpecialConfigController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\User\UserController;
use App\Models\SelectiveProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => FALSE]);

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'can:isAdmin, \App\Models\User'], function() {

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
    
    Route::controller(StudentController::class)->prefix('students')->group(function () {
        Route::name('student.')->group(function () {
            Route::get('/', 'index');
        });
    });

    Route::controller(SpecialConfigController::class)->prefix('special-configs')->group(function () {
        Route::name('configs.')->group(function() {
            Route::get('/{id}', 'index')->name('index');
            Route::put('/save-order/{process}', 'update')->name('update');
        });     
    });
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

// Algoritmo de seleção
Route::get('rsa/{courseId}', function ($courseId) {
    $process = SelectiveProcess::findOrFail(1);
    // $courses = explode('-', $process->cursos);

    $students = $process->students()->where('curso_id', $courseId)
        ->orderByDesc('media_final')
        ->orderBy('data_nascimento')
        ->orderByDesc('media_pt')
        ->orderByDesc('media_mt')->get();

    $origens = [
        'PCD' => 2,
        'PUBLICA-AMPLA' => 25,
        'PUBLICA-PROX-EEEP' => 10,
        'PRIVATE-AMPLA' => 6,
        'PRIVATE-PROX-EEEP' => 2,
    ];

    $topStudents = collect($origens)->flatMap(function ($vagas, $origem) use ($students) {
        return $students->where('origem', $origem)->take($vagas);
    });

    $allClassifiableStudents = $students->diff($topStudents);
    $publicClassifiable = $allClassifiableStudents->whereIn('origem', ['PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP']);
    $privateClassifiable = $allClassifiableStudents->whereIn('origem', ['PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP']);

    // Resultado
    SelectiveProcessController::showResult($topStudents, $origens);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
