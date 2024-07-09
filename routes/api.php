<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PortefeuilleController;
use App\Http\Controllers\DeviseController;
use App\Http\Controllers\TauxEchangeController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\ParametreController;





//utilisateur
Route::get('/users', [UtilisateurController::class, 'index']);
Route::post('/users', [UtilisateurController::class, 'store']);
Route::get('/users/{id}', [UtilisateurController::class, 'show']);
Route::put('/users/{id}', [UtilisateurController::class, 'update']);
Route::delete('/users/{id}', [UtilisateurController::class, 'destroy']);

//avis
Route::get('/avis', [AvisController::class, 'index']);
Route::post('/avis', [AvisController::class, 'store']);
Route::get('/avis/{id}', [AvisController::class, 'show']);
Route::put('/avis/{id}', [AvisController::class, 'update']);
Route::delete('/avis/{id}', [AvisController::class, 'destroy']);


 



Route::get('/', [AuthController::class, 'login'])->name('connexion');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
