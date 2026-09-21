@extends('admin.layout')

@section('content')

@php
    $activeSections = old(
        'secciones',
        $perfil->secciones ?: ['hero', 'intro', 'inventario', 'contacto', 'footer']
    );

    $sectionOptions = [
        'hero' => 'Portada principal',
        'intro' => 'Presentación de la agencia',
        'inventario' => 'Vehículos disponibles',
        'contacto' => 'Datos de contacto',
        'footer' => 'Pie de página',
    ];

    $orderedSections = array_values(
        array_unique(
            array_merge($activeSections, array_keys($sectionOptions))
        )
    );
@endphp

<div class="admin-heading landing-editor-heading">
    <div>
        <p class="eyebrow">Editor de landing</p>

        <h1>Diseñá tu página.</h1>

        <p>
            Elegí qué mostrar, cambiá el estilo y revisá el resultado antes de guardar.
        </p>
    </div>

    <a href="{{ route('panel.dashboard') }}">
        ← Volver
    </a>
</div>

<div class="landing-editor">

    {{-- ============================================================
         EDITOR
    ============================================================= --}}
    <form
        class="admin-form landing-controls"
        id="landing-editor-form"
        method="POST"
        action="{{ route('panel.landing.update') }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="secciones_configurada"
            value="1"
        >

        {{-- ========================================================
             01 · CONTENIDO
        ========================================================= --}}
        <div class="editor-section">

            <span class="editor-kicker">
                01 · Contenido
            </span>

            <h2>Construí tu página</h2>

            <p class="editor-help">
                Arrastrá para ordenar. Desactivá una sección para ocultarla
                sin borrar su contenido.
            </p>

            <div
                class="section-toggles"
                data-sortable-sections
            >

                @foreach ($orderedSections as $key)

                    @php
                        $label = $sectionOptions[$key];
                    @endphp

                    <label
                        class="section-toggle"
                        draggable="true"
                        data-section-item="{{ $key }}"
                    >

                        <span
                            class="drag-handle"
                            aria-hidden="true"
                        >
                            ⠿
                        </span>

                        <input
                            data-preview-section="{{ $key }}"
                            type="checkbox"
                            name="secciones[]"
                            value="{{ $key }}"
                            @checked(in_array($key, $activeSections, true))
                        >

                        <span>
                            <b>{{ $label }}</b>

                            <small>
                                @if ($key === 'hero')
                                Título, subtítulo e imagen
                                @elseif ($key === 'inventario')
                                Tu catálogo de vehículos
                                @else
                                Contenido editorial
                                @endif
                            </small>
                        </span>

                        <i>✓</i>

                    </label>

                @endforeach

            </div>

        </div>


        {{-- ========================================================
             02 · IDENTIDAD
        ========================================================= --}}
        <div class="editor-section">

            <span class="editor-kicker">
                02 · Identidad
            </span>

            <h2>La primera impresión</h2>

            <div class="form-grid">

                <label>
                    Nombre de agencia

                    <input
                        data-preview="agency"
                        name="nombre_negocio"
                        value="{{ old('nombre_negocio', $perfil->nombre_negocio) }}"
                        required
                    >
                </label>

                <label>
                    URL pública

                    <input
                        name="slug"
                        value="{{ old('slug', $perfil->slug) }}"
                        required
                    >
                </label>

                <label class="full">
                    Título principal

                    <input
                        data-preview="title"
                        name="titulo_portada"
                        value="{{ old('titulo_portada', $perfil->titulo_portada) }}"
                        placeholder="Tu próximo vehículo empieza acá"
                    >
                </label>

                <label class="full">
                    Subtítulo

                    <textarea
                        data-preview="subtitle"
                        name="subtitulo_portada"
                    >{{ old('subtitulo_portada', $perfil->subtitulo_portada) }}</textarea>
                </label>

            </div>

        </div>


        {{-- ========================================================
             03 · DIRECCIÓN VISUAL
        ========================================================= --}}
        <div class="editor-section">

            <span class="editor-kicker">
                03 · Dirección visual
            </span>

            <h2>Elegí tu atmósfera</h2>

            <p class="editor-help">
                Tres sistemas visuales pensados para conservar contraste
                y legibilidad en cualquier pantalla.
            </p>

            <div class="form-grid compact-grid">
                <label>
                    Plantilla

                    <select
                        data-preview="template"
                        name="plantilla"
                    >

                        <option
                            value="editorial"
                            @selected(
                                old('plantilla', $perfil->plantilla) === 'editorial' ||
                                !in_array(
                                    old('plantilla', $perfil->plantilla),
                                    ['editorial', 'alto-contraste', 'calma'],
                                    true
                                )
                            )
                        >
                            Editorial
                        </option>

                        <option
                            value="alto-contraste"
                            @selected(
                                old('plantilla', $perfil->plantilla) === 'alto-contraste'
                            )
                        >
                            Alto contraste
                        </option>

                        <option
                            value="calma"
                            @selected(
                                old('plantilla', $perfil->plantilla) === 'calma'
                            )
                        >
                            Calma
                        </option>

                    </select>

                </label>

                <label>
                    Estilo del hero

                    <select
                        data-preview="heroStyle"
                        name="hero_estilo"
                    >
                        <option value="showcase" @selected(old('hero_estilo', $perfil->hero_estilo) === 'showcase')>
                            Showcase
                        </option>
                        <option value="spotlight" @selected(old('hero_estilo', $perfil->hero_estilo) === 'spotlight')>
                            Spotlight
                        </option>
                        <option value="gallery" @selected(old('hero_estilo', $perfil->hero_estilo) === 'gallery')>
                            Gallery
                        </option>
                    </select>
                </label>
            </div>

            <div class="color-pair">

                <label>
                    Color principal

                    <input
                        data-preview="primary"
                        type="color"
                        name="color_principal"
                        value="{{ old('color_principal', $perfil->color_principal ?: '#e85d04') }}"
                    >
                </label>

                <label>
                    Color secundario

                    <input
                        data-preview="secondary"
                        type="color"
                        name="color_secundario"
                        value="{{ old('color_secundario', $perfil->color_secundario ?: '#f3aa3c') }}"
                    >
                </label>

            </div>

            <label class="full upload-field">
                Imagen de portada

                <input
                    data-preview="cover"
                    type="file"
                    name="imagen_portada"
                    accept="image/jpeg,image/png,image/webp"
                >

                <small>
                    JPG, PNG o WEBP · máximo 5 MB
                </small>
            </label>

        </div>


        {{-- ========================================================
             04 · CONTENIDO Y CONTACTO
        ========================================================= --}}
        <div class="editor-section">

            <span class="editor-kicker">
                04 · Contenido y contacto
            </span>

            <h2>Que te encuentren</h2>

            <div class="form-grid">

                <label>
                    Teléfono

                    <input
                        name="telefono"
                        value="{{ old('telefono', $perfil->telefono) }}"
                    >
                </label>

                <label>
                    WhatsApp

                    <input
                        name="whatsapp"
                        value="{{ old('whatsapp', $perfil->whatsapp) }}"
                    >
                </label>

                <label>
                    Instagram

                    <input
                        name="instagram"
                        value="{{ old('instagram', $perfil->instagram) }}"
                    >
                </label>

                <label>
                    Facebook

                    <input
                        name="facebook"
                        value="{{ old('facebook', $perfil->facebook) }}"
                    >
                </label>

                <label class="full">
                    Dirección

                    <input
                        name="direccion"
                        value="{{ old('direccion', $perfil->direccion) }}"
                    >
                </label>

                <label class="full">
                    Descripción

                    <textarea
                        name="descripcion"
                        rows="5"
                    >{{ old('descripcion', $perfil->descripcion) }}</textarea>
                </label>

            </div>

        </div>


        {{-- ========================================================
             05 · MARCA PROPIA
        ========================================================= --}}
        <div class="editor-section editor-logo-section">

            <span class="editor-kicker">
                05 · Marca propia
            </span>

            <h2>Tu logo, tu identidad</h2>

            <p class="editor-help">
                Rodante solo aparecerá como firma de plataforma
                en el pie de página.
            </p>

            <label class="full upload-field">

                Logo de tu agencia

                <input
                    data-preview="logo"
                    type="file"
                    name="logo"
                    accept="image/jpeg,image/png,image/webp"
                >

                <small>
                    Opcional · JPG, PNG o WEBP · máximo 2 MB
                </small>

            </label>

        </div>


        {{-- ========================================================
             ERRORES
        ========================================================= --}}
        @if ($errors->any())

            <div class="flash-error">
                Revisá los campos indicados antes de guardar.
            </div>

        @endif


        {{-- ========================================================
             GUARDAR
        ========================================================= --}}
        <button
            class="button button-orange editor-save"
            type="submit"
        >
            Guardar diseño

            <span>↗</span>
        </button>

    </form>


    {{-- ============================================================
         PREVIEW
    ============================================================= --}}
    <aside class="landing-preview-shell">

        <div class="preview-toolbar">

            <span>
                <i></i>
                Preview en vivo
            </span>

            <small>
                Así verá tu página el público
            </small>

        </div>


        <div
            class="landing-preview"
            id="landing-preview"
        >

            {{-- NAV --}}
            <div class="preview-nav">

                <strong id="preview-agency">
                    {{ $perfil->nombre_negocio }}
                </strong>

                <span>
                    Inventario&nbsp;&nbsp;&nbsp; Contacto
                </span>

            </div>


            {{-- HERO --}}
            <div
                class="preview-hero"
                data-preview-block="hero"
            >

                <div class="preview-copy">

                    <small>
                        VENDEDOR VERIFICADO
                    </small>

                    <h3 id="preview-title">
                        {{ $perfil->titulo_portada ?: $perfil->nombre_negocio }}
                    </h3>

                    <p id="preview-subtitle">
                        {{ $perfil->subtitulo_portada ?: 'Vehículos seleccionados para acompañar tu próximo destino.' }}
                    </p>

                    <button type="button">
                        Ver inventario
                        <b>↓</b>
                    </button>

                </div>


                <div
                    class="preview-cover"
                    @if ($perfil->imagen_portada)
                        data-cover-image="{{ asset('storage/' . $perfil->imagen_portada) }}"
                    @endif
                ></div>

            </div>


            {{-- INTRO --}}
            <div
                class="preview-intro"
                data-preview-block="intro"
            >

                <small>
                    SOBRE NOSOTROS
                </small>

                <h4 id="preview-intro">
                    {{ $perfil->nombre_negocio }}
                </h4>

                <p>
                    Atención personalizada y vehículos seleccionados.
                </p>

            </div>


            {{-- INVENTARIO --}}
            <div
                class="preview-inventory"
                data-preview-block="inventario"
            >

                <small>
                    EN STOCK
                </small>

                <h4>
                    Vehículos disponibles
                </h4>

                <div class="preview-cards">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

            </div>


            {{-- CONTACTO --}}
            <div
                class="preview-contact"
                data-preview-block="contacto"
            >
                Contacto · WhatsApp · Redes sociales
            </div>


            {{-- FOOTER --}}
            <div
                class="preview-footer"
                data-preview-block="footer"
            >
                RODANTE / {{ $perfil->nombre_negocio }}
            </div>

        </div>

    </aside>

</div>

@endsection
