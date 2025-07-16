<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\RodeoController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RecordingController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('rodeos', RodeoController::class);
    Route::resource('animals', AnimalController::class);
    Route::resource('groups', GroupController::class);
    Route::post('groups/{id}/animals', [GroupController::class, 'addAnimals']);
    Route::delete('groups/{id}/animals', [GroupController::class, 'removeAnimals']);
    Route::get('groups/{id}/index', [GroupController::class, 'rodeoGroups'])->name('rodeo.groups');

    //Route::delete('animals/bulk-destroy', [AnimalController::class, 'bulkDestroy'])->name('animals.bulkDestroy');

    //recording routes
     Route::resource('recordings', RecordingController::class)->except(['create']);
     Route::get('recordings/create/{animalId}', [RecordingController::class, 'create'])->name('recordings.create');
    
   
});
