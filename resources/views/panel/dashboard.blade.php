@extends('admin.layout')
@section('content')
<div class="admin-heading">
    <div>
        <p class="eyebrow">Tu espacio Rodante</p>
        <h1>{{ $perfil->nombre_negocio }}</h1>
        <p>{{ $perfil->plan?->name ?: 'Sin plan seleccionado' }} · {{ $perfil->vehiculos->count() }} publicaciones</p>
    </div>
</div>
<section class="dashboard-actions">
    <div><span class="eyebrow">Acciones rápidas</span>
        <h2>¿Qué querés hacer?</h2>
    </div>
    <div class="dashboard-action-grid"><a class="dashboard-action primary"
            href="{{ route('panel.vehiculos.create') }}"><span class="action-icon">+</span><strong>Publicar
                vehículo</strong><small>Sumá una nueva publicación</small></a><a class="dashboard-action"
            href="{{ route('catalogo') }}"><span class="action-icon">↗</span><strong>Ver catálogo
                general</strong><small>Explorá todo Rodante</small></a>@if($perfil->user?->rol === 'agencia')<a
            class="dashboard-action" href="{{ route('panel.landing.edit') }}"><span
                class="action-icon">✦</span><strong>Editar mi página</strong><small>Personalizá tu landing</small></a><a
            class="dashboard-action" href="{{ route('vendedor', $perfil->slug) }}" target="_blank"><span
                class="action-icon">◉</span><strong>Ver mi catálogo</strong><small>Abrí tu página
                pública</small></a>@endif</div>
</section>
@if($perfil->user?->rol === 'agencia')<div class="seller-page-banner">
    <div><span class="eyebrow">Tu página pública</span><strong>rodante.test/vendedor/{{ $perfil->slug }}</strong></div>
    <a href="{{ route('vendedor', $perfil->slug) }}" target="_blank">Ver página ↗</a>
</div>@else<div class="seller-page-banner">
    <div><span class="eyebrow">Cuenta individual</span><strong>Publicá tus vehículos en el catálogo general.</strong>
    </div>
</div>@endif
<h2 class="panel-title">Tus publicaciones</h2>
<div class="vehicle-grid">@forelse($perfil->vehiculos as $vehiculo)@php($image = $vehiculo->imagenes->first()?->ruta ?:
    $vehiculo->imagen)@php($image = $image ? asset('storage/'.$image) :
    'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=900&q=80')<article
        class="vehicle-card"><a href="{{ route('vehiculo.show',$vehiculo) }}">
            <div class="vehicle-image" style="background-image:url('{{ $image }}')"><span
                    class="vehicle-type">{{ ucfirst($vehiculo->tipo) }}</span></div>
        </a>
        <div class="vehicle-info">
            <p class="muted">{{ $vehiculo->marca?->nombre }}</p>
            <h3>{{ $vehiculo->modelo?->nombre }}</h3>
            <div class="vehicle-meta"><span>{{ $vehiculo->anio }}</span><strong>{{ $vehiculo->moneda }}
                    {{ number_format($vehiculo->precio ?? 0,0,',','.') }}</strong></div>
            <div class="card-actions"><a class="text-button"
                    href="{{ route('panel.vehiculos.edit',['vehiculo' => $vehiculo->public_id]) }}">Editar
                    publicación</a>
                <form method="POST" action="{{ route('panel.vehiculos.destroy',$vehiculo) }}">@csrf
                    @method('DELETE')<button class="text-button danger" type="submit">Eliminar</button></form>
            </div>
        </div>
    </article>@empty<div class="empty-state"><strong>Tu inventario está esperando.</strong><span>Publicá tu primer
            vehículo para que aparezca en el catálogo.</span><a class="button button-orange"
            href="{{ route('panel.vehiculos.create') }}">Crear publicación <span>+</span></a></div>@endforelse</div>
@endsection