<?php
namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AvisController extends Controller
{
    /**
     * Afficher tous les avis
     */
 public function index()
    {
        try {
            $avis = Avis::all();
            $nombreAvis = $avis->count();

            return response()->json([
                'success' => true,
                'nombreAvis' => $nombreAvis,
                'avis' => $avis
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Créer un nouvel avis
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'note' => 'required|integer|min:1|max:5',
                'commentaire' => 'required|string|max:255',
            ], [
                'note.required' => 'La note est requise',
                'note.integer' => 'La note doit être un entier',
                'note.min' => 'La note doit être au moins de 1',
                'note.max' => 'La note ne doit pas dépasser 5',
                'commentaire.required' => 'Le commentaire est requis',
                'commentaire.string' => 'Le commentaire doit être une chaîne de caractères',
                'commentaire.max' => 'Le commentaire ne doit pas dépasser 255 caractères',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $avisData = $request->all();
            $avisData['utilisateur_id'] = session('utilisateur');

            $avis = Avis::create($avisData);

            return response()->json(['success' => true, 'avis' => $avis], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour un avis
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'note' => 'sometimes|required|integer|min:1|max:5',
                'commentaire' => 'sometimes|required|string|max:255',
            ], [
                'note.required' => 'La note est requise',
                'note.integer' => 'La note doit être un entier',
                'note.min' => 'La note doit être au moins de 1',
                'note.max' => 'La note ne doit pas dépasser 5',
                'commentaire.required' => 'Le commentaire est requis',
                'commentaire.string' => 'Le commentaire doit être une chaîne de caractères',
                'commentaire.max' => 'Le commentaire ne doit pas dépasser 255 caractères',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $avis = Avis::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            $avis->update($request->all());

            return response()->json(['success' => true, 'avis' => $avis], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Avis non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer un avis
     */
    public function destroy($id)
    {
        try {
            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $avis = Avis::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            $avis->delete();

            return response()->json(['success' => true, 'message' => 'Avis supprimé avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Avis non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
