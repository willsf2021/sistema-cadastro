<?php
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\HistoricoCargoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('cargos', CargoController::class);
Route::resource('pessoas', PessoaController::class);
Route::resource('historico-cargos', HistoricoCargoController::class);
