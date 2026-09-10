<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Perfil;
use App\Models\Plan;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    private array $resources = ['planes', 'perfiles', 'marcas', 'modelos', 'vehiculos'];

    public function index(string $resource)
    {
        $this->ensureResource($resource);
        $model = $this->model($resource);
        $items = $model::query()->with($this->with($resource))->latest()->paginate(15);

        return view('admin.resource.index', compact('resource', 'items'));
    }

    public function create(string $resource)
    {
        $this->ensureResource($resource);

        return view('admin.resource.form', [
            'resource' => $resource,
            'item' => null,
            ...$this->options($resource),
        ]);
    }

    public function store(Request $request, string $resource)
    {
        $this->ensureResource($resource);
        $data = $this->validated($request, $resource);
        $images = $resource === 'vehiculos' ? $request->file('imagenes', []) : [];
        unset($data['imagenes']);
        if ($resource === 'vehiculos') {
            $data['imagen'] = null;
        }
        $item = $this->model($resource)::create($data);
        foreach ($images as $order => $image) {
            $path = $image->store('vehiculos', 'public');
            $item->imagenes()->create(['ruta' => $path, 'orden' => $order]);
            if ($order === 0) $item->update(['imagen' => $path]);
        }

        return redirect()->route('admin.index', $resource)->with('success', 'Registro creado correctamente.');
    }

    public function edit(string $resource, int $id)
    {
        $this->ensureResource($resource);
        $item = $this->model($resource)::findOrFail($id);

        return view('admin.resource.form', [
            'resource' => $resource,
            'item' => $item,
            ...$this->options($resource),
        ]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $this->ensureResource($resource);
        $item = $this->model($resource)::findOrFail($id);
        $data = $this->validated($request, $resource, $item->id);
        $images = $resource === 'vehiculos' ? $request->file('imagenes', []) : [];
        unset($data['imagenes']);
        if ($resource === 'vehiculos' && $images) {
            foreach ($item->imagenes as $image) Storage::disk('public')->delete($image->ruta);
            $item->imagenes()->delete();
            if ($item->imagen) Storage::disk('public')->delete($item->imagen);
            $data['imagen'] = null;
        }
        $item->update($data);
        foreach ($images as $order => $image) {
            $path = $image->store('vehiculos', 'public');
            $item->imagenes()->create(['ruta' => $path, 'orden' => $order]);
            if ($order === 0) $item->update(['imagen' => $path]);
        }

        return redirect()->route('admin.index', $resource)->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy(string $resource, int $id)
    {
        $this->ensureResource($resource);
        $this->model($resource)::findOrFail($id)->delete();

        return redirect()->route('admin.index', $resource)->with('success', 'Registro eliminado correctamente.');
    }

    private function model(string $resource): string
    {
        return match ($resource) {
            'planes' => Plan::class, 'perfiles' => Perfil::class, 'marcas' => Marca::class,
            'modelos' => Modelo::class, 'vehiculos' => Vehiculo::class,
        };
    }

    private function with(string $resource): array
    {
        return match ($resource) {
            'perfiles' => ['user', 'plan'], 'modelos' => ['marca'],
            'vehiculos' => ['perfil', 'marca', 'modelo'], default => [],
        };
    }

    private function options(string $resource): array
    {
        return match ($resource) {
            'perfiles' => ['users' => User::orderBy('name')->get(), 'plans' => Plan::orderBy('name')->get()],
            'modelos' => ['marcas' => Marca::orderBy('nombre')->get()],
            'vehiculos' => ['perfiles' => Perfil::orderBy('nombre_negocio')->get(), 'marcas' => Marca::with('modelos')->orderBy('nombre')->get()],
            default => [],
        };
    }

    private function validated(Request $request, string $resource, ?int $id = null): array
    {
        $unique = fn (string $table) => 'unique:' . $table . ',slug,' . ($id ?: 'NULL');

        $rules = match ($resource) {
            'planes' => ['name' => 'required|string|max:100', 'slug' => ['required', 'string', 'max:100', $unique('plans')], 'vehicle_limit' => 'nullable|integer|min:1', 'monthly_price' => 'required|numeric|min:0'],
            'perfiles' => ['user_id' => 'required|exists:users,id', 'plan_id' => 'nullable|exists:plans,id', 'slug' => ['required', 'string', 'max:100', $unique('perfil')], 'nombre_negocio' => 'required|string|max:255', 'telefono' => 'nullable|string|max:30', 'direccion' => 'nullable|string|max:255', 'descripcion' => 'nullable|string', 'color_principal' => 'required|string|size:7'],
            'marcas' => ['nombre' => 'required|string|max:100', 'slug' => ['required', 'string', 'max:100', $unique('marcas')]],
            'modelos' => ['marca_id' => 'required|exists:marcas,id', 'nombre' => 'required|string|max:100', 'slug' => 'required|string|max:100'],
            'vehiculos' => ['perfil_id' => 'required|exists:perfil,id', 'tipo' => 'required|string|max:50', 'marca_id' => 'required|exists:marcas,id', 'modelo_id' => 'required|exists:modelos,id', 'anio' => 'nullable|integer|min:1900|max:2100', 'kilometros' => 'nullable|integer|min:0', 'precio' => 'nullable|numeric|min:0', 'moneda' => 'required|string|size:3', 'ubicacion' => 'nullable|string|max:150', 'imagenes' => 'nullable|array|max:12', 'imagenes.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120', 'descripcion' => 'nullable|string', 'publicado' => 'boolean'],
        };

        return $request->validate($rules);
    }

    private function ensureResource(string $resource): void
    {
        abort_unless(in_array($resource, $this->resources, true), 404);
    }
}