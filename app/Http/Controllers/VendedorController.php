<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Plan;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VendedorController extends Controller
{
    public function dashboard(Request $request)
    {
        $perfil = $request->user()->perfil()->with(['plan', 'user', 'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.imagenes', 'vehiculos.ventaImagenes'])->firstOrFail();
        return view('panel.dashboard', compact('perfil'));
    }

    public function perfil(Request $request)
    {
        $perfil = $request->user()->perfil()->with(['plan', 'vehiculos'])->firstOrFail();

        return view('panel.perfil', compact('perfil'));
    }

    public function plan(Request $request)
    {
        $perfil = $request->user()->perfil()->with(['plan', 'vehiculos'])->firstOrFail();

        return view('panel.plan', [
            'perfil' => $perfil,
            'currentPlan' => $perfil->plan,
            'plans' => Plan::orderBy('monthly_price')->get(),
            'stats' => [
                'publicadas' => $perfil->vehiculos->where('publicado', true)->where('vendido', false)->count(),
                'vendidas' => $perfil->vehiculos->where('vendido', true)->count(),
                'landing' => $perfil->vehiculos->where('vendido', true)->where('mostrar_en_landing', true)->count(),
            ],
        ]);
    }

    public function editPerfil(Request $request)
    {
        $perfil = $request->user()->perfil()->firstOrFail();

        return view('panel.perfil.edit', compact('perfil'));
    }

    public function updatePerfil(Request $request)
    {
        $perfil = $request->user()->perfil()->firstOrFail();

        $data = $request->validate([
            'nombre_negocio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'telefono' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'instagram' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:perfil,slug,' . $perfil->id],
        ]);

        if ($perfil->user?->rol === 'agencia') {
            $data['slug'] = $data['slug'] ?? $perfil->slug;
        } else {
            $data['slug'] = $perfil->slug;
        }

        $perfil->update($data);

        return redirect()->route('panel.perfil')->with('success', 'Tu perfil fue actualizado correctamente.');
    }

    public function updatePlan(Request $request)
    {
        $perfil = $request->user()->perfil()->firstOrFail();

        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $perfil->update(['plan_id' => $data['plan_id']]);

        return redirect()->route('panel.plan')->with('success', 'Tu plan fue actualizado correctamente.');
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
            'plantilla' => 'required|in:editorial,alto-contraste,calma', 'hero_estilo' => 'required|in:showcase,spotlight,gallery',
            'color_principal' => 'required|string|size:7', 'color_secundario' => 'required|string|size:7', 'titulo_portada' => 'nullable|string|max:120',
            'subtitulo_portada' => 'nullable|string|max:500', 'whatsapp' => 'nullable|string|max:30', 'instagram' => 'nullable|string|max:100', 'facebook' => 'nullable|string|max:100',
            'imagen_portada' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'secciones' => 'nullable|array', 'secciones.*' => 'in:hero,intro,inventario,contacto,footer',
        ]);

        $allowedSections = ['hero', 'intro', 'inventario', 'contacto', 'footer'];
        $data['secciones'] = array_values(array_unique(array_filter(
            $data['secciones'] ?? $perfil::DEFAULT_SECTIONS,
            fn ($section) => in_array($section, $allowedSections, true)
        )));

        if ($data['secciones'] === []) {
            $data['secciones'] = $perfil::DEFAULT_SECTIONS;
        }

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
        $vehiculo->load(['imagenes', 'ventaImagenes']);

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
        $saleImages = $request->file('imagenes_venta', []);
        $data['imagen'] = null;
        $vehicle = $perfil->vehiculos()->create($data);
        foreach ($images as $order => $image) {
            $path = $image->store('vehiculos', 'public');
            $vehicle->imagenes()->create(['ruta' => $path, 'orden' => $order]);
            if ($order === 0) $vehicle->update(['imagen' => $path]);
        }
        foreach ($saleImages as $order => $image) {
            $path = $image->store('vehiculos/venta', 'public');
            $vehicle->ventaImagenes()->create(['ruta' => $path, 'orden' => $order]);
        }
        return redirect()->route('panel.dashboard')->with('success', 'Vehículo publicado correctamente.');
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->perfil_id === $request->user()->perfil?->id, 403);
        $data = $this->validated($request);
        $images = $request->file('imagenes', []);
        $saleImages = $request->file('imagenes_venta', []);
        unset($data['imagenes'], $data['imagenes_venta']);
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

        if ($saleImages) {
            foreach ($vehiculo->ventaImagenes as $image) {
                Storage::disk('public')->delete($image->ruta);
            }
            $vehiculo->ventaImagenes()->delete();

            foreach ($saleImages as $order => $image) {
                $path = $image->store('vehiculos/venta', 'public');
                $vehiculo->ventaImagenes()->create(['ruta' => $path, 'orden' => $order]);
            }
        }

        return redirect()->route('panel.dashboard')->with('success', 'Publicación actualizada correctamente.');
    }

    public function destroy(Request $request, Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->perfil_id === $request->user()->perfil?->id, 403);
        foreach ($vehiculo->imagenes as $image) Storage::disk('public')->delete($image->ruta);
        foreach ($vehiculo->ventaImagenes as $image) Storage::disk('public')->delete($image->ruta);
        if ($vehiculo->imagen && !$vehiculo->imagenes->contains('ruta', $vehiculo->imagen)) Storage::disk('public')->delete($vehiculo->imagen);
        $vehiculo->delete();
        return back()->with('success', 'Publicación eliminada.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', 'max:50'],
            'marca_id' => ['required', 'exists:marcas,id'],
            'modelo_id' => [
                'required',
                'exists:modelos,id',
                Rule::exists('modelos', 'id')->where(fn ($query) => $query->where('marca_id', $request->input('marca_id'))),
            ],
            'anio' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kilometros' => ['nullable', 'integer', 'min:0'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'moneda' => ['required', 'string', 'size:3'],
            'combustible' => ['required', 'in:Nafta,Diesel,GNC,Electrico,Nafta + GNC'],
            'ubicacion' => ['nullable', 'string', 'max:150'],
            'imagenes' => ['nullable', 'array', 'max:12'],
            'imagenes.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'imagenes_venta' => ['nullable', 'array', 'max:12'],
            'imagenes_venta.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'descripcion' => ['nullable', 'string'],
            'publicado' => ['boolean'],
            'vendido' => ['boolean'],
            'mostrar_en_landing' => ['boolean'],
            'fecha_venta' => ['nullable', 'date'],
        ]);

        if ($request->boolean('vendido') && !$request->filled('fecha_venta')) {
            throw ValidationException::withMessages([
                'fecha_venta' => ['La fecha de venta es obligatoria si marcás el vehículo como vendido.'],
            ]);
        }

        if (!$request->boolean('vendido') && ($request->hasFile('imagenes_venta') || $request->filled('fecha_venta'))) {
            throw ValidationException::withMessages([
                'vendido' => ['Si subís fotos o una fecha de venta, el vehículo debe marcarse como vendido.'],
            ]);
        }

        if ($request->filled('modelo_id') && $request->filled('marca_id')) {
            $modeloBelongsToMarca = \App\Models\Modelo::where('id', $request->input('modelo_id'))
                ->where('marca_id', $request->input('marca_id'))
                ->exists();

            if (!$modeloBelongsToMarca) {
                throw ValidationException::withMessages([
                    'modelo_id' => ['El modelo seleccionado no corresponde a la marca elegida.'],
                ]);
            }
        }

        if ($request->boolean('mostrar_en_landing') && !$request->boolean('publicado')) {
            throw ValidationException::withMessages([
                'mostrar_en_landing' => ['Un vehículo debe estar publicado para mostrarse en la landing.'],
            ]);
        }

        if ($request->boolean('vendido') && $request->boolean('publicado') === false) {
            throw ValidationException::withMessages([
                'publicado' => ['Un vehículo vendido debe permanecer publicado para poder consultarse en el catálogo.'],
            ]);
        }

        return $validated;
    }
}