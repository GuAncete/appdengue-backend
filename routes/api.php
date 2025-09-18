<?php

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\Api\DenunciaController;
use App\Http\Controllers\Api\FotoController;
use App\Http\Controllers\Api\HistoricoStatusController;
use App\Http\Controllers\Api\ResolucaoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/users', [UserController::class, 'store']); // POST - http://127.0.0.1:8000//mover pro sanctum dnv dps
Route::post('/forgot-password', [UserController::class, 'forgotPassword']); // POST - http://127.0.0.1:8000/api/forgot-password
Route::post('/reset-password', [UserController::class, 'resetPassword']); // POST - http://127.0.0.1:8000/api/reset-password

   // Rotas para usuários
    Route::get('/users', [UserController::class, 'index']); // GET - http://127.0.0.1:8000/api/users?page=1
    Route::get('/users/{user}', [UserController::class, 'show']); // GET - http://127.0.0.1:8000/api/users/1
    Route::put('/users/{user}', [UserController::class, 'update']); // PUT - http://127.0.0.1:8000/api/users/1
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/users/1


    // Rotas para Denuncias
    Route::get('/denuncias', [DenunciaController::class, 'index']); // GET - http://127.0.0.1:8000/api/denuncias?page=1
    Route::get('/denuncias/{denuncia}', [DenunciaController::class, 'show']); // GET - http://127.0.0.1:8000/api/denuncias/1
    Route::post('/denuncias', [DenunciaController::class, 'store']); // POST - http://127.0.0.1:8000/api/denuncias
    Route::put('/denuncias/{denuncia}', [DenunciaController::class, 'update']); // PUT - http://127.0.0.1:8000/api/denuncias/1
    Route::delete('/denuncias/{denuncia}', [DenunciaController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/denuncias/1

    // Rotas para Fotos
    Route::get('/fotos', [FotoController::class, 'index']); // GET - http://127.0.0.1:8000/api/fotos?page=1
    Route::get('/fotos/{foto}', [FotoController::class, 'show']); // GET - http://127.0.0.1:8000/api/fotos/1
    Route::post('/fotos/{idDenuncia}', [App\Http\Controllers\Api\FotoController::class, 'store']); // POST - http://127.0.0.1:8000/api/fotos
    Route::put('/fotos/{foto}', [FotoController::class, 'update']); // PUT - http://127.0.0.1:8000/api/fotos/1
    Route::delete('/fotos/{foto}', [FotoController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/fotos/1

    // Rotas para Resolucoes
    Route::get('/resolucoes', [ResolucaoController::class, 'index']); // GET - http://127.0.0.1:8000/api/resolucoes?page=1
    Route::get('/resolucoes/{resolucao}', [ResolucaoController::class, 'show']); // GET - http://127.0.0.1:8000/api/resolucoes/1
    Route::post('/resolucoes', [ResolucaoController::class, 'store']); // POST - http://127.0.0.1:8000/api/resolucoes
    Route::put('/resolucoes/{resolucao}', [ResolucaoController::class, 'update']); // PUT - http://127.0.0.1:8000/api/resolucoes/1
    Route::delete('/resolucoes/{resolucao}', [ResolucaoController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/resolucoes/1

    // Rotas para HistoricoStatus
    Route::get('/historicostatus', [HistoricoStatusController::class, 'index']); // GET - http://127.0.0.1:8000/api/historico-status?page=1
    Route::get('/historicostatus/{historicoStatus}', [HistoricoStatusController::class, 'show']); // GET - http://127.0.0.1:8000/api/historico-status/1
    Route::post('/historicostatus', [HistoricoStatusController::class, 'store']); // POST - http://127.0.0.1:8000/api/historico-status
    Route::put('/historicostatus/{historicoStatus}', [HistoricoStatusController::class, 'update']); // PUT - http://127.0.0.1:8000/api/historico-status/1
    Route::delete('/historicostatus/{historicoStatus}', [HistoricoStatusController::class, 'destroy']); // DELETE - http://127.0.0.1:8000/api/historico-status/1


Route::group(['middleware' => ['auth:sanctum']], function () {
    // Rotas protegidas por autenticação
    Route::post('/logout/{user}', [LoginController::class, 'logout']); // POST - http://127.0.0.1:8000/api/logout
 

});
