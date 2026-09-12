@extends('admin.layout')

@section('content')

@php($editing = filled($item))

<div class="admin-heading">
    <div>
        <p class="eyebrow">{{ $editing ? 'Editar publicación' : 'Nueva publicación' }}</p>
        <h1>{{ $editing ? 'Actualizá tu rodado.' : 'Mostrá tu próximo rodado.' }}</h1>
        <p>La información se mostrará en el catálogo general.</p>
    </div>


<a href="{{ route('panel.dashboard') }}">← Volver</a>


</div>

<form
    class="admin-form"
    method="POST"
    action="{{ $editing ? route('panel.vehiculos.update', ['vehiculo' => $item->public_id]) : route('panel.vehiculos.store') }}"
    enctype="multipart/form-data"
>
    @csrf


@if($editing)
    @method('PUT')
@endif

{{-- Resumen de errores --}}
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
        Tipo

        <select name="tipo" required>
            @foreach(['auto', 'moto', 'camion', 'camioneta', 'trafic', 'otro'] as $tipo)
                <option
                    value="{{ $tipo }}"
                    @selected(old('tipo', $item?->tipo) === $tipo)
                >
                    {{ ucfirst($tipo) }}
                </option>
            @endforeach
        </select>

        @error('tipo')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Marca

        <select name="marca_id" required>
            <option value="">Seleccionar marca</option>

            @foreach($marcas as $marca)
                <option
                    value="{{ $marca->id }}"
                    @selected(old('marca_id', $item?->marca_id) == $marca->id)
                >
                    {{ $marca->nombre }}
                </option>
            @endforeach
        </select>

        @error('marca_id')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Modelo

        <select name="modelo_id" required>
            <option value="">Seleccionar modelo</option>

            @foreach($marcas as $marca)
                @foreach($marca->modelos as $modelo)
                    <option
                        value="{{ $modelo->id }}"
                        @selected(old('modelo_id', $item?->modelo_id) == $modelo->id)
                    >
                        {{ $marca->nombre }} · {{ $modelo->nombre }}
                    </option>
                @endforeach
            @endforeach
        </select>

        @error('modelo_id')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Año

        <input
            type="number"
            name="anio"
            min="1900"
            max="2100"
            value="{{ old('anio', $item?->anio) }}"
        >

        @error('anio')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Kilómetros

        <input
            type="number"
            name="kilometros"
            min="0"
            value="{{ old('kilometros', $item?->kilometros) }}"
        >

        @error('kilometros')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Precio

        <input
            type="number"
            name="precio"
            min="0"
            step="0.01"
            value="{{ old('precio', $item?->precio) }}"
        >

        @error('precio')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Moneda

        <select name="moneda" required>
            <option
                value="USD"
                @selected(old('moneda', $item?->moneda ?: 'USD') === 'USD')
            >
                USD
            </option>

            <option
                value="ARS"
                @selected(old('moneda', $item?->moneda) === 'ARS')
            >
                ARS
            </option>
        </select>

        @error('moneda')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Combustible

        <select name="combustible" required>
            <option value="">Seleccionar combustible</option>

            <option
                value="Nafta"
                @selected(old('combustible', $item?->combustible) === 'Nafta')
            >
                Nafta
            </option>

            <option
                value="Diesel"
                @selected(old('combustible', $item?->combustible) === 'Diesel')
            >
                Diesel
            </option>

            <option
                value="GNC"
                @selected(old('combustible', $item?->combustible) === 'GNC')
            >
                GNC
            </option>

            <option
                value="Electrico"
                @selected(old('combustible', $item?->combustible) === 'Electrico')
            >
                Eléctrico
            </option>

            <option
                value="Nafta + GNC"
                @selected(old('combustible', $item?->combustible) === 'Nafta + GNC')
            >
                Nafta + GNC
            </option>
        </select>

        @error('combustible')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label>
        Ubicación

        <input
            name="ubicacion"
            value="{{ old('ubicacion', $item?->ubicacion) }}"
            placeholder="Ciudad, provincia"
        >

        @error('ubicacion')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label class="full upload-field">
        {{ $editing ? 'Reemplazar galería' : 'Fotos del vehículo' }}

        <input
            type="file"
            name="imagenes[]"
            accept="image/jpeg,image/png,image/webp"
            multiple
        >

        <small>
            Hasta 12 imágenes · JPG, PNG o WEBP · máximo 5 MB cada una.
            Al editar, reemplaza la galería actual.
        </small>

        @error('imagenes')
            <small class="field-error">{{ $message }}</small>
        @enderror

        @error('imagenes.*')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>

    <label class="full upload-field">
        Fotos de la venta

        <input
            type="file"
            name="imagenes_venta[]"
            accept="image/jpeg,image/png,image/webp"
            multiple
            data-sale-input
            disabled
        >

        <small>
            Se usan para documentar la operación vendida y opcionalmente mostrarla en la landing.
        </small>

        @error('imagenes_venta')
            <small class="field-error">{{ $message }}</small>
        @enderror

        @error('imagenes_venta.*')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>

    <label class="full">
        Descripción

        <textarea
            name="descripcion"
            rows="5"
        >{{ old('descripcion', $item?->descripcion) }}</textarea>

        @error('descripcion')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>


    <label class="check">
        <input
            type="checkbox"
            name="publicado"
            value="1"
            @checked(old('publicado', $item?->publicado ?? true))
        >

        Publicado
    </label>

    <label class="check">
        <input
            type="checkbox"
            name="vendido"
            value="1"
            id="vendido-toggle"
            @checked(old('vendido', $item?->vendido ?? false))
        >

        Vendido
    </label>

    <label class="check">
        <input
            type="checkbox"
            name="mostrar_en_landing"
            value="1"
            @checked(old('mostrar_en_landing', $item?->mostrar_en_landing ?? false))
        >

        Mostrar en la landing
    </label>

    <label>
        Fecha de venta

        <input
            type="date"
            name="fecha_venta"
            id="fecha_venta"
            value="{{ old('fecha_venta', $item?->fecha_venta?->format('Y-m-d')) }}"
            data-sale-date
            disabled
        >

        @error('fecha_venta')
            <small class="field-error">{{ $message }}</small>
        @enderror
    </label>

