<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Administración' }} | Rodante</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="admin-body">

    <header class="topbar">
        <a class="brand" href="{{ route('catalogo') }}">
            <span>R</span> RODANTE
        </a>

        <nav>
            <a href="{{ route('catalogo') }}">Ver catálogo</a>
            @auth
                @if(auth()->user()->tieneRol('admin'))
                    <a class="nav-action" href="{{ route('admin.dashboard') }}">Administración</a>
                @else
                    <a class="nav-action" href="{{ route('panel.dashboard') }}">Mi panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="header-logout">
                    @csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            @else
                <a class="nav-action" href="{{ route('login') }}">Ingresar</a>
            @endauth
        </nav>
    </header>

    <div class="admin-shell">

        <aside class="admin-nav">

            @auth
                @if(auth()->user()->tieneRol('admin'))
                    <p class="eyebrow">Panel de control</p>
                    <h2>Gestionar</h2>
                    <a href="{{ route('admin.dashboard') }}">Resumen</a>
                    <a href="{{ route('admin.index', 'vehiculos') }}">Vehículos</a>
                    <a href="{{ route('admin.index', 'perfiles') }}">Vendedores</a>
                    <a href="{{ route('admin.index', 'planes') }}">Planes</a>
                    <a href="{{ route('admin.index', 'marcas') }}">Marcas</a>
                    <a href="{{ route('admin.index', 'modelos') }}">Modelos</a>
                @else
                    <p class="eyebrow">Espacio del vendedor</p>
                    <h2>Mi cuenta</h2>
                    <a href="{{ route('panel.dashboard') }}">Mis publicaciones</a>
                @endif
            @endauth

        </aside>

        <main class="admin-content">

            @if (session('success'))
                <div class="flash-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="flash-error">
                    Revisá los campos indicados.
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</body>

</html>