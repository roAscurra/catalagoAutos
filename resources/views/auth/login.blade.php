@extends('auth.layout')
@section('content')<p class="eyebrow">Bienvenido de nuevo</p>
<h1>Entrá a tu espacio.</h1>
<p class="auth-intro">Gestioná tu inventario y mantené tu página siempre en movimiento.</p>
<form class="auth-form" method="POST" action="{{ route('login.store') }}">@csrf<label>Email<input type="email"
            name="email" value="{{ old('email') }}" required autofocus></label><label>Contraseña<input type="password"
            name="password" required></label>@error('email')<small
        class="form-error">{{ $message }}</small>@enderror<button class="button button-orange" type="submit">Ingresar
        <span>→</span></button></form>
<p class="auth-footer">¿Todavía no tenés cuenta? <a href="{{ route('register') }}">Registrate</a></p>@endsection