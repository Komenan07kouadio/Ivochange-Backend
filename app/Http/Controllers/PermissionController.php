<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class PermissionController extends Controller
{
    // Catégorisation des permissions selon leur contexte
    private function getCategoryFromPermission($permission)
    {
        $categories = [
            'utilisateurs' => 'Utilisateurs',
            'Transaction' => 'Transaction',
            'Devise' => 'Devise',
            'Portefeuille' => 'Portefeuille',
            'reserve' => 'reserve',
            'Avis' => 'Avis',
            'TauxEchange' => 'TauxEchange',
            'Profile' => 'Profile',

        ];

        foreach ($categories as $key => $value) {
            if (strpos($permission, $key) !== false) {
                return $value;
            }
        }

        return 'Autres'; // Catégorie par défaut
    }

    // Liste toutes les permissions
    public function index()
    {
        try {
            $permissions = Permission::all();
            $nombres = Permission::count();
            $groupedPermissions = [
                'Utilisateurs' => [
                    'title' => 'Permissions liées aux utilisateurs',
                    'permissions' => []
                ],
                'Échange de monnaie' => [
                    'title' => 'Permissions liées à l\'échange de monnaie',
                    'permissions' => []
                ],
                'Protefeuille' => [
                    'title' => 'Permissions liées à un portefeuille',
                    'permissions' => []
                ],
                'Devise' => [
                    'title' => 'Permissions liées à une dévise',
                    'permissions' => []
                ],
                'reserve' => [
                    'title' => 'Permissions liées à une erserve',
                    'permissions' => []
                ],
                // Ajouter d'autres catégories ici
                'Autres' => [
                    'title' => 'Autres permissions',
                    'permissions' => []
                ],
            ];

            foreach ($permissions as $permission) {
                $category = $this->getCategoryFromPermission($permission->name);
                if (isset($groupedPermissions[$category])) {
                    $groupedPermissions[$category]['permissions'][] = $permission;
                } else {
                    $groupedPermissions['Autres']['permissions'][] = $permission;
                }
            }

            // Supprimer les catégories vides
            $groupedPermissions = array_filter($groupedPermissions, function($group) {
                return !empty($group['permissions']);
            });

            return response()->json([
                "success" => true,
                "nombres" => $nombres,
                "permission" => $permissions,
                "groupedpermission" => $groupedPermissions
            ]);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
            ]);
        }
    }

    // Création d'une nouvelle permission
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name',
        ],[
            'name.required' => 'Le nom est obligatoire',
            'name.unique' => 'Chaque permission doit être unique'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ], 422);
        }

        try {
            $permission = Permission::create($validate->validated());

            return response()->json([
                'success' => true,
                'message' => 'Permission créée avec succès',
                'permission' => $permission,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Affichage d'une permission par ID
    public function show($id)
    {
        try {
            $permission = Permission::find($id);
            if (!$permission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission non trouvée',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'permission' => $permission,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Mise à jour d'une permission existante
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission spécifiée non trouvée.'
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'name' => 'unique:permissions,name',
            'isActive' => 'boolean',
            'isDeleted' => 'boolean'
        ],[
            'name.unique' => 'Le nom est unique',
            'isActive.boolean' => 'isActive doit être un booléen',
            'isDeleted.boolean' => 'isDeleted doit être un booléen'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ], 422);
        }

        try {
            $permission->update($validate->validated());

            return response()->json([
                'success' => true,
                'message' => 'Permission modifiée avec succès',
                'permission' => $permission,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification de la permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Suppression d'une permission (suppression douce)
    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission spécifiée non trouvée.'], 404);
        }

        try {
            $permission->isDeleted = true;
            $permission->save();

            return response()->json([
                'success' => true,
                'message' => 'Permission supprimée avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la permission',
                'error' => $e->getMessage()
            ]);
        }
    }
}
