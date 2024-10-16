<?php

namespace App\Http\Controllers;

use App\Models\Devises;
use Illuminate\Http\Request;
use App\Http\Resources\DeviseResource;
use Illuminate\Support\Facades\Log;

class DeviseController extends Controller
{
    public function index()
    {
        return DeviseResource::collection(Devises::all());
    }

    public function store(Request $request)
    {
        Log::info('Entrée dans la méthode store');

        $validated = $request->validate([
            'nom' => 'required|unique:devises',
            'symbole' => 'required',
            'reserve' => 'required',
        ]);

        $devise = Devises::create($validated);

        Log::info('Devise créée', ['devise' => $devise]);


        //return new DeviseResource($devise);
        return response()->json(['message' => 'Devise enregistrée avec succès']);
    }

    public function show($id)
    {
        return new DeviseResource(Devises::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $devise = Devises::findOrFail($id);

        $request->validate([
            'nom' => 'required|unique:devises,code,' . $devise->id . '|max:10',
            'symbole' => 'required',
            'reserve'=> 'required',
        ]);

        $devise->update($request->all());

        return new DeviseResource($devise);
    }


}
