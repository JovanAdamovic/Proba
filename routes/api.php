<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredmetController;
use App\Http\Controllers\UpisController;
use App\Http\Controllers\ZadatakController;
use App\Http\Controllers\PredajaController;
use App\Http\Controllers\ProveraPlagijataController;

/*
|--------------------------------------------------------------------------
| Predmeti (Subject)
|--------------------------------------------------------------------------
| - Svi ulogovani: list + detalji
| - PROFESOR/ADMIN: CRUD (kreiranje/izmena/brisanje)
*/


Route::get('/predmeti', [PredmetController::class, 'index']);
Route::get('/predmeti/{id}', [PredmetController::class, 'show']);
Route::delete('/predmeti/{id}', [PredmetController::class, 'destroy']);
Route::put('/predmeti/{id}', [PredmetController::class, 'update']);
Route::post('/predmeti', [PredmetController::class, 'store']);


Route::get('/upisi', [UpisController::class, 'index']);
Route::get('/upisi/{id}', [UpisController::class, 'show']);
Route::post('/upisi', [UpisController::class, 'store']);
Route::put('/upisi/{id}', [UpisController::class, 'update']);
Route::delete('/upisi/{id}', [UpisController::class, 'destroy']);

Route::get('/zadaci', [ZadatakController::class, 'index']);
Route::get('/zadaci/{id}', [ZadatakController::class, 'show']);
Route::post('/zadaci', [ZadatakController::class, 'store']);
Route::put('/zadaci/{id}', [ZadatakController::class, 'update']);
Route::delete('/zadaci/{id}', [ZadatakController::class, 'destroy']);

Route::get('/predaje', [PredajaController::class, 'index']);
Route::get('/predaje/{id}', [PredajaController::class, 'show']);
Route::post('/predaje', [PredajaController::class, 'store']);
Route::put('/predaje/{id}', [PredajaController::class, 'update']);
Route::delete('/predaje/{id}', [PredajaController::class, 'destroy']);

Route::get('/provere-plagijata', [ProveraPlagijataController::class, 'index']);
Route::get('/provere-plagijata/{id}', [ProveraPlagijataController::class, 'show']);
Route::post('/provere-plagijata', [ProveraPlagijataController::class, 'store']);
Route::put('/provere-plagijata/{id}', [ProveraPlagijataController::class, 'update']);
Route::delete('/provere-plagijata/{id}', [ProveraPlagijataController::class, 'destroy']);
Route::post('/predaje/{predajaId}/provera-plagijata', [ProveraPlagijataController::class, 'pokreni']);





    //Route::apiResource('upisi', UpisController::class);
    /* Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('upisi', UpisController::class);
}); */






/* Route::middleware('auth:sanctum')->group(function () {

    // READ (svim ulogovanim)
    

    // WRITE (samo PROFESOR i ADMIN)
    Route::middleware('role:PROFESOR,ADMIN')->group(function () {
        Route::post('/predmeti', [PredmetController::class, 'store']);
        Route::put('/predmeti/{predmet}', [PredmetController::class, 'update']);
        Route::delete('/predmeti/{predmet}', [PredmetController::class, 'destroy']);
    });

}); */
