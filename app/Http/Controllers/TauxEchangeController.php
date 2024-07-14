<?php

namespace App\Http\Controllers;

use App\Models\TauxEchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TauxEchangeController extends Controller
{
    /**
     * Afficher tous les taux d'échange
     */
    public function index()
    {
        $tauxEchanges = TauxEchange::all();
        return response()->json($tauxEchanges);
    }

    /**
     * Créer un nouveau taux d'échange
     */
    public function store(Request $request)
    {
        // Log the request to debug
        Log::info('Request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'devise_source' => 'required|exists:devises,id',
            'devise_cible' => 'required|exists:devises,id',
            'taux' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Ensure that all required fields are passed to create method
        $tauxEchange = TauxEchange::create([
            'devise_source' => $request->devise_source,
            'devise_cible' => $request->devise_cible,
            'taux' => $request->taux,
        ]);

        return response()->json($tauxEchange, 201);
    }

    /**
     * Afficher un taux d'échange spécifique
     */
    public function show($id)
    {
        $tauxEchange = TauxEchange::findOrFail($id);
        return response()->json($tauxEchange);
    }

    /**
     * Mettre à jour un taux d'échange
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'devise_source' => 'sometimes|required|exists:devises,id',
            'devise_cible' => 'sometimes|required|exists:devises,id',
            'taux' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tauxEchange = TauxEchange::findOrFail($id);
        $tauxEchange->update($request->all());

        return response()->json($tauxEchange);
    }

    /**
     * Supprimer un taux d'échange
     */
    public function destroy($id)
    {
        $tauxEchange = TauxEchange::findOrFail($id);
        $tauxEchange->delete();

        return response()->json(['message' => 'Taux d\'échange supprimé avec succès'], 204);
    }
}
