<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - {{ $taxonomy->label }}</title></head>
<body>
<h1>{{ $taxonomy->label }} ({{ app('currentSite')->subdomain }})</h1>
<p>
  <a href="{{ route('admin.taxonomies') }}">← Taxonomías</a>
  | <a href="{{ route('admin.dashboard') }}">Dashboard</a>
</p>

@if(session('ok')) <p><strong>{{ session('ok') }}</strong></p> @endif

<h2>Crear</h2>
<form method="post" action="{{ route('admin.taxonomies.terms.save', [$taxonomy->taxonomy_key]) }}">
  @csrf
  <p><input name="name" placeholder="Nombre" style="width:320px;"></p>
  <p><input name="slug" placeholder="slug (opcional)" style="width:320px;"></p>
  <button type="submit">Crear</button>
</form>

<hr>

<h2>Listado</h2>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr><th>ID</th><th>Nombre</th><th>Slug</th><th>Acciones</th></tr></thead>
  <tbody>
    @foreach($terms as $tr)
      <tr>
        <td>{{ $tr->id }}</td>
        <td>{{ $tr->name }}</td>
        <td>{{ $tr->slug }}</td>
        <td>
          <form method="post" action="{{ route('admin.taxonomies.terms.delete', [$taxonomy->taxonomy_key]) }}" style="display:inline">
            @csrf
            <input type="hidden" name="term_id" value="{{ $tr->id }}">
            <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
</body>
</html>
