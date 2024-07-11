<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
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

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if ($utilisateur) {
            if (Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
                $request->session()->put('utilisateur', $utilisateur->id);
                return response()->json([
                    "succes" => true,
                    "utilisateur" => $utilisateur,
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
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->mot_de_passe)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect'], 400);
        }

        $user->mot_de_passe = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès'], 200);
    }

    /**
     * CRUD : Afficher tous les utilisateurs
     */
    public function index()
    {
        $utilisateurs = Utilisateur::all();
        $nombres = Utilisateur::count();
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
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'mot_de_passe' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
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
        $utilisateur = Utilisateur::findOrFail($id);
        return response()->json($utilisateur);
    }

    /**
     * CRUD : Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:utilisateurs,email,' . $id,
            'mot_de_passe' => 'sometimes|required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $utilisateur = Utilisateur::findOrFail($id);
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
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 204);
    }

    /**
     * Changer la photo de l'utilisateur
     */
    public function changePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();
        $photo = $request->file('photo');
        $photoName = time().'.'.$photo->getClientOriginalExtension();
        $photo->move(public_path('photos'), $photoName);

        $user->photo = $photoName;
        $user->save();

        return response()->json(['message' => 'Photo mise à jour avec succès', 'photo' => $photoName], 200);
    }
}
