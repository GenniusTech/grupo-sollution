<?php

use App\Http\Controllers\NomeController;
use App\Http\Controllers\RelatorioController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/consultar', [NomeController::class, 'consulta'])->name('consultar');
Route::post('/geraListaExcel', [RelatorioController::class, 'geraListaExcel'])->name('geraListaExcel');
Route::post('/geraListaZip', [RelatorioController::class, 'geraListaZip'])->name('geraListaZip');
