<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| 🔓 RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 🔐 RUTAS PROTEGIDAS (SANCTUM)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // 👤 USUARIO LOGUEADO
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | 📌 TASKS
    |--------------------------------------------------------------------------
    */

    Route::apiResource('tasks', TaskController::class);

    // 🔥 CHECKBOX (COMPLETAR / DESMARCAR)
    Route::put('/tasks/{id}/toggle', [TaskController::class, 'toggleStatus']);

    /*
    |--------------------------------------------------------------------------
    | 👥 PERSONAS
    |--------------------------------------------------------------------------
    */

    Route::apiResource('personas', PersonaController::class);
});

/*
|--------------------------------------------------------------------------
| 🔥 API EXTERNA (POKEMON)
|--------------------------------------------------------------------------
*/

Route::get('/pokemones', function () {

    $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=50');

    return response()->json([
        'total' => $response['count'],
        'pokemones' => $response['results']
    ]);
});