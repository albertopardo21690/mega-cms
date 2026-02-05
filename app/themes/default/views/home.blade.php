<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
</head>
<body>
  <h1>{{ $title }}</h1>

  <p><strong>Tenant:</strong> {{ $site->subdomain }}</p>
  <p><strong>Site ID:</strong> {{ $site->id }}</p>
  <p><strong>Tema:</strong> {{ $site->theme }}</p>
  <p><strong>Módulos:</strong> {{ implode(', ', $site->modules ?? []) }}</p>
</body>
</html>
