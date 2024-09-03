<?php

namespace App\Http\Controllers;

use App\Models\Devises;
use Illuminate\Http\Request;
use App\Http\Resources\DeviseResource;

class DeviseController extends Controller
{
    public function index()
    {
        return DeviseResource::collection(Devises::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|unique:devises',
            'symbole' => 'required',
            'reserve'=> 'required',
        ]);

        $devise = Devises::create($request->all());

        return new DeviseResource($devise);
    }

    public function show($id)
    {
        return new DeviseResource(Devises::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $devise = Devises::findOrFail($id);

        $request->validate([
            'nom' => 'required|unique:devises,code,' . $devise->id . '|max:3',
            'symbole' => 'required',
            'reserve'=> 'required',
        ]);

        $devise->update($request->all());

        return new DeviseResource($devise);
    }

    
}
