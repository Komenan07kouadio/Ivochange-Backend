<?php
use Illuminate\Http\Request;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DeviseController;
use App\Http\Controllers\TauxEchangeController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\AvisController;
use Illuminate\Support\Facades\Route;

// Routes pour l'authentification et la gestion des utilisateurs
Route::post('/login', [UtilisateurController::class, 'login'])->name('login');
// Route::post('/login', [UtilisateurController::class, 'login']);
Route::post('/create', [UtilisateurController::class, 'store']);
Route::post('/logout', [UtilisateurController::class, 'logout'])->middleware('auth:sanctum');


// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // Utilisateur
    Route::get('/liste', [UtilisateurController::class, 'index']);
    Route::get('/utilisateurs/{id}', [UtilisateurController::class, 'show']);
    Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
    Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);
    Route::post('/forgot-password', [UtilisateurController::class, 'forgotPassword']);
    Route::post('/change-photo', [UtilisateurController::class, 'changePhoto']);

    // Avis
    Route::get('/avis', [AvisController::class, 'index']);
    Route::post('/avis', [AvisController::class, 'store']);
    Route::get('/avis/{id}', [AvisController::class, 'show']);
    Route::put('/avis/{id}', [AvisController::class, 'update']);
    Route::delete('/avis/{id}', [AvisController::class, 'destroy']);

    // Devise
    Route::get('/devises', [DeviseController::class, 'index']);
    Route::post('/devises', [DeviseController::class, 'store']);
    Route::get('/devises/{id}', [DeviseController::class, 'show']);
    Route::put('/devises/{id}', [DeviseController::class, 'update']);

    // Taux d'échange
    Route::get('/taux_echanges', [TauxEchangeController::class, 'index']);
    Route::post('/taux_echanges', [TauxEchangeController::class, 'store']);
    Route::get('/taux_echanges/{id}', [TauxEchangeController::class, 'show']);
    Route::put('/taux_echanges/{id}', [TauxEchangeController::class, 'update']);

    // Actualités
    Route::get('/actualites', [ActualiteController::class, 'index']);
    Route::post('/actualites', [ActualiteController::class, 'store']);
    Route::get('/actualites/{id}', [ActualiteController::class, 'show']);
    Route::put('/actualites/{id}', [ActualiteController::class, 'update']);
    Route::delete('/actualites/{id}', [ActualiteController::class, 'destroy']);

    // Transaction
    Route::get('/create', [TransactionController::class, 'index']);
    Route::post('/liste', [TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
});



