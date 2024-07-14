<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    /**
     * CRUD : Afficher tous les profils
     */
    public function getProfil()
    {
        $Profil = Profil::all();
        $nombres = Profil::count();
        return response()->json([
            "succes" => true,
            "nombres" => $nombres,
            "Profils" => $Profil
        ]);
    }
    /**
     * Créer un nouveau profil pour un utilisateur.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
        ],
        [
            'utilisateur_id' => 'Veuillez renseignez ce champs',
            "nom" => "Veuillez renseignez ce champs",
            "prenom" => "Veuillez renseignez ce champs",
            "adresse" => "Veuillez renseignez ce champs",
            "telephone" => "Veuillez renseignez ce champs",
        ],
    );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $profil = Profil::create($validator->validated());

            return response()->json(['message' => 'Profil créé avec succès', 'profil' => $profil], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la création du profil', 'error' => $e->getMessage()], 500);
        }
    }  
    /**
     * Afficher le profil d'un utilisateur.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByIdProfil($id)
    {
        $profil = Profil::find($id);

        if (!$profil) {
            return response()->json(['message' => 'Profil non trouvé'], 404);
        }

        return response()->json(['success' => true, 'profil' => $profil], 200);
    }

    /**
     * Mettre à jour le profil d'un utilisateur.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfil(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'date_naissance' => 'sometimes|date',
            'adresse' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $profil = Profil::find($id);

            if (!$profil) {
                return response()->json(['message' => 'Profil non trouvé'], 404);
            }

            $profil->update($validator->validated());

            return response()->json(['message' => 'Profil mis à jour avec succès', 'profil' => $profil], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la mise à jour du profil', 'error' => $e->getMessage()], 500);
        }
    }
}
