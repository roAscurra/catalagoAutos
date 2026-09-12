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
        <div class="topbar-left">
            <button class="mobile-nav-toggle" type="button" aria-label="Abrir menú" aria-expanded="false">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 6h18M3 12h18M3 18h18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </button>
            <a class="brand" href="{{ route('catalogo') }}">
                <span>R</span> RODANTE
            </a>
        </div>
    </header>

    <div class="admin-shell">

        <aside class="admin-nav">

            @auth

                @if (auth()->user()->tieneRol('admin'))

                    <div class="admin-nav-header">
                        <span class="admin-nav-eyebrow">Administración</span>
                        <h2>Panel de control</h2>
                    </div>

                    <nav class="admin-nav-section">

                        <span class="admin-nav-title">General</span>

                        <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                            <x-heroicon-o-home class="nav-icon" />

                            <span>Resumen</span>
                        </a>

                    </nav>

                    <nav class="admin-nav-section">

                        <span class="admin-nav-title">Catálogo</span>

                        <a href="{{ route('admin.index', 'vehiculos') }}"
                        class="{{ request()->is('admin/vehiculos*') ? 'active' : '' }}">

                            <x-heroicon-o-truck class="nav-icon" />

                            <span>Vehículos</span>
                        </a>

                        <a href="{{ route('admin.index', 'marcas') }}"
                        class="{{ request()->is('admin/marcas*') ? 'active' : '' }}">

                            <x-heroicon-o-tag class="nav-icon" />

                            <span>Marcas</span>
                        </a>

                        <a href="{{ route('admin.index', 'modelos') }}"
                        class="{{ request()->is('admin/modelos*') ? 'active' : '' }}">

                            <x-heroicon-o-list-bullet class="nav-icon" />

                            <span>Modelos</span>
                        </a>

                    </nav>

                    <nav class="admin-nav-section">

                        <span class="admin-nav-title">Usuarios</span>

                        <a href="{{ route('admin.index', 'perfiles') }}"
                        class="{{ request()->is('admin/perfiles*') ? 'active' : '' }}">

                            <x-heroicon-o-users class="nav-icon" />

                            <span>Vendedores</span>
                        </a>

                    </nav>

                    <nav class="admin-nav-section">

                        <span class="admin-nav-title">Configuración</span>

                        <a href="{{ route('admin.index', 'planes') }}"
                        class="{{ request()->is('admin/planes*') ? 'active' : '' }}">

                            <x-heroicon-o-credit-card class="nav-icon" />

                            <span>Planes</span>
                        </a>

                    </nav>

                    <nav class="admin-nav-section">
                        <span class="admin-nav-title">Accesos</span>

                        <a href="{{ route('catalogo') }}">
                            <x-heroicon-o-arrow-top-right-on-square class="nav-icon" />
                            <span>Ver catálogo</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="header-logout">
                            @csrf
                            <button type="submit">
                                <x-heroicon-o-arrow-left-start-on-rectangle class="nav-icon" />
                                <span>Cerrar sesión</span>
                            </button>
                        </form>
                    </nav>

                @else

                    <div class="admin-nav-header">
                        <span class="admin-nav-eyebrow">Mi espacio</span>
                        <h2>Vendedor</h2>
                    </div>

                    <nav class="admin-nav-section">

                        <span class="admin-nav-title">Publicaciones</span>

                        <a href="{{ route('panel.dashboard') }}"
                        class="{{ request()->routeIs('panel.dashboard') ? 'active' : '' }}">

                            <x-heroicon-o-truck class="nav-icon" />

                            <span>Mis vehículos</span>
                        </a>

                    </nav>

                    <nav class="admin-nav-section">

                        <span class="admin-nav-title">Cuenta</span>

                        <a href="{{ route('panel.perfil') }}">
                            <x-heroicon-o-user-circle class="nav-icon" />
                            <span>Mi perfil</span>
                        </a>

                        <a href="{{ route('panel.plan') }}">
                            <x-heroicon-o-credit-card class="nav-icon" />
                            <span>Mi plan</span>
                        </a>

                    </nav>

                    <nav class="admin-nav-section">
                        <span class="admin-nav-title">Accesos</span>

                        <a href="{{ route('catalogo') }}">
                            <x-heroicon-o-arrow-top-right-on-square class="nav-icon" />
                            <span>Ver catálogo</span>
                        </a>

                        <a href="{{ route('panel.dashboard') }}">
                            <x-heroicon-o-home class="nav-icon" />
                            <span>Mi panel</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="header-logout">
                            @csrf
                            <button type="submit">
                                <x-heroicon-o-arrow-left-start-on-rectangle class="nav-icon" />
                                <span>Cerrar sesión</span>
                            </button>
                        </form>
                    </nav>

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
