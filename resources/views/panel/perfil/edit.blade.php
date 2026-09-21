@extends('admin.layout')

@section('content')
<div class="admin-heading">
    <div>
        <p class="eyebrow">Cuenta</p>
        <h1>Editar perfil</h1>
        <p>Actualizá los datos visibles de tu negocio.</p>
    </div>
    <a href="{{ route('panel.perfil') }}">← Volver</a>
</div>

<section class="profile-edit-card">
    <form class="admin-form" method="POST" action="{{ route('panel.perfil.update') }}">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="flash-error">
                <strong>Revisá los siguientes errores:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-grid">
            <label>
                Nombre del negocio
                <input type="text" name="nombre_negocio" value="{{ old('nombre_negocio', $perfil->nombre_negocio) }}" required>
            </label>

            <label>
                URL pública
                <input type="text" name="slug" value="{{ old('slug', $perfil->slug) }}" placeholder="mi-agencia">
            </label>

            <label class="full">
                Descripción
                <textarea name="descripcion" rows="5">{{ old('descripcion', $perfil->descripcion) }}</textarea>
            </label>

            <label>
                Teléfono
                <input type="text" name="telefono" value="{{ old('telefono', $perfil->telefono) }}">
            </label>

            <label>
                WhatsApp
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $perfil->whatsapp) }}">
            </label>

            <label>
                Instagram
                <input type="text" name="instagram" value="{{ old('instagram', $perfil->instagram) }}">
            </label>

            <label>
                Facebook
                <input type="text" name="facebook" value="{{ old('facebook', $perfil->facebook) }}">
            </label>

            <label class="full">
                Dirección
                <input type="text" name="direccion" value="{{ old('direccion', $perfil->direccion) }}">
            </label>
        </div>

        <div class="profile-actions">
            <button type="submit" class="button button-orange">Guardar cambios <span>↗</span></button>
            <a href="{{ route('panel.perfil') }}" class="button button-secondary">Cancelar</a>
        </div>
    </form>
</section>
@endsection
