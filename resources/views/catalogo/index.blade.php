<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodante | Catálogo de vehículos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ route('catalogo') }}"><span>R</span> RODANTE</a>
    <nav><a href="#catalogo">Catálogo</a><a href="#planes">Planes</a>@auth @if(auth()->user()->tieneRol('admin'))<a class="nav-action" href="{{ route('admin.dashboard') }}">Administración <b>↗</b></a>@else<a class="nav-action" href="{{ route('panel.dashboard') }}">Mi panel <b>↗</b></a>@endif @else<a class="nav-action" href="{{ route('register') }}">Publicar vehículo <b>↗</b></a><a href="{{ route('login') }}">Ingresar</a>@endauth</nav>
</header>
<main>
<section class="hero">
    <div class="hero-copy"><p class="eyebrow">Compra y vende con identidad</p><h1>Tu próximo vehículo<br><em>está en marcha.</em></h1><p class="hero-text">Un catálogo confiable para autos, motos, camiones, camionetas y todo lo que se mueve.</p><a class="button button-dark" href="#catalogo">Explorar catálogo <span>↓</span></a></div>
    <div class="hero-art"><div class="sun"></div><div class="road"></div><div class="hero-stat"><strong>{{ $vehiculos->total() }}</strong><span>publicaciones<br>activas</span></div></div>
</section>
<section class="catalogue" id="catalogo">
    <div class="section-heading"><div><p class="eyebrow">Selección reciente</p><h2>Encontrá tu próximo rodado</h2></div><span class="result-count">{{ $vehiculos->total() }} resultados</span></div>
    <form class="filters" method="GET" action="{{ route('catalogo') }}"><label>Buscar<input name="buscar" value="{{ request('buscar') }}" placeholder="Marca o modelo"></label><label>Tipo<select name="tipo"><option value="">Todos los tipos</option>@foreach($tipos as $tipo)<option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ ucfirst($tipo) }}</option>@endforeach</select></label><label>Marca<select name="marca"><option value="">Todas las marcas</option>@foreach($marcas as $marca)<option value="{{ $marca->id }}" @selected((int) request('marca') === $marca->id)>{{ $marca->nombre }}</option>@endforeach</select></label><button class="button button-orange" type="submit">Filtrar <span>→</span></button></form>
    <div class="vehicle-grid">
    @forelse($vehiculos as $vehiculo)
        @php($image = $vehiculo->imagenes->first()?->ruta ?: $vehiculo->imagen)
        @php($image = $image ? asset('storage/'.$image) : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=900&q=80')
        <article class="vehicle-card"><a href="{{ route('vehiculo.show', $vehiculo) }}"><div class="vehicle-image" style="background-image:linear-gradient(180deg,transparent 45%,rgba(20,24,24,.7)),url('{{ $image }}')"><span class="vehicle-type">{{ ucfirst($vehiculo->tipo) }}</span><span class="vehicle-year">{{ $vehiculo->anio }}</span></div></a><div class="vehicle-info"><p class="muted">{{ $vehiculo->marca?->nombre }}</p><h3>{{ $vehiculo->modelo?->nombre }}</h3><p class="vehicle-seller">Vende: {{ $vehiculo->perfil?->nombre_negocio }}</p><div class="vehicle-meta"><span>{{ number_format($vehiculo->kilometros ?? 0, 0, ',', '.') }} km</span><strong>{{ $vehiculo->moneda }} {{ number_format($vehiculo->precio ?? 0, 0, ',', '.') }}</strong></div><a href="{{ route('vehiculo.show', $vehiculo) }}">Ver publicación <span>↗</span></a>@if($vehiculo->perfil?->esAgencia())<a href="{{ route('vendedor', $vehiculo->perfil->slug) }}">Ver catálogo del vendedor <span>↗</span></a>@endif</div></article>
    @empty
        <div class="empty-state"><strong>El catálogo está tomando forma.</strong><span>Pronto vas a encontrar nuevas publicaciones aquí.</span><a class="button button-orange" href="#planes">Quiero publicar <span>↗</span></a></div>
    @endforelse
    </div>{{ $vehiculos->links() }}
</section>
<section class="plans" id="planes"><div class="section-heading"><div><p class="eyebrow">Para vendedores</p><h2>Tu negocio, a tu manera</h2></div><p class="section-note">Elegí el espacio que acompaña<br>el tamaño de tu inventario.</p></div><div class="plan-grid">@forelse($planes as $plan)<article class="plan-card @if($loop->iteration === 2) featured @endif"><div><span class="plan-number">0{{ $loop->iteration }}</span><h3>{{ $plan->name }}</h3><p>{{ $plan->vehicle_limit ? 'Hasta ' . $plan->vehicle_limit . ' vehículos publicados' : 'Vehículos ilimitados' }}</p></div><div class="plan-bottom"><strong>${{ number_format($plan->monthly_price, 0, ',', '.') }}<small>/mes</small></strong><a href="{{ route('register') }}">Elegir plan ↗</a></div></article>@empty<p class="empty-plans">Los planes estarán disponibles muy pronto.</p>@endforelse</div></section>
</main>
<footer><a class="brand" href="{{ route('catalogo') }}"><span>R</span> RODANTE</a><p>El mercado donde tu próximo viaje comienza.</p><small>© {{ date('Y') }} Rodante</small></footer>
</body>
</html>