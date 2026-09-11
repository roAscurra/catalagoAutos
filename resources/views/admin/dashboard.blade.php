@extends('admin.layout')
@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Rodante</p>
            <h1>Resumen del catálogo</h1>
            <p>Administrá las publicaciones y la identidad de cada vendedor.</p>
        </div>
    </div>
    <div class="admin-cards"><a class="admin-card"
            href="{{ route('admin.index', 'vehiculos') }}"><strong>Vehículos</strong><span>Crear y editar publicaciones
                ↗</span></a><a class="admin-card"
            href="{{ route('admin.index', 'perfiles') }}"><strong>Vendedores</strong><span>Gestionar páginas personalizadas
                ↗</span></a><a class="admin-card"
            href="{{ route('admin.index', 'planes') }}"><strong>Planes</strong><span>Definir límites y precios ↗</span></a><a
            class="admin-card" href="{{ route('admin.index', 'marcas') }}"><strong>Marcas y modelos</strong><span>Organizar
                el catálogo ↗</span></a></div>
@endsection
