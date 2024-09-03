<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    // Display a listing of the roles.
    public function index()
    {
        try {
            $roles = Role::all();
            $nombres = Role::count();
            return response()->json([
                "success" => true,
                "nombres" => $nombres,
                "roles" => $roles,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e,
            ]);
        }

    }

    // Store a newly created role in storage.
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            // 'permissions' => 'required|array',
        ],[
            'name.required '=> 'le nom est obligatoire',
            'name.required' => 'le role est unique'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ], 422);
        }
        try {
            $role = Role::create($validate->validated());
            // $role->syncPermissions($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Role creer avec success',
                'role' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message' => 'Erreur lors de la création du role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Display the specified role.
    public function show($id)
    {
        try {
            $role = Role::find($id);
            return response()->json([
                'success' => true,
                'role' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'error' => $e->getMessage()
            ]);
        }

    }

    // Update the specified role in storage.
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json([
                'success' => false,
                'message' => 'role spécifié non trouvé.'
            ],404);
        }
        $validate = Validator::make($request->all(), [
            'name' => 'unique:roles,name',
            'isActive' => 'boolean',
            'isDeleted' => 'boolean'
            // 'permissions' => 'required|array',
        ],[
            'name.unique '=> 'le nom est unique',
            'isActive.boolean' => 'isActive est un booleen',
            'isDeleted.boolean' => 'isDeleted est un booleen'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ], 422);
        }

        try {
            $role->update($validate->validated());
            // $role->syncPermissions($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Role modifier avec succes',
                'role' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message' => 'Erreur lors de la modification du role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Remove the specified role from storage.
    public function destroy($id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message' => 'role spécifié non trouvé.'],404);
        }
        try {
            $role->isDeleted = true;
            $role->save();
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message' => 'Erreur lors de la modification du role',
                'error' => $e->getMessage()
            ]);
        }
    }
}
