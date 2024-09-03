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
use App\Http\Controllers\ParametresController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AutorisationController;
use App\Http\Controllers\DashboardDataController;

 //Role
 Route::get('/listeRole',[RoleController::class, 'index'])->middleware('check.permissions:liste_roles');
 Route::get('/getRoleBy/{id}',[RoleController::class, 'show'])->middleware('check.permissions:detail_role');
 Route::post('/saveRole',[RoleController::class, 'store'])->middleware('check.permissions:creer_role');
 Route::put('/updateRole/{id}',[RoleController::class, 'update'])->middleware('check.permissions:modifier_role');
 Route::delete('/deleteRole/{id}',[RoleController::class, 'destroy'])->middleware('check.permissions:supprimer_role');
  
 //dashboard
  Route::get('/dashboard/{id}', [DashboardDataController::class, 'getDashboardData'])->middleware('check.permissions:recuperer_dashboard_data');

 // Permission
 Route::post('/savePermission', [PermissionController::class, 'store'])->middleware('check.permissions:enregistrer_permission');
 Route::put('/updatePermission/{id}', [PermissionController::class, 'update'])->middleware('check.permissions:modifier_permission');
 Route::get('/listePermissions', [PermissionController::class, 'index'])->middleware('check.permissions:liste_permissions');
 Route::get('/permission/{id}', [PermissionController::class, 'show'])->middleware('check.permissions:detail_permission');
 Route::delete('/deletePermission/{id}', [PermissionController::class, 'destroy'])->middleware('check.permissions:supprimer_permission');

 // Autorisation
Route::post('/saveAutorisation/{role_id}', [AutorisationController::class, 'assignPermissions'])->middleware('check.permissions:attribuer_permissions_role');
Route::put('/updateAutorisation/{role_id}', [AutorisationController::class, 'update'])->middleware('check.permissions:modifier_autorisations');
Route::get('/listeAutorisations', [AutorisationController::class, 'index'])->middleware('check.permissions:liste_autorisations');
Route::post('/checkAutorisation/{role_id}', [AutorisationController::class, 'checkPermissions'])->middleware('check.permissions:verifier_permissions_role');
Route::delete('/deleteAutorisation/{role_id}', [AutorisationController::class, 'removePermissions'])->middleware('check.permissions:supprimer_permissions_role');


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

// Définition des routes
Route::get('/profil', [ProfilController::class, 'getProfil']);
Route::post('/profil', [ProfilController::class, 'createProfil']);
Route::get('/profil/{id}', [ProfilController::class, 'getByIdProfil']);
Route::put('/profil/{id}', [ProfilController::class, 'updateProfil']);
Route::delete('/profil/{id}', [ProfilController::class, 'deleteProfil']);

//Paramètres
Route::get('/parametres', [ParametresController::class, 'listeParametre']);
Route::post('/parametres/{id}/avatar', [ParametresController::class, 'updateAvatar']);
Route::post('/parametres/{id}/password', [ParametresController::class, 'updatePassword']);
Route::post('/parametres/{id}/contact', [ParametresController::class, 'updateContact']);
Route::get('/parametres/{id}/codeq', [ParametresController::class, 'printCodeQ']);

//Portefeuilles
Route::get('/portefeuilles', [PortefeuilleController::class, 'listePortefeuille']);
Route::post('/portefeuilles', [PortefeuilleController::class, 'createPortefeuille']);
Route::get('/portefeuilles/{id}', [PortefeuilleController::class, 'getPortefeuilleByID']);
Route::put('/portefeuilles/{id}', [PortefeuilleController::class, 'modifierPortefeuille']);
Route::delete('/portefeuilles/{id}', [PortefeuilleController::class, 'supprimerPortefeuille']);

//Transaction
Route::get('/transactions', [TransactionController::class, 'index']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
Route::put('/transactions/{id}', [TransactionController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);

//Reserve
Route::get('/reserves', [ReserveController::class, 'index']);
Route::post('/reserves', [ReserveController::class, 'store']);
Route::get('/reserves/{id}', [ReserveController::class, 'show']);
Route::put('/reserves/{id}', [ReserveController::class, 'update']);
Route::delete('/reserves/{id}', [ReserveController::class, 'destroy']);

// Route pour le dashboard superAdmin
Route::get('/superAdmin', [DashboardDataController::class, 'index'])->name('superAdmin.dashboard')->middleware('auth');

// Route pour le dashboard admin
Route::get('/admin', [DashboardDataController::class, 'index'])->name('admin.dashboard')->middleware('auth');

// Route pour le dashboard utilisateur
Route::get('/user', [DashboardDataController::class, 'index'])->name('user.dashboard')->middleware('auth');

// Route par défaut si nécessaire
Route::get('/default', [DashboardDataController::class, 'index'])->name('default.page')->middleware('auth');
Route::get('/dashboard', [DashboardDataController::class, 'dashboard'])->name('default.page')->middleware('auth');

Route::get('/', [UtilisateurController::class, 'login'])->name('connexion');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
