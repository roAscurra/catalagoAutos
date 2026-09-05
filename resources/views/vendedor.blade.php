<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $perfil->nombre_negocio }} | Rodante</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body>
<header class="topbar"><a class="brand" href="{{ route('catalogo') }}"><span>R</span> RODANTE</a><a href="{{ route('catalogo') }}">← Volver al catálogo</a></header>
<main class="seller-page">
    <p class="eyebrow">Vendedor verificado</p><h1>{{ $perfil->nombre_negocio }}</h1>
    <p class="seller-description">{{ $perfil->descripcion ?: 'Encontrá vehículos seleccionados y atención personalizada.' }}</p>
    <div class="seller-contact">{{ $perfil->direccion }} · {{ $perfil->telefono }} @if($perfil->plan)<span>{{ $perfil->plan->name }}</span>@endif</div>
    <h2>Publicaciones <small>{{ $perfil->vehiculos->count() }}</small></h2>
    <div class="vehicle-grid">@forelse($perfil->vehiculos as $vehiculo)@php($image = $vehiculo->imagen ? asset('storage/'.$vehiculo->imagen) : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=900&q=80')<article class="vehicle-card"><div class="vehicle-image" style="background-image:url('{{ $image }}')"><span class="vehicle-type">{{ ucfirst($vehiculo->tipo) }}</span></div><div class="vehicle-info"><p class="muted">{{ $vehiculo->marca?->nombre }}</p><h3>{{ $vehiculo->modelo?->nombre }}</h3><div class="vehicle-meta"><span>{{ $vehiculo->anio }}</span><strong>{{ $vehiculo->moneda }} {{ number_format($vehiculo->precio ?? 0, 0, ',', '.') }}</strong></div></div></article>@empty<div class="empty-state"><strong>Sin publicaciones activas.</strong><span>Este vendedor todavía no publicó vehículos.</span></div>@endforelse</div>
</main>
</body></html>