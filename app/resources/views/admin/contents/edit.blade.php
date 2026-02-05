<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Editar {{ strtoupper($type) }}</title>
</head>
<body>
  <h1>{{ $item->exists ? 'Editar' : 'Crear' }} {{ strtoupper($type) }}</h1>

  @if(session('ok'))
    <p><strong>{{ session('ok') }}</strong></p>
  @endif

  @if ($errors->any())
    <div>
      <strong>Errores:</strong>
      <ul>
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <p>
    <a href="{{ route('admin.contents.index', [$type]) }}">← Volver</a>
  </p>

  <form method="post" action="{{ $item->exists ? route('admin.contents.update', [$type, $item->id]) : route('admin.contents.store', [$type]) }}">
    @csrf

    <p>
      <label>Título</label><br>
      <input name="title" value="{{ old('title', $item->title) }}" style="width:520px;">
    </p>

    <p>
      <label>Slug (opcional)</label><br>
      <input name="slug" value="{{ old('slug', $item->slug) }}" style="width:520px;">
    </p>

    <p>
      <label>Estado</label><br>
      <select name="status">
        @foreach(['draft'=>'draft','published'=>'published','trash'=>'trash'] as $k => $v)
          <option value="{{ $k }}" @selected(old('status', $item->status) === $k)>{{ $v }}</option>
        @endforeach
      </select>
    </p>

    <p>
      <label>Extracto</label><br>
      <textarea name="excerpt" rows="3" style="width:720px;">{{ old('excerpt', $item->excerpt) }}</textarea>
    </p>

    <p>
      <label>Contenido</label><br>
      <textarea name="content" rows="14" style="width:720px;">{{ old('content', $item->content) }}</textarea>
    </p>

    <button type="submit">Guardar</button>
  </form>
</body>
</html>
