<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredmetController;
use App\Http\Controllers\UpisController;


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
