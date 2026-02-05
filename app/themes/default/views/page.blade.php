<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $meta['seo_title'] ?? $title }}</title>
  <meta name="description" content="{{ $meta['seo_description'] ?? '' }}">
</head>
<body>
  <p><a href="/">← Inicio</a></p>
  <h1>{{ $title }}</h1>
  <div>{!! $html !!}</div>
</body>
</html>
