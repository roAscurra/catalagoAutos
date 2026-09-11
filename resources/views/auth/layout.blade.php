<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Acceso' }} | Rodante</title>@vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="auth-body"><a class="brand auth-brand" href="{{ route('catalogo') }}"><span>R</span> RODANTE</a>
    <main class="auth-card">@yield('content')</main>
</body>

</html>