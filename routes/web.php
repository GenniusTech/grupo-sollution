<?php


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendasController;
use Illuminate\Support\Facades\Route;

//Login
Route::get('/', [UserController::class, 'index'])->name('login');
Route::post('/', [UserController::class, 'login_action'])->name('login_action');

//Produtos
Route::get('/limpanome/{id}', [ProdutoController::class, 'limpaNome'])->name('limpanome');


Route::post('/venda/{id}', [VendasController::class, 'vender'])->name('vender');
Route::view('/contrato', 'relatorio.contrato')->name('relatorio.contrato');

//Extras
Route::view('/obrigado', 'obrigado');

//Autenticados
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');

    Route::get('/vendas/{id}', [VendasController::class, 'getVendas'])->name('vendas');
    Route::post('vendas', [VendasController::class, 'getVendas'])->name('vendas');

    Route::get('/usuario/{tipo}', [UserController::class, 'usuario'])->name('usuario');
    Route::post('usuario', [UserController::class, 'action_usuario'])->name('usuario');

    Route::get('/perfil',[UserController::class, 'perfil'])->name('perfil');
    Route::post('perfil', [UserController::class, 'action_perfil'])->name('perfil');

});
