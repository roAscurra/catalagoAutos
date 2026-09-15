<!doctype html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Rodante | Catálogo de vehículos</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="catalogo-home">

<header class="topbar">
    <div class="topbar-left">
        <a class="brand" href="{{ route('catalogo') }}">
            <span>R</span>
            RODANTE
        </a>
    </div>

    <button class="topbar-toggle" type="button" aria-label="Abrir menú" aria-expanded="false">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 6h18M3 12h18M3 18h18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
    </button>

    <nav>
        <a href="#catalogo">Catálogo</a>
        <a href="#planes">Planes</a>

        @auth

            @if(auth()->user()->tieneRol('admin'))

                <a class="nav-action" href="{{ route('admin.dashboard') }}">
                    Administración <b>↗</b>
                </a>

            @else

                <a class="nav-action" href="{{ route('panel.dashboard') }}">
                    Mi panel <b>↗</b>
                </a>

            @endif

        @else

            <a class="nav-action" href="{{ route('register') }}">
                Publicar vehículo <b>↗</b>
            </a>

            <a href="{{ route('login') }}">
                Ingresar
            </a>

        @endauth
    </nav>
</header>

<main>


{{-- HERO --}}
<section class="hero">

    <div class="hero-copy">

        <p class="eyebrow">
            BUSCÁ. ELEGÍ. RODÁ.
        </p>

        <h1>
            Tu próximo vehículo
            <br>
            <em>está en marcha.</em>
        </h1>

        <p class="hero-text">
            Explorá vehículos disponibles de vendedores y agencias,
            compará opciones y encontrá el que mejor se adapta a vos.
        </p>

        <div class="hero-actions">
            <a class="button button-dark" href="#catalogo">
                Explorar vehículos
                <span>↓</span>
            </a>

            <a class="button button-secondary" href="{{ route('register') }}">
                Publicar tu auto
            </a>
        </div>

    </div>


    <div class="hero-art" aria-hidden="true">

        <div class="sun"></div>

        <div class="road"></div>

        <div class="hero-stat">

            <strong>
                {{ $vehiculos->total() }}
            </strong>

            <span>
                publicaciones
                <br>
                activas
            </span>

        </div>

    </div>

</section>


