@extends('admin.layout')

@section('content')
<div class="admin-heading">
    <div>
        <p class="eyebrow">Cuenta</p>
        <h1>Mi perfil</h1>
        <p>Actualizá la identidad de tu negocio y la información visible para clientes.</p>
    </div>
</div>

<section class="panel-summary-grid">
    <div class="summary-card">
        <span class="eyebrow">Negocio</span>
        <h2>{{ $perfil->nombre_negocio }}</h2>
        <p>{{ $perfil->descripcion ?: 'Todavía no agregaste una descripción.' }}</p>
    </div>

    <div class="summary-card">
        <span class="eyebrow">Contacto</span>
        <ul class="info-list">
            <li><strong>Teléfono:</strong> {{ $perfil->telefono ?: 'Sin cargar' }}</li>
            <li><strong>WhatsApp:</strong> {{ $perfil->whatsapp ?: 'Sin cargar' }}</li>
            <li><strong>Instagram:</strong> {{ $perfil->instagram ?: 'Sin cargar' }}</li>
            <li><strong>Dirección:</strong> {{ $perfil->direccion ?: 'Sin cargar' }}</li>
        </ul>
    </div>

    <div class="summary-card">
        <span class="eyebrow">Página pública</span>
        <p><strong>{{ url('/vendedor/' . $perfil->slug) }}</strong></p>
        <div class="profile-actions">
            @if($perfil->user?->rol === 'agencia')
                <a class="button button-orange" href="{{ route('panel.landing.edit') }}">Editar landing <span>↗</span></a>
            @endif
            <a class="button button-secondary" href="{{ route('panel.perfil.edit') }}">Editar perfil</a>
        </div>
    </div>
</section>

<section class="panel-form-card">
    <div class="card-header">
        <h2>Datos principales</h2>
    </div>

    <dl class="profile-meta">
        <div>
            <dt>Tipo de cuenta</dt>
            <dd>{{ $perfil->user?->rol === 'agencia' ? 'Agencia / concesionaria' : 'Vendedor individual' }}</dd>
        </div>
        <div>
            <dt>Plan actual</dt>
            <dd>{{ $perfil->plan?->name ?: 'Sin plan seleccionado' }}</dd>
        </div>
        <div>
            <dt>URL pública</dt>
            <dd>{{ $perfil->slug ?: 'No disponible' }}</dd>
        </div>
        <div>
            <dt>Publicaciones activas</dt>
            <dd>{{ $perfil->vehiculos->where('publicado', true)->where('vendido', false)->count() }}</dd>
        </div>
        <div>
            <dt>Vehículos vendidos</dt>
            <dd>{{ $perfil->vehiculos->where('vendido', true)->count() }}</dd>
        </div>
        <div>
            <dt>Ventas destacadas en landing</dt>
            <dd>{{ $perfil->vehiculos->where('vendido', true)->where('mostrar_en_landing', true)->count() }}</dd>
        </div>
    </dl>
</section>
@endsection
