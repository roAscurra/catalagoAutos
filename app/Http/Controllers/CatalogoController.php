<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Marca;
use App\Models\Plan;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $vehiculos = Vehiculo::with(['perfil.user', 'perfil.plan', 'marca', 'modelo', 'imagenes'])
            ->where('publicado', true)
            ->where('vendido', false)
            ->when($request->filled('tipo'), fn ($query) => $query->where('tipo', $request->string('tipo')))
            ->when($request->filled('marca'), fn ($query) => $query->where('marca_id', $request->integer('marca')))
            ->when($request->filled('buscar'), fn ($query) => $query->where(function ($query) use ($request) {
                $term = '%' . $request->string('buscar') . '%';
                $query->whereHas('marca', fn ($brand) => $brand->where('nombre', 'like', $term))
                    ->orWhereHas('modelo', fn ($model) => $model->where('nombre', 'like', $term));
            }))
            ->latest()->paginate(12)->withQueryString();

        return view('catalogo.index', [
            'vehiculos' => $vehiculos,
            'planes' => Plan::orderBy('monthly_price')->get(),
            'tipos' => Vehiculo::where('publicado', true)->distinct()->orderBy('tipo')->pluck('tipo'),
            'marcas' => Marca::whereHas('vehiculos', fn ($query) => $query->where('publicado', true))->orderBy('nombre')->get(),
        ]);
    }

    public function vendedor(string $slug)
    {
        $perfil = Perfil::with(['user', 'plan', 'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.imagenes', 'vehiculos.ventaImagenes'])
            ->where('slug', $slug)->firstOrFail();

        abort_unless($perfil->user?->rol === 'agencia', 404);

        return view('vendedor', compact('perfil'));
    }

    public function vehiculo(Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->publicado && !$vehiculo->vendido, 404);
        $vehiculo->load(['perfil.user', 'marca', 'modelo', 'imagenes']);
        return view('catalogo.show', compact('vehiculo'));
    }
}