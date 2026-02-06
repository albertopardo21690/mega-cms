<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? $site->name }}</title>

    <link rel="stylesheet" href="{{ app('themePath') }}/assets/css/theme.css">
</head>
<body>

@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')

<script src="{{ app('themePath') }}/assets/js/theme.js"></script>
</body>
</html>
