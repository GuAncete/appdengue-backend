<?php

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\Api\DenunciaController;
use App\Http\Controllers\Api\FotoController;
use App\Http\Controllers\Api\HistoricoStatusController;
use App\Http\Controllers\Api\ResolucaoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
|
| Estas rotas não exigem que o usuário esteja logado.
|
*/
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/users', [UserController::class, 'store']);
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);


/*
|--------------------------------------------------------------------------
| Rotas Protegidas por Autenticação (Sanctum)
|--------------------------------------------------------------------------
|
| Todas as rotas dentro deste grupo só podem ser acessadas por
| usuários que estão logados e enviando um token de autenticação válido.
|
*/
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Rota de Logout
    Route::post('/logout/{user}', [LoginController::class, 'logout']);

    // Rotas para Usuários
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Rotas para Denuncias
    Route::get('/denuncias', [DenunciaController::class, 'index']);
    Route::get('/denuncias/{denuncia}', [DenunciaController::class, 'show']);
    Route::post('/denuncias', [DenunciaController::class, 'store']);
    Route::put('/denuncias/{denuncia}', [DenunciaController::class, 'update']);
    Route::delete('/denuncias/{denuncia}', [DenunciaController::class, 'destroy']);
    Route::post('/denuncias/{denuncia}/finalizar', [DenunciaController::class, 'finalizar']); // Sua rota
    Route::get('/relatorio/denuncias', [DenunciaController::class, 'relatorio']); // Sua rota

    // Rotas para Fotos
    Route::get('/fotos', [FotoController::class, 'index']);
    Route::get('/fotos/{foto}', [FotoController::class, 'show']);
    Route::post('/fotos/{idDenuncia}', [App\Http\Controllers\Api\FotoController::class, 'store']);
    Route::put('/fotos/{foto}', [FotoController::class, 'update']);
    Route::delete('/fotos/{foto}', [FotoController::class, 'destroy']);

    // Rotas para Resolucoes
    Route::get('/resolucoes', [ResolucaoController::class, 'index']);
    Route::get('/resolucoes/{resolucao}', [ResolucaoController::class, 'show']);
    Route::post('/resolucoes', [ResolucaoController::class, 'store']);
    Route::put('/resolucoes/{resolucao}', [ResolucaoController::class, 'update']);
    Route::delete('/resolucoes/{resolucao}', [ResolucaoController::class, 'destroy']);

    // Rotas para HistoricoStatus
    Route::get('/historicostatus', [HistoricoStatusController::class, 'index']);
    Route::get('/historicostatus/{historicoStatus}', [HistoricoStatusController::class, 'show']);
    Route::post('/historicostatus', [HistoricoStatusController::class, 'store']);
    Route::put('/historicostatus/{historicoStatus}', [HistoricoStatusController::class, 'update']);
    Route::delete('/historicostatus/{historicoStatus}', [HistoricoStatusController::class, 'destroy']);
});