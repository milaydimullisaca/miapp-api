<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('tasks', TaskController::class);

    Route::apiResource('personas', PersonaController::class);
});

/*
|--------------------------------------------------------------------------
| Pokemones
|--------------------------------------------------------------------------
*/

Route::get('/pokemones', function () {

    $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=50');

    return response()->json([
        'total' => $response['count'],
        'pokemones' => $response['results']
    ]);
});