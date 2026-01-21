<?php

// routes/api.php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PredmetController;
use App\Http\Controllers\UpisController;
use App\Http\Controllers\ZadatakController;
use App\Http\Controllers\PredajaController;
use App\Http\Controllers\ProveraPlagijataController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register'); // ako ti treba
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', fn (Request $request) => response()->json($request->user()));
    Route::post('/logout', [AuthController::class, 'logout']);

    // "moje" rute (filterisane po ulozi)
    Route::get('/predmeti/moji', [PredmetController::class, 'moji']);
    Route::get('/zadaci/moji', [ZadatakController::class, 'moji']);
    Route::get('/predaje/moje', [PredajaController::class, 'moje']); // student
    Route::get('/predaje/za-moje-predmete', [PredajaController::class, 'zaMojePredmete']); // profesor

    // osnovne rute (ali u kontrolerima ograniči šta ko vidi)
    Route::get('/predmeti', [PredmetController::class, 'index']);
    Route::get('/predmeti/{id}', [PredmetController::class, 'show']);

    Route::get('/zadaci', [ZadatakController::class, 'index']);
    Route::get('/zadaci/{id}', [ZadatakController::class, 'show']);

    Route::get('/predaje', [PredajaController::class, 'index']);
    Route::get('/predaje/export/csv', [PredajaController::class, 'exportCsv']);
    Route::get('/predaje/{id}', [PredajaController::class, 'show']);

    // STUDENT akcije (ti si rekao: student ne menja predaju)
    Route::post('/predaje', [PredajaController::class, 'store']);
    Route::delete('/predaje/{id}', [PredajaController::class, 'destroy']);

    // PROFESOR akcije
    Route::post('/zadaci', [ZadatakController::class, 'store']);
    Route::put('/zadaci/{id}', [ZadatakController::class, 'update']);
    Route::delete('/zadaci/{id}', [ZadatakController::class, 'destroy']);
    Route::put('/predaje/{id}', [PredajaController::class, 'update']); // ocena/komentar/status

    // ✅ Provera plagijata — rute postoje, ali kontroler dozvoljava samo PROFESOR-u
    Route::get('/provere-plagijata', [ProveraPlagijataController::class, 'index']);
    Route::get('/provere-plagijata/{id}', [ProveraPlagijataController::class, 'show']);
    Route::post('/predaje/{predajaId}/provera-plagijata', [ProveraPlagijataController::class, 'pokreni']);

    // ADMIN CRUD (predmeti/upisi) - ako ti treba
    Route::post('/predmeti', [PredmetController::class, 'store']);
    Route::put('/predmeti/{id}', [PredmetController::class, 'update']);
    Route::delete('/predmeti/{id}', [PredmetController::class, 'destroy']);

    Route::get('/upisi', [UpisController::class, 'index']);
    Route::get('/upisi/{id}', [UpisController::class, 'show']);
    Route::post('/upisi', [UpisController::class, 'store']);
    Route::put('/upisi/{id}', [UpisController::class, 'update']);
    Route::delete('/upisi/{id}', [UpisController::class, 'destroy']);
});

