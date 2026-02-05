<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Settings</title>
</head>
<body>
  <h1>Settings (Tenant: {{ app('currentSite')->subdomain }})</h1>

  <p>
    <a href="{{ route('admin.dashboard') }}">← Dashboard</a>
  </p>

  @if(session('ok'))
    <p><strong>{{ session('ok') }}</strong></p>
  @endif

  <form method="post" action="{{ route('admin.settings.flush') }}">
    @csrf
    <button type="submit">Vaciar caché autoload</button>
  </form>

  <hr>

  <h2>Nuevo / Editar</h2>
  <form method="post" action="{{ route('admin.settings.save') }}">
    @csrf
    <p>
      <label>Key</label><br>
      <input name="key" style="width:420px;" placeholder="site_tagline">
    </p>
    <p>
      <label>Value</label><br>
      <textarea name="value" rows="3" style="width:720px;" placeholder="Texto..."></textarea>
    </p>
    <p>
      <label>
        <input type="checkbox" name="autoload" value="1">
        Autoload (cargar y cachear en cada request)
      </label>
    </p>
    <button type="submit">Guardar</button>
  </form>

  <hr>

  <h2>Listado</h2>
  <table border="1" cellpadding="6" cellspacing="0">
    <thead>
      <tr>
        <th>Key</th>
        <th>Value</th>
        <th>Autoload</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $it)
        <tr>
          <td><code>{{ $it->key }}</code></td>
          <td style="max-width:520px; white-space:pre-wrap;">{{ $it->value }}</td>
          <td>{{ $it->autoload ? '1' : '0' }}</td>
          <td>
            <form method="post" action="{{ route('admin.settings.delete') }}" style="display:inline">
              @csrf
              <input type="hidden" name="key" value="{{ $it->key }}">
              <button type="submit" onclick="return confirm('¿Eliminar setting?')">Eliminar</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
