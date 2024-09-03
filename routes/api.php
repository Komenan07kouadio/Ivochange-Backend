<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DeviseController;
use App\Http\Controllers\TauxEchangeController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AvisController;






// Routes pour l'authentification et la gestion des utilisateurs
Route::post('/login', [UtilisateurController::class, 'login']);
Route::post('/logout', [UtilisateurController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/forgot-password', [UtilisateurController::class, 'forgotPassword']);
Route::post('/change-photo', [UtilisateurController::class, 'changePhoto']);


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

//Devise 
Route::get('/devises', [DeviseController::class, 'index']);
Route::post('/devises', [DeviseController::class, 'store']);
Route::get('/devises/{id}', [DeviseController::class, 'show']);
Route::put('/devises/{id}', [DeviseController::class, 'update']);

//Taux d'échange
Route::get('/taux_echanges', [TauxEchangeController::class, 'index']);
Route::post('/taux_echanges', [TauxEchangeController::class, 'store']);
Route::get('/taux_echanges/{id}', [TauxEchangeController::class, 'show']);
Route::put('/taux_echanges/{id}', [TauxEchangeController::class, 'update']);

//Actualités
Route::get('/actualites', [ActualiteController::class, 'index']);
Route::post('/actualites', [ActualiteController::class, 'store']);
Route::get('/actualites/{id}', [ActualiteController::class, 'show']);
Route::put('/actualites/{id}', [ActualiteController::class, 'update']);
Route::delete('/actualites/{id}', [ActualiteController::class, 'destroy']);


//Transaction
Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
Route::put('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);



Route::get('/', [UtilisateurController::class, 'login'])->name('connexion');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
