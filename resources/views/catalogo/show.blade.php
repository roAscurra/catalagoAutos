<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $vehiculo->marca?->nombre }} {{ $vehiculo->modelo?->nombre }} | Rodante</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<header class="topbar"><a class="brand" href="{{ route('catalogo') }}"><span>R</span> RODANTE</a><nav class="header-links"><a href="{{ route('catalogo') }}">Catálogo</a>@auth @if(auth()->user()->tieneRol('admin'))<a class="nav-action" href="{{ route('admin.dashboard') }}">Administración</a>@else<a class="nav-action" href="{{ route('panel.dashboard') }}">Mi panel</a>@endif @else<a class="nav-action" href="{{ route('login') }}">Ingresar</a>@endauth</nav></header>
<nav class="mobile-quick-nav" aria-label="Accesos rápidos"><a href="{{ route('catalogo') }}">Catálogo</a>@auth @if(auth()->user()->tieneRol('admin'))<a href="{{ route('admin.dashboard') }}">Admin</a>@else<a href="{{ route('panel.dashboard') }}">Mi panel</a>@endif @else<a href="{{ route('login') }}">Ingresar</a>@endauth</nav>
<main class="vehicle-detail">
    <a class="eyebrow back-link" href="{{ route('catalogo') }}">Catálogo / {{ ucfirst($vehiculo->tipo) }}</a>
    @php
        $firstImage = $vehiculo->imagenes->first()?->ruta ?: $vehiculo->imagen;
        $mainImage = $firstImage ? asset('storage/' . $firstImage) : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1200&q=80';
    @endphp
    <div class="detail-grid">
        <section class="vehicle-gallery" data-gallery>
            <div class="detail-main-image" data-gallery-main style="background-image:url('{{ $mainImage }}')"><button class="gallery-arrow gallery-prev" type="button" data-gallery-prev aria-label="Imagen anterior">←</button><button class="gallery-arrow gallery-next" type="button" data-gallery-next aria-label="Imagen siguiente">→</button><span class="gallery-counter" data-gallery-counter>1 / {{ max(1, $vehiculo->imagenes->count()) }}</span></div>
            <div class="detail-thumbs" data-gallery-thumbs>
                @forelse($vehiculo->imagenes as $image)
                    <button class="gallery-thumb @if($loop->first) is-active @endif" type="button" data-gallery-thumb="{{ asset('storage/' . $image->ruta) }}"><img src="{{ asset('storage/' . $image->ruta) }}" alt="{{ $vehiculo->marca?->nombre }} {{ $vehiculo->modelo?->nombre }}"></button>
                @empty
                    <button class="gallery-thumb is-active" type="button" data-gallery-thumb="{{ $mainImage }}"><img src="{{ $mainImage }}" alt="{{ $vehiculo->marca?->nombre }} {{ $vehiculo->modelo?->nombre }}"></button>
                @endforelse
            </div>
        </section>
        <section class="detail-copy">
            <span class="vehicle-type detail-type">{{ ucfirst($vehiculo->tipo) }}</span>
            <p class="muted">{{ $vehiculo->marca?->nombre }}</p>
            <h1>{{ $vehiculo->modelo?->nombre }}</h1>
            <strong class="detail-price">{{ $vehiculo->moneda }} {{ number_format($vehiculo->precio ?? 0, 0, ',', '.') }}</strong>
            <div class="detail-specs">
                <span>
                    <b>{{ $vehiculo->anio ?: '----' }}</b>
                    Año
                </span>

                <span>
                    <b>{{ number_format($vehiculo->kilometros ?? 0, 0, ',', '.') }}</b>
                    Km
                </span>

                <span>
                    <b>{{ $vehiculo->combustible ?: 'Consultar' }}</b>
                    Combustible
                </span>

                <span>
                    <b>{{ $vehiculo->ubicacion ?: 'Consultar' }}</b>
                    Ubicación
                </span>
            </div>            
        <p class="detail-description">{{ $vehiculo->descripcion ?: 'Consultá todos los detalles de esta publicación.' }}</p>
            @if($vehiculo->perfil?->user?->rol === 'agencia')
                <p class="seller-label">Vende: <strong>{{ $vehiculo->perfil->nombre_negocio }}</strong></p>
                <div class="detail-actions"><a class="button button-orange" href="{{ route('vendedor', $vehiculo->perfil->slug) }}">Ver catálogo del vendedor <span>↗</span></a>@if($vehiculo->perfil->whatsapp || $vehiculo->perfil->telefono)<a class="button button-whatsapp" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vehiculo->perfil->whatsapp ?: $vehiculo->perfil->telefono) }}?text={{ urlencode('Hola ' . $vehiculo->perfil->nombre_negocio . ', consulto por el ' . $vehiculo->marca?->nombre . ' ' . $vehiculo->modelo?->nombre . ' publicado en Rodante.') }}" target="_blank" rel="noopener">Consultar por WhatsApp <span>↗</span></a>@endif</div>
            @else
                <p class="seller-label">Vende: <strong>{{ $vehiculo->perfil?->nombre_negocio ?: 'Vendedor individual' }}</strong></p>
                @if($vehiculo->perfil?->whatsapp || $vehiculo->perfil?->telefono)<a class="button button-whatsapp" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vehiculo->perfil->whatsapp ?: $vehiculo->perfil->telefono) }}?text={{ urlencode('Hola, consulto por el ' . $vehiculo->marca?->nombre . ' ' . $vehiculo->modelo?->nombre . ' publicado en Rodante.') }}" target="_blank" rel="noopener">Consultar por WhatsApp <span>↗</span></a>@else<div class="seller-chip"><span>Vendedor individual</span><small>El vendedor no configuró WhatsApp.</small></div>@endif
            @endif
        </section>
    </div>
</main>
</body>
</html>