<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendasController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

//Login
Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/', [UserController::class, 'login_action'])->name('login_action');

//Produtos
Route::get('/limpanome/{id}', [ProdutoController::class, 'limpaNome'])->name('limpanome');
Route::get('/kannanda/{id}', [ProdutoController::class, 'kannanda'])->name('kannanda');
Route::get('/verinha/{id}', [ProdutoController::class, 'verinha'])->name('verinha');

//Vendas
Route::post('/vender/{id}', [VendasController::class, 'vender'])->name('vender');


//Autenticados
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');

    Route::get('/vendas/{id}', [VendasController::class, 'getVendas'])->name('vendas');
    Route::post('vendas', [VendasController::class, 'getVendas'])->name('vendas');

    Route::get('/vendaDireta/{id}', [VendasController::class, 'vendaDireta'])->name('vendaDireta');
    Route::post('action_vendaDireta', [VendasController::class, 'action_vendaDireta'])->name('action_vendaDireta');

    Route::get('/usuario', [UserController::class, 'usuario'])->name('usuario');
    Route::post('usuario', [UserController::class, 'action_usuario'])->name('usuario');

    Route::get('/perfil',[UserController::class, 'perfil'])->name('perfil');
    Route::post('perfil', [UserController::class, 'action_perfil'])->name('perfil');

    Route::get('/saque', [FinanceiroController::class, 'index'])->name('saque');
    Route::post('saque', [FinanceiroController::class, 'saque'])->name('saque');
    Route::get('/saqueExtrato', [FinanceiroController::class, 'saqueExtrato'])->name('saqueExtrato');
    Route::post('saqueExtrato', [FinanceiroController::class, 'saqueExtrato'])->name('saqueExtrato');

    Route::get('/marketing/{id}', [MarketingController::class, 'marketing'])->name('marketing');
    Route::get('/materiais', [MarketingController::class, 'materiais'])->name('materiais');
    Route::post('materiais', [MarketingController::class, 'action_materiais'])->name('materiais');
    Route::post('materiais_delete', [MarketingController::class, 'materiais_delete'])->name('materiais_delete');

    Route::get('/notificacoes', [MarketingController::class, 'notificacoes'])->name('notificacoes');
    Route::post('notificacoes', [MarketingController::class, 'action_notificacoes'])->name('notificacoes');
    Route::post('notificacoes_delete', [MarketingController::class, 'notificacoes_delete'])->name('notificacoes_delete');

    Route::get('/wallet', [FinanceiroController::class, 'wallet'])->name('wallet');
    Route::post('confirmaPagamento', [FinanceiroController::class, 'confirmaPagamento'])->name('confirmaPagamento');

});
