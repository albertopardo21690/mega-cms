<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - Taxonomías</title></head>
<body>
<h1>Taxonomías ({{ app('currentSite')->subdomain }})</h1>
<p><a href="{{ route('admin.dashboard') }}">← Dashboard</a></p>

<ul>
@foreach($items as $t)
  <li>
    <a href="{{ route('admin.taxonomies.terms', [$t->taxonomy_key]) }}">
      {{ $t->label }} ({{ $t->taxonomy_key }})
    </a>
  </li>
@endforeach
</ul>
</body>
</html>
