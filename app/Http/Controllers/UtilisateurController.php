<?php

namespace App\Http\Controllers;

use App\Models\Utilisateurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UtilisateurController extends Controller
{
    /**
     * Connexion de l'utilisateur
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'mot_de_passe' => 'required|string',
        ]);

        $utilisateur = Utilisateurs::where('email', $request->email)->first();

        if ($utilisateur) {
            if (Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
                $request->session()->put('utilisateurs', $utilisateur->id);
                return response()->json([
                    "succes" => true,
                    "utilisateurs" => $utilisateur,
                ]);
            } else {
                return response()->json([
                    "succes" => false,
                    "message" => "Mot de passe incorrect",
                ]);
            }
        } else {
            return response()->json([
                "succes" => false,
                "message" => "Utilisateur non trouvé",
            ]);
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }

    /**
     * Mot de passe oublié
     */
    public function forgotPassword(Request $request)
    {
        // Implémentez ici la logique pour envoyer un e-mail de réinitialisation de mot de passe
    }
    
    /**
     * CRUD : Afficher tous les utilisateurs
     */
    public function index()
    {
        $utilisateurs = Utilisateurs::all();
        $nombres = Utilisateurs::count();
        return response()->json([
            "succes" => true,
            "nombres" => $nombres,
            "utilisateurs" => $utilisateurs
        ]);
    }

    /**
     * CRUD : Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'telephone' => 'required|numeric|max:15|unique:utilisateurs',
            'pays' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'mot_de_passe' => 'required|string',
            'date_inscription' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $utilisateur = Utilisateurs::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenom,
            'telephone' => $request->telephone,
            'pays' => $request->pays,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'date_inscription' => now(),
        ]);

        return response()->json($utilisateur, 201);
    }

    /**
     * CRUD : Afficher un utilisateur spécifique
     */
    public function show($id)
    {
        $utilisateur = Utilisateurs::findOrFail($id);
        return response()->json($utilisateur);
    }

    /**
     * CRUD : Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenoms' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|required|numeric|max:15|unique:utilisateurs,telephone' . $id,
            'pays' => 'required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:utilisateurs,email,' . $id,
            'mot_de_passe' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $utilisateur = Utilisateurs::findOrFail($id);
        $data = $request->all();
        if (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = Hash::make($data['mot_de_passe']);
        }
        $utilisateur->update($data);

        return response()->json($utilisateur);
    }

    /**
     * CRUD : Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $utilisateur = Utilisateurs::findOrFail($id);
        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 204);
    }

    /**
     * Changer la photo de l'utilisateur
     */
        public function changePhoto(Request $request)
    {
        // Valider la requête
        $validator = Validator::make($request->all(), [
            'photoProfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Vérifier si l'utilisateur est connecté
        if (session()->has('utilisateurs')) {
            $utilisateur = Utilisateurs::find(session('utilisateur'));

            // Traiter la requête AJAX
            if ($request->ajax()) {
                $photoProfil = $request->file('photoProfil');
                $extensionFichier = $photoProfil->getClientOriginalExtension();
                $nomDuFichier = 'User-' . $utilisateur->id . '-prof.' . $extensionFichier;

                $dossierContentFile = public_path('assets/galerie/utilisateurs/profil/');

                // Déplacer et enregistrer la nouvelle photo
                $uploadPhoto = $photoProfil->move($dossierContentFile, $nomDuFichier);

                if ($uploadPhoto) {
                    // Mettre à jour le chemin de l'avatar dans la base de données
                    $utilisateur->avatar = $nomDuFichier;
                    $utilisateur->save();

                    return response()->json([
                        'success' => 'Image enregistrée avec succès',
                        'avatar' => $nomDuFichier
                    ]);
                } else {
                    return response()->json(['error' => "Impossible d'enregistrer l'image"], 500);
                }
            } else {
                return response()->json(['error' => 'Requête non autorisée'], 403);
            }
        } else {
            return response()->json(['error' => 'Utilisateur non connecté'], 401);
        }
    }
}