{{-- CATÁLOGO --}}
<section class="catalogue" id="catalogo">

    <div class="section-heading">

        <div>

            <p class="eyebrow">
                EXPLORÁ RODANTE
            </p>

            <h2>
                Vehículos que pueden ser para vos
            </h2>

        </div>

        <span class="result-count">
            {{ $vehiculos->total() }}
            {{ $vehiculos->total() === 1 ? 'resultado' : 'resultados' }}
        </span>

    </div>


    {{-- FILTROS --}}
    <form
        class="filters"
        method="GET"
        action="{{ route('catalogo') }}"
    >

        <label>
            Buscar

            <input
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Marca o modelo"
            >
        </label>


        <label>
            Tipo

            <select name="tipo">

                <option value="">
                    Todos los tipos
                </option>

                @foreach($tipos as $tipo)

                    <option
                        value="{{ $tipo }}"
                        @selected(request('tipo') === $tipo)
                    >
                        {{ ucfirst($tipo) }}
                    </option>

                @endforeach

            </select>

        </label>


        <label>
            Marca

            <select name="marca">

                <option value="">
                    Todas las marcas
                </option>

                @foreach($marcas as $marca)

                    <option
                        value="{{ $marca->id }}"
                        @selected((int) request('marca') === $marca->id)
                    >
                        {{ $marca->nombre }}
                    </option>

                @endforeach

            </select>

        </label>


        <button
            class="button button-orange"
            type="submit"
        >
            Filtrar
            <span>→</span>
        </button>

    </form>


    {{-- RESULTADOS --}}
    <div class="vehicle-grid">

        @forelse($vehiculos as $vehiculo)

            @php
                $image = $vehiculo->imagenes->first()?->ruta ?: $vehiculo->imagen;

                $image = $image
                    ? asset('storage/' . $image)
                    : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=900&q=80';
            @endphp


            <article class="vehicle-card">

                {{-- IMAGEN --}}
                <a href="{{ route('vehiculo.show', $vehiculo) }}">

                    <div
                        class="vehicle-image"
                        style="background-image:
                            linear-gradient(
                                180deg,
                                transparent 45%,
                                rgba(20,24,24,.72)
                            ),
                            url('{{ $image }}')"
                    >

                        <span class="vehicle-type">
                            {{ ucfirst($vehiculo->tipo) }}
                        </span>

                        <span class="vehicle-year">
                            {{ $vehiculo->anio ?: '----' }}
                        </span>

                    </div>

                </a>


                {{-- INFORMACIÓN --}}
                <div class="vehicle-info">

                    <p class="muted">
                        {{ $vehiculo->marca?->nombre }}
                    </p>

                    <h3>
                        {{ $vehiculo->modelo?->nombre }}
                    </h3>

                    <div class="vehicle-meta">

                        <span>
                            {{ number_format($vehiculo->kilometros ?? 0, 0, ',', '.') }} km
                        </span>

                        <span>
                            {{ $vehiculo->combustible ?: 'Consultar' }}
                        </span>

                        <strong>
                            {{ $vehiculo->moneda }}
                            {{ number_format($vehiculo->precio ?? 0, 0, ',', '.') }}
                        </strong>

                    </div>

                    <p class="vehicle-seller">
                        Vende:
                        <strong>
                            {{ $vehiculo->perfil?->nombre_negocio ?: 'Vendedor individual' }}
                        </strong>
                    </p>

                    <a href="{{ route('vehiculo.show', $vehiculo) }}">
                        Ver publicación
                        <span>↗</span>
                    </a>

                    @if($vehiculo->perfil?->esAgencia())

                        <a href="{{ route('vendedor', $vehiculo->perfil->slug) }}">
                            Ver catálogo del vendedor
                            <span>↗</span>
                        </a>

                    @endif

                </div>

            </article>

        @empty

            <div class="empty-state">

                <strong>
                    No encontramos vehículos.
                </strong>

                <span>
                    Probá modificar los filtros o volvé a explorar el catálogo.
                </span>

                <a
                    class="button button-orange"
                    href="{{ route('catalogo') }}"
                >
                    Ver todos
                    <span>↗</span>
                </a>

            </div>

        @endforelse

    </div>


    {{-- PAGINACIÓN --}}
    @if($vehiculos->hasPages())

        <div class="catalogue-pagination">
            {{ $vehiculos->links() }}
        </div>

    @endif

</section>


{{-- PLANES --}}
<section class="plans" id="planes">

    <div class="section-heading">

        <div>

            <p class="eyebrow">
                PARA VENDEDORES
            </p>

            <h2>
                Publicá tus vehículos
            </h2>

        </div>

        <p class="section-note">
            Elegí el plan que mejor se adapta
            <br>
            a tu negocio.
        </p>

    </div>


    <div class="plan-grid">

        @forelse($planes as $plan)

            <article
                class="plan-card @if($loop->iteration === 2) featured @endif"
            >

                <div>

                    <span class="plan-number">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <h3>
                        {{ $plan->name }}
                    </h3>

                    <p>
                        {{ $plan->vehicle_limit
                            ? 'Hasta ' . $plan->vehicle_limit . ' vehículos publicados'
                            : 'Publicaciones ilimitadas'
                        }}
                    </p>

                </div>


                <div class="plan-bottom">

                    <strong>
                        ${{ number_format($plan->monthly_price, 0, ',', '.') }}

                        <small>
                            /mes
                        </small>
                    </strong>

                    <a href="{{ route('register') }}">
                        Elegir plan ↗
                    </a>

                </div>

            </article>

        @empty

            <p class="empty-plans">
                Los planes estarán disponibles muy pronto.
            </p>

        @endforelse

    </div>

</section>


</main>

{{-- FOOTER --}}

<footer>


<div>

    <a
        class="brand"
        href="{{ route('catalogo') }}"
    >
        <span>R</span>
        RODANTE
    </a>


</div>


<small>
    © {{ date('Y') }} Rodante
</small>


</footer>

</body>
</html>
