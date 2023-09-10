<?php

use App\Http\Controllers\AsaasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Webhook Assas
Route::post('assas', [AsaasController::class, 'receberPagamento'])->name('assas');

//Gera Pagamento
Route::post('geraPagamento', [AsaasController::class, 'geraPagamento'])->name('geraPagamento');

