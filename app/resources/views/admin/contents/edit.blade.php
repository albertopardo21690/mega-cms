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

    @if($type === 'post')
      <hr>
      <h2>Taxonomías</h2>

      <h3>Categorías</h3>
      @php $cats = $tax?->terms ?? collect(); @endphp
      @foreach($cats as $t)
        <label style="display:block;">
          <input type="checkbox" name="categories[]" value="{{ $t->id }}"
            @checked(in_array($t->id, $selectedCategoryIds ?? []))>
          {{ $t->name }}
        </label>
      @endforeach
      <p><a href="/admin/taxonomies/category">Gestionar categorías</a></p>

      <h3>Etiquetas</h3>
      @php $tagsAll = $tag?->terms ?? collect(); @endphp
      @foreach($tagsAll as $t)
        <label style="display:block;">
          <input type="checkbox" name="tags[]" value="{{ $t->id }}"
            @checked(in_array($t->id, $selectedTagIds ?? []))>
          {{ $t->name }}
        </label>
      @endforeach
      <p><a href="/admin/taxonomies/tag">Gestionar etiquetas</a></p>
    @endif


    <hr>
    <h2>Meta (tipo wp_postmeta)</h2>
    <p>Añade pares <code>meta_key</code> / <code>meta_value</code>. Ej: <code>seo_title</code>, <code>seo_description</code>, <code>featured_image</code>...</p>

    <table border="1" cellpadding="6" cellspacing="0">
      <thead>
        <tr>
          <th>Key</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        @php
          $rows = [];
          foreach(($meta ?? []) as $k => $v) $rows[] = ['k'=>$k,'v'=>$v];
          // 3 filas extra vacías para añadir rápido
          for($i=0;$i<3;$i++) $rows[] = ['k'=>'','v'=>''];
        @endphp

        @foreach($rows as $r)
          <tr>
            <td><input name="meta_key[]" value="{{ $r['k'] }}" style="width:260px;"></td>
            <td><input name="meta_value[]" value="{{ $r['v'] }}" style="width:520px;"></td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <hr>

    <button type="submit">Guardar</button>
  </form>
</body>
</html>
