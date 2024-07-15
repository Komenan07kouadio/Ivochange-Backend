<?php

namespace App\Http\Controllers;

use App\Models\Portefeuille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PortefeuilleController extends Controller
{
    /**
     * Afficher tous les portefeuilles
     */
    public function listePortefeuille()
    {
        try {
            $portefeuilles = Portefeuille::all();
            $nombrePortefeuilles = $portefeuilles->count();

            return response()->json([
                'success' => true,
                'nombrePortefeuilles' => $nombrePortefeuilles,
                'portefeuilles' => $portefeuilles
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Créer un nouveau portefeuille
     */
    public function createPortefeuille(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'devise_id' => 'required|exists:devises,id',
                'solde' => 'required|numeric',
            ], [
                'devise_id.required' => 'La devise est requise',
                'devise_id.exists' => 'La devise sélectionnée est invalide',
                'solde.required' => 'Le solde est requis',
                'solde.numeric' => 'Le solde doit être un nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!session()->has('utilisateur')) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            $portefeuilleData = $request->all();
            $portefeuilleData['utilisateur_id'] = session('utilisateur');

            $portefeuille = Portefeuille::create($portefeuilleData);

            return response()->json(['success' => true, 'portefeuille' => $portefeuille], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Afficher un portefeuille spécifique
     */
    public function getPortefeuilleByID($id)
    {
        try {
            $portefeuille = Portefeuille::findOrFail($id);

            return response()->json(['success' => true, 'portefeuille' => $portefeuille], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Portefeuille non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour un portefeuille
     */
    public function modifierPortefeuille(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'devise_id' => 'sometimes|required|exists:devises,id',
                'solde' => 'sometimes|required|numeric',
            ], [
                'devise_id.exists' => 'La devise sélectionnée est invalide',
                'solde.numeric' => 'Le solde doit être un nombre',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $portefeuille = Portefeuille::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            $portefeuille->update($request->all());

            return response()->json(['success' => true, 'portefeuille' => $portefeuille], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Portefeuille non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer un portefeuille
     */
    public function supprimerPortefeuille($id)
    {
        try {
            $portefeuille = Portefeuille::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            $portefeuille->delete();

            return response()->json(['success' => true, 'message' => 'Portefeuille supprimé avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Portefeuille non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
