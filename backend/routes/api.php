<?php
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\HistoricoCargoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('cargos', CargoController::class);
Route::apiResource('pessoas', PessoaController::class);
Route::get('pessoas/{pessoa}/historico-cargos', [PessoaController::class, 'historicoCargos']);
Route::apiResource('historico-cargos', HistoricoCargoController::class);