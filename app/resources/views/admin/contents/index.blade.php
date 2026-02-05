<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - {{ strtoupper($type) }}</title>
</head>
<body>
  <h1>{{ strtoupper($type) }} (Tenant: {{ app('currentSite')->subdomain }})</h1>

  @if(session('ok'))
    <p><strong>{{ session('ok') }}</strong></p>
  @endif

  <p>
    <a href="{{ route('admin.dashboard') }}">← Dashboard</a>
    | <a href="{{ route('admin.contents.create', [$type]) }}">+ Crear</a>
  </p>

  <form method="get" action="">
    <input name="s" value="{{ $search ?? '' }}" placeholder="Buscar por título o slug">
    <button type="submit">Buscar</button>
  </form>

  <hr>

  <table border="1" cellpadding="6" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Slug</th>
        <th>Estado</th>
        <th>Actualizado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $it)
        <tr>
          <td>{{ $it->id }}</td>
          <td>{{ $it->title }}</td>
          <td>{{ $it->slug }}</td>
          <td>{{ $it->status }}</td>
          <td>{{ $it->updated_at }}</td>
          <td>
            <a href="{{ route('admin.contents.edit', [$type, $it->id]) }}">Editar</a>
            <form method="post" action="{{ route('admin.contents.delete', [$type, $it->id]) }}" style="display:inline">
              @csrf
              <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top:12px;">
    {{ $items->links() }}
  </div>
</body>
</html>
