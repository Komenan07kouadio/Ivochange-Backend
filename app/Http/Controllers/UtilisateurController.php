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
    // public function login(Request $request)
    // {
    //     // Validation des champs
    //     $request->validate([
    //         'email' => 'required|string|email|max:255',
    //         'mot_de_passe' => 'required|string',
    //     ]);
    
    //     // Rechercher l'utilisateur par email
    //     $utilisateur = Utilisateurs::where('email', $request->email)->first();
    
    //     // Si l'utilisateur est trouvé
    //     if ($utilisateur) {
    //         // Vérification du mot de passe
    //         if (Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
                
    //             // Générer un token d'API pour l'utilisateur
    //             $token = $utilisateur->createToken('auth_token')->plainTextToken;
    
    //             // Retourner une réponse JSON avec succès et le token
    //             return response()->json([
    //                 "success" => true,
    //                 "message" => "Connexion réussie. Bienvenue, " . $utilisateur->nom . "!",
    //                 "utilisateur" => $utilisateur,
    //                 "token" => $token, // Le token sera utilisé pour l'authentification future
    //             ]);
    //         } else {
    //             // Si le mot de passe est incorrect
    //             return response()->json([
    //                 "success" => false,
    //                 "message" => "Mot de passe incorrect",
    //             ], 401); // Code d'erreur 401 : Unauthorized
    //         }
    //     }
    
    //     // Si l'utilisateur n'est pas trouvé
    //     return response()->json([
    //         "success" => false,
    //         "message" => "Identifiants incorrects",
    //     ], 404); // Code d'erreur 404 : Not Found
    // }
    public function login(Request $request)
    {
        // Validation des champs
        $request->validate([
            'email' => 'required|string|email|max:255',
            'mot_de_passe' => 'required|string',
        ]);

        // Rechercher l'utilisateur par email
        $utilisateur = Utilisateurs::where('email', $request->email)->first();

        // Si l'utilisateur est trouvé
        if ($utilisateur) {
            // Vérification du mot de passe
            if (Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
                // Générer un token d'API pour l'utilisateur
                $token = $utilisateur->createToken('auth_token')->plainTextToken;

                // Retourner uniquement le token
                return response()->json([
                    "success" => true,
                    "token" => $token, // Le token sera utilisé pour l'authentification future
                ]);
            } else {
                // Si le mot de passe est incorrect
                return response()->json([
                    "success" => false,
                    "message" => "Mot de passe incorrect",
                ], 401); // Code d'erreur 401 : Unauthorized
            }
        }

        // Si l'utilisateur n'est pas trouvé
        return response()->json([
            "success" => false,
            "message" => "Identifiants incorrects",
        ], 404); // Code d'erreur 404 : Not Found
    }

    // Exemple d'une action protégée par l'authentification
    public function actionProtegee()
    {
        return response()->json([
            "message" => "Vous êtes connecté et pouvez accéder à cette action protégée",
        ]);
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
            // public function index()
            // {
            //     // Récupérer tous les utilisateurs depuis la base de données
            //     $utilisateurs = Utilisateurs::all();
                
            //     // Compter le nombre total d'utilisateurs
            //     $nombres = $utilisateurs->count(); 
                
            //     // Retourner une réponse JSON avec un statut de succès, le nombre d'utilisateurs et les données des utilisateurs
            //     return response()->json([
            //         "succes" => true,
            //         "nombres" => $nombres,
            //         "utilisateurs" => $utilisateurs
            //     ], 200); // Code HTTP 200 pour signifier que la requête a réussi
            // }
            public function index()
            {
                // Récupérer tous les utilisateurs depuis la base de données
                $utilisateurs = Utilisateurs::all();

                // Vérifie si le champ email est bien présent
                foreach ($utilisateurs as $user) {
                    if (!$user->email) {
                        return response()->json(['error' => "L'email n'est pas disponible pour l'utilisateur: {$user->nom}"], 400);
                    }
                }

                // Compter le nombre total d'utilisateurs
                $nombres = $utilisateurs->count();

                // Retourner une réponse JSON avec un statut de succès
                return response()->json([
                    "succes" => true,
                    "nombres" => $nombres,
                    "utilisateurs" => $utilisateurs
                ], 200);
            }

            public function store(Request $request)
            {
                // Validation des données de la requête
                $validator = Validator::make($request->all(), [
                    'nom' => 'required|string|max:255',
                    'prenoms' => 'required|string|max:255',
                    'telephone' => 'required|digits:10|unique:utilisateurs',
                    'pays' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:utilisateurs',
                    'mot_de_passe' => 'required|string',
                    'date_inscription' => 'required|date'
                ],
                [
                    'nom.required' => 'Le champ nom est obligatoire.',
                    'prenoms.required' => 'Le champ prénoms est obligatoire.',
                    'telephone.required' => 'Le champ téléphone est obligatoire et doit être unique.',
                    'email.required' => 'Le champ email est obligatoire et doit être un email valide.',
                    'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
                    'date_inscription.required' => 'La date d\'inscription est obligatoire et doit être une date valide.',
                ]);
        
                // Si la validation échoue, retourner les erreurs
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }
        
                // Création de l'utilisateur
                try {
                    $utilisateur = Utilisateurs::create([
                        'nom' => $request->nom,
                        'prenoms' => $request->prenoms, // Assurez-vous que la clé est 'prenoms' et non 'prenom'
                        'telephone' => $request->telephone,
                        'pays' => $request->pays,
                        'email' => $request->email,
                        'mot_de_passe' => Hash::make($request->mot_de_passe),
                        'date_inscription' => $request->date_inscription,
                    ]);
        
                    // Réponse en cas de succès
                    return response()->json($utilisateur, 201);
                } catch (\Exception $e) {
                    // Réponse en cas d'erreur interne
                    return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur.'], 500);
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
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenoms' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|numeric|digits:10|unique:utilisateurs,telephone,' . $id,
            'pays' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:utilisateurs,email,' . $id,
            'mot_de_passe' => 'sometimes|string',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Recherche de l'utilisateur
        $utilisateur = Utilisateurs::findOrFail($id);

        // Récupération des données du formulaire
        $data = $request->all();

        // Si le mot de passe est modifié, le hacher
        if (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = Hash::make($data['mot_de_passe']);
        }

        // Mise à jour de l'utilisateur
        $utilisateur->update($data);

        // Retourner les données de l'utilisateur mis à jour
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
