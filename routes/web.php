<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

//Login
Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/', [UserController::class, 'login_action'])->name('login_action');

//Autenticados
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/whatsapp', [DashboardController::class, 'whatsapp'])->name('whatsapp');
    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');

    Route::get('/vendaDireta/{id}', [NomeController::class, 'vendaDireta'])->name('vendaDireta');
    Route::post('cadastraNome', [NomeController::class, 'cadastraNome'])->name('cadastraNome');
    Route::post('cadastraDocumento', [NomeController::class, 'cadastraDocumento'])->name('cadastraDocumento');
    Route::post('excluiNome', [NomeController::class, 'excluiNome'])->name('excluiNome');

    Route::get('/usuario', [UserController::class, 'usuario'])->name('usuario');
    Route::post('cadastraUsuario', [UserController::class, 'cadastraUsuario'])->name('cadastraUsuario');
    Route::post('atualizaUsuario', [UserController::class, 'atualizaUsuario'])->name('atualizaUsuario');
    Route::post('excluiUsuario', [UserController::class, 'excluiUsuario'])->name('excluiUsuario');

    Route::get('/perfil',[UserController::class, 'perfil'])->name('perfil');
    Route::post('perfil', [UserController::class, 'action_perfil'])->name('perfil');

    Route::get('/marketing/{id}', [MarketingController::class, 'marketing'])->name('marketing');
    Route::get('/materiais', [MarketingController::class, 'materiais'])->name('materiais');
    Route::post('materiais', [MarketingController::class, 'action_materiais'])->name('materiais');
    Route::post('materiais_delete', [MarketingController::class, 'materiais_delete'])->name('materiais_delete');

    Route::get('/lista', [ListaController::class, 'lista'])->name('lista');
    Route::get('/listaDetalhe/{id}', [ListaController::class, 'listaDetalhe'])->name('listaDetalhe');
    Route::post('cadastraLista', [ListaController::class, 'cadastraLista'])->name('cadastraLista');
    Route::post('atualizaLista', [ListaController::class, 'atualizaLista'])->name('atualizaLista');
    Route::post('excluiLista', [ListaController::class, 'excluiLista'])->name('excluiLista');
});
