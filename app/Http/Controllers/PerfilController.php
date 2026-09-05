<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerfilController extends Controller
{
    public function index()
    {
        $perfiles = Perfil::all();

        if ($perfiles->isEmpty()) {
            return response()->json(['message' => 'No se encontraron perfiles'], 200);
        }
        return response()->json($perfiles, 200);
        
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'nombre_negocio' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ])->validate();

        $perfil = Perfil::create($validatedData);

        return response()->json($perfil, 201);
    }
}