</div>


@if($editing && $item->imagenes->isNotEmpty())
    <div class="current-gallery">
        <p class="eyebrow">Galería actual</p>

        <div class="detail-thumbs">
            @foreach($item->imagenes as $image)
                <img
                    src="{{ asset('storage/' . $image->ruta) }}"
                    alt="Imagen de la publicación"
                >
            @endforeach
        </div>
    </div>
@endif

@if($editing && $item->ventaImagenes->isNotEmpty())
    <div class="current-gallery">
        <p class="eyebrow">Fotos de la venta</p>

        <div class="detail-thumbs">
            @foreach($item->ventaImagenes as $image)
                <img
                    src="{{ asset('storage/' . $image->ruta) }}"
                    alt="Imagen de venta"
                >
            @endforeach
        </div>
    </div>
@endif


<button class="button button-orange" type="submit">
    {{ $editing ? 'Guardar cambios' : 'Publicar vehículo' }}
    <span>↗</span>
</button>


</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('vendido-toggle');
        const saleDate = document.getElementById('fecha_venta');
        const saleFiles = document.querySelectorAll('[data-sale-input]');

        function syncSaleFields() {
            const isSold = !!toggle && toggle.checked;

            if (saleDate) {
                saleDate.disabled = !isSold;
                saleDate.required = isSold;
            }

            saleFiles.forEach((input) => {
                input.disabled = !isSold;
                if (!isSold) {
                    input.value = '';
                }
            });
        }

        if (toggle) {
            toggle.addEventListener('change', syncSaleFields);
            syncSaleFields();
        }
    });
</script>

@endsection
