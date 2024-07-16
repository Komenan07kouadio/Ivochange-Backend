<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReserveController extends Controller
{
    /**
     * Afficher toutes les réserves
     */
    public function index()
    {
        $reserves = Reserve::all();
        return response()->json($reserves);
    }

    /**
     * Créer une nouvelle réserve
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'devise_id' => 'required|exists:devises,id',
            'montant' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $reserve = Reserve::create($request->all());

        return response()->json($reserve, 201);
    }

    /**
     * Afficher une réserve spécifique
     */
    public function show($id)
    {
        $reserve = Reserve::findOrFail($id);
        return response()->json($reserve);
    }

    /**
     * Mettre à jour une réserve
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'devise_id' => 'sometimes|required|exists:devises,id',
            'montant' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $reserve = Reserve::findOrFail($id);
        $reserve->update($request->all());

        return response()->json($reserve);
    }

    /**
     * Supprimer une réserve
     */
    public function destroy($id)
    {
        $reserve = Reserve::findOrFail($id);
        $reserve->delete();

        return response()->json(['message' => 'Réserve supprimée avec succès'], 204);
    }
}
