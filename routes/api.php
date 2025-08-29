<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DietApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


###################################################################################################################################################
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
    //->middleware('guest'); // Solo permite acceso a usuarios no autenticados
Route::post('/', function () {
    return response()->json([
        'message' => 'Rodeo diet api.',
        'error' => 'no autenticado'
    ], 401);
})->name('login');
###################################################################################################################################################



/**
 * Rutas para manejar las dietas
 * De momento no requieren autenticación
 * las comentadas estan con autenticación. para lo cual se
 * usa el login de arriba: 
 * post /login
 * Content-Type: application/json
 * {
 *      "email": "alan@alan.com",
 *      "password": "12345678"
 * }
 * esto devuelve un token que se usa en las siguientes peticiones:
 * Authorization: Bearer {token}
 */
Route::get('/diets', [DietApiController::class, 'index']);
Route::post('/diets', [DietApiController::class, 'mockDataPost']);
/*Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/diets', [DietApiController::class, 'mockData']);
    Route::post('/diets', [DietApiController::class, 'mockDataPost']);
});*/