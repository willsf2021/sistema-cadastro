<?php

use App\Http\Controllers\CargoController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\CargoPessoaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('cargos', CargoController::class);
Route::get('/cargos/{id}/vinculos', [CargoController::class, 'verificarVinculos']);
Route::apiResource('pessoas', PessoaController::class);
Route::apiResource('cargo-pessoa', CargoPessoaController::class);
Route::get('historico-cargo/{pessoa_id}', [CargoPessoaController::class, 'historico']);