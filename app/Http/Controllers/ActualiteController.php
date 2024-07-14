<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActualiteController extends Controller
{
    /**
     * Afficher toutes les actualités
     */
    public function index()
    {
        $actualites = Actualite::all();
        return response()->json($actualites);
    }

    /**
     * Créer une nouvelle actualité
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $actualite = Actualite::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
        ]);

        return response()->json($actualite, 201);
    }

    /**
     * Afficher une actualité spécifique
     */
    public function show($id)
    {
        $actualite = Actualite::findOrFail($id);
        return response()->json($actualite);
    }

    /**
     * Mettre à jour une actualité
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'contenu' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $actualite = Actualite::findOrFail($id);
        $actualite->update($request->all());

        return response()->json($actualite);
    }

    /**
     * Supprimer une actualité
     */
    public function destroy($id)
    {
        $actualite = Actualite::findOrFail($id);
        $actualite->delete();

        return response()->json(['message' => 'Actualité supprimée avec succès'], 204);
    }
}
