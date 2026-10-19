<?php

use App\Http\Controllers\Api\V1\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/status', StatusController::class);

    // Rotas públicas (Visitante): cadastro, login, catálogo, busca...

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', fn (Request $request) => $request->user());
    });
});
