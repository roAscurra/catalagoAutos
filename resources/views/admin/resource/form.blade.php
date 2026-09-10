@extends('admin.layout')
@section('content')
@php($labels = ['planes'=>'Plan','perfiles'=>'Vendedor','marcas'=>'Marca','modelos'=>'Modelo','vehiculos'=>'Vehículo'])
<div class="admin-heading">
    <div>
        <p class="eyebrow">{{ $item ? 'Editar' : 'Nuevo' }} registro</p>
        <h1>{{ $labels[$resource] }}</h1>
    </div><a href="{{ route('admin.index', $resource) }}">← Volver</a>
</div>
<form class="admin-form" method="POST" enctype="multipart/form-data" action="{{ $item ? route('admin.update', [$resource, $item->id]) : route('admin.store', $resource) }}">@csrf @if($item) @method('PUT') @endif
    @if($resource === 'planes')<div class="form-grid"><label>Nombre<input name="name" value="{{ old('name', $item?->name) }}" required></label><label>Slug<input name="slug" value="{{ old('slug', $item?->slug) }}" required></label><label>Límite de vehículos<input type="number" name="vehicle_limit" value="{{ old('vehicle_limit', $item?->vehicle_limit) }}"></label><label>Precio mensual<input type="number" step="0.01" name="monthly_price" value="{{ old('monthly_price', $item?->monthly_price ?? 0) }}" required></label></div>
    @elseif($resource === 'marcas')<div class="form-grid"><label>Nombre<input name="nombre" value="{{ old('nombre', $item?->nombre) }}" required></label><label>Slug<input name="slug" value="{{ old('slug', $item?->slug) }}" required></label></div>
    @elseif($resource === 'modelos')<div class="form-grid"><label>Marca<select name="marca_id" required>
                <option value="">Seleccionar</option>@foreach($marcas as $marca)<option value="{{ $marca->id }}" @selected(old('marca_id', $item?->marca_id) == $marca->id)>{{ $marca->nombre }}</option>@endforeach
            </select></label><label>Nombre<input name="nombre" value="{{ old('nombre', $item?->nombre) }}" required></label><label>Slug<input name="slug" value="{{ old('slug', $item?->slug) }}" required></label></div>
    @elseif($resource === 'perfiles')<div class="form-grid"><label>Usuario<select name="user_id" required>
                <option value="">Seleccionar</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('user_id', $item?->user_id) == $user->id)>{{ $user->name }} · {{ $user->email }}</option>@endforeach
            </select></label><label>Plan<select name="plan_id">
                <option value="">Sin plan</option>@foreach($plans as $plan)<option value="{{ $plan->id }}" @selected(old('plan_id', $item?->plan_id) == $plan->id)>{{ $plan->name }}</option>@endforeach
            </select></label><label>Nombre del negocio<input name="nombre_negocio" value="{{ old('nombre_negocio', $item?->nombre_negocio) }}" required></label><label>Slug<input name="slug" value="{{ old('slug', $item?->slug) }}" required></label><label>Teléfono<input name="telefono" value="{{ old('telefono', $item?->telefono) }}"></label><label>Dirección<input name="direccion" value="{{ old('direccion', $item?->direccion) }}"></label><label class="full">Descripción<textarea name="descripcion">{{ old('descripcion', $item?->descripcion) }}</textarea></label><label>Color principal<input type="color" name="color_principal" value="{{ old('color_principal', $item?->color_principal ?: '#e85d04') }}"></label></div>
    @else<div class="form-grid"><label>Vendedor<select name="perfil_id" required>
                <option value="">Seleccionar</option>@foreach($perfiles as $perfil)<option value="{{ $perfil->id }}" @selected(old('perfil_id', $item?->perfil_id) == $perfil->id)>{{ $perfil->nombre_negocio }}</option>@endforeach
            </select></label><label>Tipo<select name="tipo" required>@foreach(['auto','moto','camion','camioneta','trafic','otro'] as $tipo)<option value="{{ $tipo }}" @selected(old('tipo', $item?->tipo) === $tipo)>{{ ucfirst($tipo) }}</option>@endforeach</select></label><label>Marca<select name="marca_id" required>
                <option value="">Seleccionar</option>@foreach($marcas as $marca)<option value="{{ $marca->id }}" @selected(old('marca_id', $item?->marca_id) == $marca->id)>{{ $marca->nombre }}</option>@endforeach
            </select></label><label>Modelo<select name="modelo_id" required>
                <option value="">Seleccionar modelo</option>@foreach($marcas as $marca)@foreach($marca->modelos as $modelo)<option value="{{ $modelo->id }}" @selected(old('modelo_id', $item?->modelo_id) == $modelo->id)>{{ $marca->nombre }} · {{ $modelo->nombre }}</option>@endforeach @endforeach
            </select></label><label>Año<input type="number" name="anio" value="{{ old('anio', $item?->anio) }}"></label><label>Kilómetros<input type="number" name="kilometros" value="{{ old('kilometros', $item?->kilometros) }}"></label><label>Precio<input type="number" step="0.01" name="precio" value="{{ old('precio', $item?->precio) }}"></label><label>Moneda<input name="moneda" value="{{ old('moneda', $item?->moneda ?: 'USD') }}" maxlength="3" required></label><label class="full upload-field">Imágenes del vehículo<input type="file" name="imagenes[]" accept="image/jpeg,image/png,image/webp" multiple><small>Hasta 12 imágenes · JPG, PNG o WEBP · máximo 5 MB cada una</small></label><label class="full">Descripción<textarea name="descripcion">{{ old('descripcion', $item?->descripcion) }}</textarea></label><label class="check"><input type="checkbox" name="publicado" value="1" @checked(old('publicado', $item?->publicado ?? true))> Publicado</label></div>@endif
    <button class="button button-orange" type="submit">{{ $item ? 'Guardar cambios' : 'Crear registro' }} <span>→</span></button>
</form>
@endsection