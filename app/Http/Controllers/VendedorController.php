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
        $perfil = $request->user()->perfil()->with(['plan', 'user', 'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.imagenes'])->firstOrFail();
        return view('panel.dashboard', compact('perfil'));
    }

    public function editLanding(Request $request)
    {
        $perfil = $request->user()->perfil()->firstOrFail();
        abort_unless($perfil->esAgencia(), 403);
        return view('panel.perfil.form', compact('perfil'));
    }

    public function updateLanding(Request $request)
    {
        $perfil = $request->user()->perfil()->firstOrFail();
        abort_unless($perfil->esAgencia(), 403);
        $data = $request->validate([
            'nombre_negocio' => 'required|string|max:255', 'slug' => 'required|string|max:100|alpha_dash|unique:perfil,slug,' . $perfil->id,
            'descripcion' => 'nullable|string', 'telefono' => 'nullable|string|max:30', 'direccion' => 'nullable|string|max:255',
            'plantilla' => 'required|in:editorial,alto-contraste,calma', 'color_principal' => 'required|string|size:7',
            'color_secundario' => 'required|string|size:7', 'titulo_portada' => 'nullable|string|max:120', 'subtitulo_portada' => 'nullable|string|max:500',
            'whatsapp' => 'nullable|string|max:30', 'instagram' => 'nullable|string|max:100', 'facebook' => 'nullable|string|max:100',
            'imagen_portada' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'secciones' => 'nullable|array', 'secciones.*' => 'in:hero,intro,inventario,contacto,footer',
        ]);
        $data['secciones'] = array_values(array_unique($data['secciones'] ?? []));
        if ($request->hasFile('imagen_portada')) {
            if ($perfil->imagen_portada) Storage::disk('public')->delete($perfil->imagen_portada);
            $data['imagen_portada'] = $request->file('imagen_portada')->store('perfiles', 'public');
        }
        if ($request->hasFile('logo')) {
            if ($perfil->logo) Storage::disk('public')->delete($perfil->logo);
            $data['logo'] = $request->file('logo')->store('perfiles/logos', 'public');
        }
        $perfil->update($data);
        return redirect()->route('panel.landing.edit')->with('success', 'Tu página fue actualizada.');
    }

    public function create(Request $request)
    {
        return view('panel.vehiculos.form', ['item' => null, 'marcas' => Marca::with('modelos')->orderBy('nombre')->get(), 'perfil' => $request->user()->perfil]);
    }

    public function edit(Request $request, Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->perfil_id === $request->user()->perfil?->id, 403);
        $vehiculo->load('imagenes');

        return view('panel.vehiculos.form', [
            'item' => $vehiculo,
            'marcas' => Marca::with('modelos')->orderBy('nombre')->get(),
            'perfil' => $request->user()->perfil,
        ]);
    }

    public function store(Request $request)
    {
        $perfil = $request->user()->perfil;
        abort_unless($perfil, 403);
        $data = $this->validated($request);
        $images = $request->file('imagenes', []);
        $data['imagen'] = null;
        $vehicle = $perfil->vehiculos()->create($data);
        foreach ($images as $order => $image) {
            $path = $image->store('vehiculos', 'public');
            $vehicle->imagenes()->create(['ruta' => $path, 'orden' => $order]);
            if ($order === 0) $vehicle->update(['imagen' => $path]);
        }
        return redirect()->route('panel.dashboard')->with('success', 'Vehículo publicado correctamente.');
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->perfil_id === $request->user()->perfil?->id, 403);
        $data = $this->validated($request);
        $images = $request->file('imagenes', []);
        unset($data['imagenes']);
        $vehiculo->update($data);

        if ($images) {
            foreach ($vehiculo->imagenes as $image) {
                Storage::disk('public')->delete($image->ruta);
            }
            $vehiculo->imagenes()->delete();
            $vehiculo->update(['imagen' => null]);

            foreach ($images as $order => $image) {
                $path = $image->store('vehiculos', 'public');
                $vehiculo->imagenes()->create(['ruta' => $path, 'orden' => $order]);
                if ($order === 0) $vehiculo->update(['imagen' => $path]);
            }
        }

        return redirect()->route('panel.dashboard')->with('success', 'Publicación actualizada correctamente.');
    }

    public function destroy(Request $request, Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->perfil_id === $request->user()->perfil?->id, 403);
        foreach ($vehiculo->imagenes as $image) Storage::disk('public')->delete($image->ruta);
        if ($vehiculo->imagen && !$vehiculo->imagenes->contains('ruta', $vehiculo->imagen)) Storage::disk('public')->delete($vehiculo->imagen);
        $vehiculo->delete();
        return back()->with('success', 'Publicación eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate(['tipo' => 'required|string|max:50', 'marca_id' => 'required|exists:marcas,id', 'modelo_id' => 'required|exists:modelos,id', 'anio' => 'nullable|integer|min:1900|max:2100', 'kilometros' => 'nullable|integer|min:0', 'precio' => 'nullable|numeric|min:0', 'moneda' => 'required|string|size:3', 'ubicacion' => 'nullable|string|max:150', 'imagenes' => 'nullable|array|max:12', 'imagenes.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120', 'descripcion' => 'nullable|string', 'publicado' => 'boolean']);
    }
}