@extends('auth.layout')

@php
    $authWidth = 'auth-card-register';
@endphp

@section('content') <p class="eyebrow">Comenzá a vender</p>

<h1>
    Tu negocio<br>
    <em>tiene un lugar.</em>
</h1>

<p class="auth-intro">
    Creá tu página personalizada y publicá tu inventario en Rodante.
</p>

<form class="auth-form" method="POST" action="{{ route('register.store') }}">

    @csrf

    <div class="form-grid form-grid-register">

        {{-- TIPO DE CUENTA --}}
        <label>
            Tipo de cuenta

            <select name="rol" class="{{ $errors->has('rol') ? 'input-error' : '' }}" required>
                <option value="agencia" @selected(old('rol', 'individual') === 'agencia')>
                    Agencia / concesionaria
                </option>

                <option value="individual" @selected(old('rol') === 'individual')>
                    Vendedor individual
                </option>
            </select>

            @error('rol')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>


        {{-- NOMBRE --}}
        <label>
            Tu nombre

            <input
                name="name"
                value="{{ old('name') }}"
                class="{{ $errors->has('name') ? 'input-error' : '' }}"
                required
            >

            @error('name')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>


        {{-- EMAIL --}}
        <label>
            Email

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="{{ $errors->has('email') ? 'input-error' : '' }}"
                required
            >

            @error('email')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>


        {{-- NEGOCIO --}}
        <label>
            Nombre del negocio

            <input
                name="nombre_negocio"
                value="{{ old('nombre_negocio') }}"
                class="{{ $errors->has('nombre_negocio') ? 'input-error' : '' }}"
                required
            >

            @error('nombre_negocio')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>


        {{-- SLUG --}}
        <label>
            URL de tu página

            <input
                name="slug"
                value="{{ old('slug') }}"
                placeholder="mi-concesionaria"
                class="{{ $errors->has('slug') ? 'input-error' : '' }}"
            >

            <small>Solo es pública para cuentas de agencia.</small>

            @error('slug')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>


        {{-- PLAN --}}
        <label>
            Plan inicial

            <select name="plan_id" class="{{ $errors->has('plan_id') ? 'input-error' : '' }}">
                <option value="">
                    Elegir más adelante
                </option>

                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>
                        {{ $plan->name }}
                        · ${{ number_format($plan->monthly_price, 0, ',', '.') }}/mes
                    </option>
                @endforeach
            </select>

            @error('plan_id')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>

    </div>

    {{-- CONTRASEÑAS --}}
    <div class="form-grid">

        <label>
            Contraseña

            <div class="password-field">
                <input
                    type="password"
                    name="password"
                    class="{{ $errors->has('password') ? 'input-error' : '' }}"
                    required
                >

                <button type="button" class="password-toggle" aria-label="Mostrar contraseña">
                    <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z" />
                        <circle cx="12" cy="12" r="2.5" />
                    </svg>
                </button>
            </div>

            @error('password')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>


        <label>
            Repetir contraseña

            <div class="password-field">
                <input
                    type="password"
                    name="password_confirmation"
                    class="{{ $errors->has('password_confirmation') ? 'input-error' : '' }}"
                    required
                >

                <button type="button" class="password-toggle" aria-label="Mostrar contraseña">
                    <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z" />
                        <circle cx="12" cy="12" r="2.5" />
                    </svg>
                </button>
            </div>

            @error('password_confirmation')
                <small class="form-error">{{ $message }}</small>
            @enderror
        </label>

    </div>

    <button class="button button-orange" type="submit">
        Crear mi cuenta
        <span>↗</span>
    </button>

</form>


<p class="auth-footer">
    ¿Ya tenés cuenta?
    <a href="{{ route('login') }}">Ingresá</a>
</p>


@endsection
