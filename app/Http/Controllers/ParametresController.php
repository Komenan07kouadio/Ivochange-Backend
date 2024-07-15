<?php

namespace App\Http\Controllers;

use App\Models\Parametres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ParametresController extends Controller
{
    /**
     * Afficher les paramètres
     */
    public function listeParametre()
    {
        $parametres = Parametres::all();
        return response()->json($parametres);
    }

    /**
     * Mettre à jour l'avatar
     */
    public function updateAvatar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'avatar.required' => 'Veuillez fournir un avatar',
                'avatar.image' => 'L\'avatar doit être une image',
                'avatar.mimes' => 'L\'avatar doit être un fichier de type : jpeg, png, jpg, gif',
                'avatar.max' => 'L\'avatar ne doit pas dépasser 2048 kilo-octets',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $utilisateurId = session('utilisateur');
            $parametres = Parametres::where('utilisateur_id', $utilisateurId)->firstOrFail();

            // Supprimer l'ancien avatar si nécessaire
            if ($parametres->avatar) {
                Storage::delete('avatars/' . $parametres->avatar);
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatars'), $avatarName);

            $parametres->avatar = $avatarName;
            $parametres->save();

            return response()->json(['success' => true, 'avatar' => $avatarName], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'currentPassword' => 'required|min:6',
                'newPassword' => 'required|min:6|confirmed',
            ], [
                'currentPassword.required' => 'Le mot de passe actuel est requis',
                'currentPassword.min' => 'Le mot de passe actuel doit contenir au moins 6 caractères',
                'newPassword.required' => 'Le nouveau mot de passe est requis',
                'newPassword.min' => 'Le nouveau mot de passe doit contenir au moins 6 caractères',
                'newPassword.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $utilisateurId = session('utilisateur');
            $parametres = Parametres::where('utilisateur_id', $utilisateurId)->firstOrFail();

            if (!Hash::check($request->currentPassword, $parametres->password)) {
                return response()->json(['error' => 'Mot de passe actuel incorrect'], 400);
            }

            $parametres->password = Hash::make($request->newPassword);
            $parametres->save();

            return response()->json(['success' => true, 'message' => 'Mot de passe mis à jour avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour le contact
     */
    public function updateContact(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'contact' => 'required|string',
            ], [
                'contact.required' => 'Le contact est requis',
                'contact.string' => 'Le contact doit être une chaîne de caractères',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $utilisateurId = session('utilisateur');
            $parametres = Parametres::where('utilisateur_id', $utilisateurId)->firstOrFail();

            $parametres->contact = $request->contact;
            $parametres->save();

            return response()->json(['success' => true, 'message' => 'Contact mis à jour avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Imprimer le code QR
     */
    public function printCodeQ($id)
    {
        try {
            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $utilisateurId = session('utilisateur');
            $parametres = Parametres::where('utilisateur_id', $utilisateurId)->findOrFail($id);
            $codeQ = $parametres->codeQ;

            // Logique pour générer et imprimer le code QR
            // Utilisez une bibliothèque pour générer le code QR (comme BaconQRCode)

            return response()->json(['success' => true, 'codeQ' => $codeQ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

