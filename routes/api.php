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
Route::post('/change-photo', [UtilisateurController::class, 'changePhoto'])->middleware('auth:sanctum');

// Gestion des utilisateurs
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
    Route::post('/utilisateurs', [UtilisateurController::class, 'store']);
    Route::get('/utilisateurs/{id}', [UtilisateurController::class, 'show']);
    Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
    Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);
});

// Gestion des avis
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/avis', [AvisController::class, 'index']);
    Route::post('/avis', [AvisController::class, 'store']);
    Route::get('/avis/{id}', [AvisController::class, 'show']);
    Route::put('/avis/{id}', [AvisController::class, 'update']);
    Route::delete('/avis/{id}', [AvisController::class, 'destroy']);
});

// Gestion des devises
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/devises', [DeviseController::class, 'index']);
    Route::post('/devises', [DeviseController::class, 'store']);
    Route::get('/devises/{id}', [DeviseController::class, 'show']);
    Route::put('/devises/{id}', [DeviseController::class, 'update']);
});

// Gestion des taux d'échange
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/taux_echanges', [TauxEchangeController::class, 'index']);
    Route::post('/taux_echanges', [TauxEchangeController::class, 'store']);
    Route::get('/taux_echanges/{id}', [TauxEchangeController::class, 'show']);
    Route::put('/taux_echanges/{id}', [TauxEchangeController::class, 'update']);
});

// Gestion des actualités
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/actualites', [ActualiteController::class, 'index']);
    Route::post('/actualites', [ActualiteController::class, 'store']);
    Route::get('/actualites/{id}', [ActualiteController::class, 'show']);
    Route::put('/actualites/{id}', [ActualiteController::class, 'update']);
    Route::delete('/actualites/{id}', [ActualiteController::class, 'destroy']);
});

// Gestion des transactions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
});

// Page d'accueil
Route::get('/', [UtilisateurController::class, 'login'])->name('connexion');

// Récupérer l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
