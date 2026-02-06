<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Editar menú</title>
<style>
body{font-family:system-ui,Arial;margin:24px}
a{color:#111}
.card{border:1px solid #eee;border-radius:12px;padding:14px;max-width:1100px}
.grid{display:grid;grid-template-columns: 60px 220px 260px 120px 140px 200px 100px 120px;gap:8px;align-items:center}
.h{font-weight:700;opacity:.7;font-size:13px}
input,select{width:100%;padding:8px;border:1px solid #ddd;border-radius:10px}
.btn{border:1px solid #ddd;border-radius:10px;padding:8px 10px;background:#fff;cursor:pointer}
.small{font-size:12px;opacity:.7}
hr{border:0;border-top:1px solid #eee;margin:14px 0}
</style>
</head>
<body>
<p><a href="/admin/menus">← Volver</a></p>
<h1>Editar menú: {{ strtoupper($location) }}</h1>

<div class="card">
  <form method="post" action="/admin/menus/{{ $location }}/add" style="display:flex;gap:8px;flex-wrap:wrap;align-items:end;">
    @csrf
    <div style="flex:1;min-width:180px">
      <div class="small">Label</div>
      <input name="label" placeholder="Ej: Contacto" required>
    </div>
    <div style="flex:2;min-width:260px">
      <div class="small">URL</div>
      <input name="url" placeholder="/contacto o https://..." required>
    </div>
    <div style="min-width:160px">
      <div class="small">Padre</div>
      <select name="parent_id">
        <option value="">— (raíz)</option>
        @foreach($parents as $p)
          <option value="{{ $p->id }}">{{ $p->label }}</option>
        @endforeach
      </select>
    </div>
    <button class="btn" type="submit">Añadir</button>
  </form>

  <hr>

  <form method="post" action="/admin/menus/{{ $location }}/save">
    @csrf

    <div class="grid h">
      <div>ID</div><div>Label</div><div>URL</div><div>Orden</div><div>Padre</div><div>Opciones</div><div>Visible</div><div></div>
    </div>

    <hr>

    @foreach($items as $it)
      <div class="grid" style="margin-bottom:8px">
        <div>{{ $it->id }}</div>

        <div>
          <input name="items[{{ $it->id }}][label]" value="{{ $it->label }}">
          <div class="small">Icono</div>
          <input name="items[{{ $it->id }}][icon]" value="{{ $it->icon ?? '' }}" placeholder="✨">
        </div>

        <div>
          <input name="items[{{ $it->id }}][url]" value="{{ $it->url }}">
          <div class="small">Title (tooltip)</div>
          <input name="items[{{ $it->id }}][title]" value="{{ $it->title ?? '' }}">
        </div>

        <div>
          <input type="number" name="items[{{ $it->id }}][sort]" value="{{ $it->sort }}" step="1">
        </div>

        <div>
          <select name="items[{{ $it->id }}][parent_id]">
            <option value="">— raíz</option>
            @foreach($parents as $p)
              <option value="{{ $p->id }}" @if($it->parent_id == $p->id) selected @endif>
                {{ $p->label }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <div class="small">Target</div>
          <select name="items[{{ $it->id }}][target]">
            <option value="_self" @if(($it->target ?? '_self') === '_self') selected @endif>_self</option>
            <option value="_blank" @if(($it->target ?? '_self') === '_blank') selected @endif>_blank</option>
          </select>

          <div class="small">Rel</div>
          <input name="items[{{ $it->id }}][rel]" value="{{ $it->rel ?? '' }}" placeholder="nofollow noopener">
          <div class="small">CSS class</div>
          <input name="items[{{ $it->id }}][css_class]" value="{{ $it->css_class ?? '' }}" placeholder="btn btn-primary">
        </div>

        <div>
          <select name="items[{{ $it->id }}][is_visible]">
            <option value="1" @if($it->is_visible) selected @endif>Sí</option>
            <option value="0" @if(!$it->is_visible) selected @endif>No</option>
          </select>
        </div>

        <div>
          <button class="btn" type="submit" formaction="/admin/menus/{{ $location }}/delete" name="id" value="{{ $it->id }}">
            Eliminar
          </button>
        </div>
      </div>
    @endforeach

    <hr>

    <button class="btn" type="submit">Guardar cambios</button>
  </form>
</div>
</body>
</html>
