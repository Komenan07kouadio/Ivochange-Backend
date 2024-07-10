<?php

namespace App\Http\Controllers;

use App\Models\Devise;
use Illuminate\Http\Request;
use App\Http\Resources\DeviseResource;

class DeviseController extends Controller
{
    public function index()
    {
        return DeviseResource::collection(Devise::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:devises|max:3',
            'nom' => 'required',
        ]);

        $devise = Devise::create($request->all());

        return new DeviseResource($devise);
    }

    public function show($id)
    {
        return new DeviseResource(Devise::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $devise = Devise::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:devises,code,' . $devise->id . '|max:3',
            'nom' => 'required',
        ]);

        $devise->update($request->all());

        return new DeviseResource($devise);
    }

    
}
