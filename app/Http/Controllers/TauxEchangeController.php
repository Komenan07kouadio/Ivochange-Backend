<?php

namespace App\Http\Controllers;

use App\Models\Taux_echange;
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
        $tauxEchanges = Taux_echange::all();
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
            'taux' => 'required|numeric',
            'date_taux'=> 'date_format:format'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Ensure that all required fields are passed to create method
        $tauxEchange = Taux_echange::create([
            'taux' => $request->taux,
            'date_taux' => $request->date_taux,
        ]);

        return response()->json($tauxEchange, 201);
    }

    /**
     * Afficher un taux d'échange spécifique
     */
    public function show($id)
    {
        $tauxEchange = Taux_echange::findOrFail($id);
        return response()->json($tauxEchange);
    }

    /**
     * Mettre à jour un taux d'échange
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'taux' => 'sometimes|required|numeric',
            'date_taux'=> 'date_format:format'
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tauxEchange = Taux_echange::findOrFail($id);
        $tauxEchange->update($request->all());

        return response()->json($tauxEchange);
    }

    /**
     * Supprimer un taux d'échange
     */
    public function destroy($id)
    {
        $tauxEchange = Taux_echange::findOrFail($id);
        $tauxEchange->delete();

        return response()->json(['message' => 'Taux d\'échange supprimé avec succès'], 204);
    }
}
