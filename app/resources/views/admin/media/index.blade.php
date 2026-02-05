<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - Media</title></head>
<body>
<h1>Media ({{ app('currentSite')->subdomain }})</h1>
<p><a href="{{ route('admin.dashboard') }}">← Dashboard</a></p>

@if(session('ok')) <p><strong>{{ session('ok') }}</strong></p> @endif

<h2>Subir archivo</h2>
<form method="post" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data">
  @csrf
  <input type="file" name="file" required>
  <button type="submit">Subir</button>
</form>

<hr>

<h2>Biblioteca</h2>
<table border="1" cellpadding="6" cellspacing="0">
  <thead><tr><th>ID</th><th>Preview</th><th>Nombre</th><th>MIME</th><th>Tamaño</th><th>URL</th><th>Acciones</th></tr></thead>
  <tbody>
    @foreach($items as $m)
      <tr>
        <td>{{ $m->id }}</td>
        <td>
          @if(str_starts_with($m->mime ?? '', 'image/'))
            <img src="{{ $m->url() }}" style="max-width:80px; max-height:80px;">
          @endif
        </td>
        <td>{{ $m->filename }}</td>
        <td>{{ $m->mime }}</td>
        <td>{{ $m->size }}</td>
        <td><a href="{{ $m->url() }}" target="_blank">Abrir</a></td>
        <td>
          <form method="post" action="{{ route('admin.media.delete') }}" style="display:inline">
            @csrf
            <input type="hidden" name="id" value="{{ $m->id }}">
            <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{ $items->links() }}
</body>
</html>
