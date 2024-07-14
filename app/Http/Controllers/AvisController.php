<?php

namespace App\Http\Controllers;

use App\Models\Models;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Avis::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'utilisateur_id' => 'required|integer|exists:utilisateurs,id',
            'note' => 'required|string',
            'commentaire' => 'nullable|string',
        ]);

        $avis = Avis::create($validatedData);

        return response()->json($avis, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $avis = Avis::findOrFail($id);
        return response()->json($avis);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'utilisateur_id' => 'sometimes|required|integer|exists:utilisateurs,id',
            'note' => 'sometimes|required|string',
            'commentaire' => 'nullable|string',
        ]);

        $avis = Avis::findOrFail($id);
        $avis->update($validatedData);

        return response()->json($avis);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $avis = Avis::findOrFail($id);
        $avis->delete();

        return response()->json(null, 204);
    }
}
