<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? ($site->name ?? 'Inicio') }}</title>
</head>
<body>
  @extends('layouts.app')

    @section('content')
    <h1>{{ $site->name }}</h1>
    <p>Bienvenido a {{ $site->name }}</p>
    @endsection
</body>
</html>
