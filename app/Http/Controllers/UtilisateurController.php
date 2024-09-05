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
                // Gérer d'autres rôles ou rediriger vers une page par défaut
                return redirect()->route('default.page');
            }
        }

        // Si la connexion échoue, renvoyer à la page de connexion avec un message d'erreur
        return redirect()->route('connexion')->withErrors('Identifiants incorrects');
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
                // Récupérer tous les utilisateurs depuis la base de données
                $utilisateurs = Utilisateurs::all();
                
                // Compter le nombre total d'utilisateurs
                $nombres = $utilisateurs->count(); 
                
                // Retourner une réponse JSON avec un statut de succès, le nombre d'utilisateurs et les données des utilisateurs
                return response()->json([
                    "succes" => true,
                    "nombres" => $nombres,
                    "utilisateurs" => $utilisateurs
                ], 200); // Code HTTP 200 pour signifier que la requête a réussi
            }

            public function store(Request $request)
        {
            // Validation des données de la requête
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenoms' => 'required|string|max:255',
                'telephone' => 'required|digits:10|unique:utilisateurs',
                'pays' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:utilisateurs',
                'mot_de_passe' => 'required|string',
                'date_inscription' => 'required|date'
            ]);

            try {
                // Création d'un nouvel utilisateur avec les données validées
                $utilisateur = Utilisateurs::create([
                    'nom' => $validatedData['nom'],
                    'prenoms' => $validatedData['prenoms'],
                    'telephone' => $validatedData['telephone'],
                    'pays' => $validatedData['pays'],
                    'email' => $validatedData['email'],
                    'mot_de_passe' => bcrypt($validatedData['mot_de_passe']), // Assurez-vous de hacher le mot de passe
                    'date_inscription' => $validatedData['date_inscription']
                ]);

                return response()->json([
                    'success' => true,
                    'utilisateur' => $utilisateur
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'utilisateur.',
                    'error' => $e->getMessage()
                ], 500);
            }
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
            'telephone' => 'sometimes|required|numeric|max:10|unique:utilisateurs,telephone' . $id,
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
