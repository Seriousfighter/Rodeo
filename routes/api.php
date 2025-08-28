<?php

use App\Http\Controllers\Api\DietApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/diets', [DietApiController::class, 'index']);