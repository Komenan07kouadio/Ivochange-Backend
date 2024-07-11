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




// Routes pour l'authentification et la gestion des utilisateurs
Route::post('/login', [UtilisateurController::class, 'login']);
Route::post('/logout', [UtilisateurController::class, 'logout']);
Route::post('/forgot-password', [UtilisateurController::class, 'forgotPassword']);
Route::post('/update-password', [UtilisateurController::class, 'updatePassword']);
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



 



Route::get('/', [UtilisateurController::class, 'login'])->name('connexion');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
