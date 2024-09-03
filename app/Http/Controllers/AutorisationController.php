<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class AutorisationController extends Controller
{
    // Méthode pour récupérer la liste des rôles avec leurs permissions
    public function index()
    {
        try {
            // Récupère tous les rôles avec leurs permissions
            $roles = Role::where('isActive', true)
            ->where('isDeleted', false)
            ->with('permissions')
            ->get();
            // Renvoie les rôles en format JSON
            return response()->json([
                'success' => true,
                'roles'=> $roles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des roles',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    // Méthode pour assigner des permissions à un rôle
    public function assignPermissions(Request $request, $role_id)
    {
        $role = Role::find($role_id);
        if(!$role_id){
            return response()->json(['message' => 'role spécifié non trouvé.'],404);
        }
        // Validation des données du formulaire
        $validate =Validator::make($request->all(), [
            'permissions' => 'required|array', // Les permissions doivent être fournies sous forme de tableau
            'permissions.*' => 'exists:permissions,name', // Chaque permission doit exister dans la table des permissions
        ], [
            'permissions.required' => 'Le champ est obligatoire',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 422);
        }
        try {
            // Assigne les permissions au rôle
            $role->givePermissionTo($validate->validated());
            // Renvoie une réponse JSON indiquant le succès
            return response()->json([
                'success' => true,
                'message' => 'autorisation assignée avec succes'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour retirer des permissions d'un rôle
    public function removePermissions(Request $request, $role_id)
    {
        $role = Role::find($role_id);
        if(!$role){
            return response()->json(['message' => 'role spécifié non trouvé.'],404);
        }
        // Validation des données du formulaire
        $validate =Validator::make($request->all(), [
            'permissions' => 'required|array', // Les permissions doivent être fournies sous forme de tableau
            'permissions.*' => 'exists:permissions,name', // Chaque permission doit exister dans la table des permissions
        ], [
            'permissions.required' => 'Le champ est obligatoire',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 422);
        }
        try {
            
            // Retire les permissions du rôle
            $role->revokePermissionTo($request->permissions);
            // Renvoie une réponse JSON indiquant le succès
            return response()->json([
                'success' => true,
                'message' => 'autorisation supprimé avec succes'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function update(Request $request, $role_id)
    {
        $role = Role::find($role_id);
        if (!$role) {
            return response()->json(['message' => 'Role spécifié non trouvé.'], 404);
        }
    
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'permissions.required' => 'Le champ permissions est obligatoire',
            'permissions.*.exists' => 'Une ou plusieurs permissions spécifiées n\'existent pas',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $newPermissions = $request->permissions;
            $currentPermissions = $role->permissions()->pluck('name')->toArray();
    
            $permissionsToAdd = array_diff($newPermissions, $currentPermissions);
            $permissionsToRemove = array_diff($currentPermissions, $newPermissions);
    
            // Ajouter les nouvelles permissions
            foreach ($permissionsToAdd as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
    
            // Retirer les permissions qui ne sont plus nécessaires
            foreach ($permissionsToRemove as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $role->revokePermissionTo($permission);
                }
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Les permissions du rôle ont été mises à jour avec succès.',
                'added' => $permissionsToAdd,
                'removed' => $permissionsToRemove
            ]);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour vérifier si un rôle possède une ou plusieurs permissions
    public function checkPermissions(Request $request, $role_id)
    {
        $role = Role::find($role_id);
        if(!$role){
            return response()->json(['message' => 'role spécifié non trouvé.'],404);
        }
        // Validation des données du formulaire
        $validate =Validator::make($request->all(), [
            // 'permissions' => 'required|array', 
            // 'permissions.*' => 'exists:permissions,name',
            'permission' => 'required|exists:permissions,name',

        ], [
            'permission.required' => 'Le champ permission est obligatoire',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ], 422);
        }
        try {

            // Vérifie si le rôle possède au moins une des permissions fournies
            if ($role->hasAnyPermission($validate->validated())) {
                // Renvoie une réponse JSON indiquant que le rôle possède au moins une des permissions
                return response()->json([
                    'success' => true,
                    'message' => 'Autorisation trouve avec succes'
                ]);
            }
            // Renvoie une réponse JSON indiquant que le rôle ne possède pas les permissions requises
            return response()->json([
                'success' => false,
                'error' => 'Role does not have the required permissions.'
            ], 400);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }

    }
}

