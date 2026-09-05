<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendedorController extends Controller
{
    public function dashboard(Request $request)
    {
        $perfil = $request->user()->perfil()->with(['plan', 'vehiculos.marca', 'vehiculos.modelo'])->firstOrFail();
        return view('panel.dashboard', compact('perfil'));
    }

    public function create(Request $request)
    {
        return view('panel.vehiculos.form', ['item' => null, 'marcas' => Marca::with('modelos')->orderBy('nombre')->get(), 'perfil' => $request->user()->perfil]);
    }

    public function store(Request $request)
    {
        $perfil = $request->user()->perfil;
        abort_unless($perfil, 403);
        $data = $this->validated($request);
        if ($request->hasFile('imagen')) $data['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        $perfil->vehiculos()->create($data);
        return redirect()->route('panel.dashboard')->with('success', 'Vehículo publicado correctamente.');
    }

    public function destroy(Request $request, Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->perfil_id === $request->user()->perfil?->id, 403);
        if ($vehiculo->imagen) Storage::disk('public')->delete($vehiculo->imagen);
        $vehiculo->delete();
        return back()->with('success', 'Publicación eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate(['tipo' => 'required|string|max:50', 'marca_id' => 'required|exists:marcas,id', 'modelo_id' => 'required|exists:modelos,id', 'anio' => 'nullable|integer|min:1900|max:2100', 'kilometros' => 'nullable|integer|min:0', 'precio' => 'nullable|numeric|min:0', 'moneda' => 'required|string|size:3', 'ubicacion' => 'nullable|string|max:150', 'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', 'descripcion' => 'nullable|string', 'publicado' => 'boolean']);
    }
}