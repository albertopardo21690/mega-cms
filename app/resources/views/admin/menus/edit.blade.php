<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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

    /* Drag & Drop */
    .dd-root { border:1px dashed #ddd; border-radius:12px; padding:10px; background:#fff; }
    .dd-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:6px; }
    .dd-item { border:1px solid #eee; border-radius:12px; padding:8px; background:#fff; }
    .dd-row { display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .dd-handle { cursor: grab; user-select:none; padding:6px 10px; border:1px solid #ddd; border-radius:10px; background:#fff; }
    .dd-label { font-weight:600; }
    .dd-dropzone { height:10px; border-radius:8px; border:1px dashed transparent; margin-top:6px; }
    .dd-dropzone.active { border-color:#999; }
    .dd-children { margin-top:6px; padding-left:18px; }
    .dd-pill { font-size:12px; opacity:.7; border:1px solid #eee; border-radius:999px; padding:2px 8px; }
  </style>
</head>
<body>
@php
  // Construimos JSON de forma ultra-robusta (sin [] en PHP).
  $ddArr = array();
  foreach ($items as $i) {
    $ddArr[] = array(
      'id' => (int) $i->id,
      'label' => (string) $i->label,
      'parent_id' => $i->parent_id ? (int) $i->parent_id : null,
    );
  }
  $ddItemsJson = json_encode($ddArr, JSON_UNESCAPED_UNICODE);
@endphp

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

  <h2 style="margin:0 0 10px 0;">Organizar (Drag & Drop)</h2>
  <div class="small" style="margin-bottom:10px;">
    Arrastra para reordenar. Arrastra sobre un item para hacerlo hijo. Luego pulsa “Guardar orden”.
  </div>

  <div id="ddRoot" class="dd-root"></div>

  <div style="display:flex;gap:10px;align-items:center;margin:12px 0 4px 0;">
    <button class="btn" type="button" id="ddSaveBtn">Guardar orden (AJAX)</button>
    <span class="small" id="ddStatus"></span>
  </div>

  <script>
    window.__MENU_LOCATION__ = "{{ $location }}";
    window.__MENU_ITEMS__ = {!! $ddItemsJson !!};
  </script>

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

<script>
(function(){
  var items = window.__MENU_ITEMS__ || [];
  var locationKey = window.__MENU_LOCATION__ || 'header';

  function buildTree(flat) {
    var byId = {};
    flat.forEach(function(n){ byId[n.id] = {id:n.id, label:n.label, parent_id:n.parent_id||null, children:[]}; });
    var roots = [];
    Object.keys(byId).forEach(function(k){
      var n = byId[k];
      if (n.parent_id && byId[n.parent_id]) byId[n.parent_id].children.push(n);
      else roots.push(n);
    });
    return roots;
  }

  function el(tag, attrs, children) {
    var e = document.createElement(tag);
    if (attrs) Object.keys(attrs).forEach(function(k){
      if (k === 'class') e.className = attrs[k];
      else if (k.indexOf('data-') === 0) e.setAttribute(k, attrs[k]);
      else e[k] = attrs[k];
    });
    (children||[]).forEach(function(c){
      if (typeof c === 'string') e.appendChild(document.createTextNode(c));
      else if (c) e.appendChild(c);
    });
    return e;
  }

  function renderNode(node) {
    var li = el('li', {class:'dd-item', 'data-id': String(node.id)}, [
      el('div', {class:'dd-row'}, [
        el('div', {style:'display:flex;gap:10px;align-items:center;'}, [
          el('span', {class:'dd-handle', draggable:true, 'data-drag-id': String(node.id)}, ['↕']),
          el('span', {class:'dd-label'}, [node.label]),
          el('span', {class:'dd-pill'}, ['ID ' + node.id])
        ]),
        el('span', {class:'small'}, [node.children.length ? ('Hijos: '+node.children.length) : ''])
      ]),
      el('div', {class:'dd-dropzone', 'data-zone':'before', 'data-id': String(node.id)}, []),
      el('div', {class:'dd-dropzone', 'data-zone':'inside', 'data-id': String(node.id)}, []),
      el('div', {class:'dd-children'}, [
        node.children.length ? renderList(node.children) : el('ul', {class:'dd-list'}, [])
      ])
    ]);
    return li;
  }

  function renderList(nodes) {
    return el('ul', {class:'dd-list'}, nodes.map(renderNode));
  }

  function mount() {
    var root = document.getElementById('ddRoot');
    if (!root) return;

    var tree = buildTree(items);
    root.innerHTML = '';
    root.appendChild(renderList(tree));

    var dragId = null;

    root.addEventListener('dragstart', function(e){
      var t = e.target;
      if (!t || !t.classList.contains('dd-handle')) return;
      dragId = t.getAttribute('data-drag-id');
      e.dataTransfer.setData('text/plain', dragId);
      e.dataTransfer.effectAllowed = 'move';
    });

    root.addEventListener('dragend', function(){
      dragId = null;
      root.querySelectorAll('.dd-dropzone.active').forEach(function(z){ z.classList.remove('active'); });
    });

    root.addEventListener('dragover', function(e){
      var z = e.target;
      if (!z || !z.classList.contains('dd-dropzone')) return;
      e.preventDefault();
      z.classList.add('active');
      e.dataTransfer.dropEffect = 'move';
    });

    root.addEventListener('dragleave', function(e){
      var z = e.target;
      if (!z || !z.classList.contains('dd-dropzone')) return;
      z.classList.remove('active');
    });

    root.addEventListener('drop', function(e){
      var z = e.target;
      if (!z || !z.classList.contains('dd-dropzone')) return;
      e.preventDefault();
      z.classList.remove('active');

      var fromId = parseInt(e.dataTransfer.getData('text/plain') || '0', 10);
      var toId = parseInt(z.getAttribute('data-id') || '0', 10);
      var zone = z.getAttribute('data-zone');

      if (!fromId || !toId || fromId === toId) return;

      var from = items.find(function(n){ return n.id === fromId; });
      var to = items.find(function(n){ return n.id === toId; });
      if (!from || !to) return;

      if (zone === 'inside') from.parent_id = toId;
      else from.parent_id = to.parent_id || null;

      mount();
    });
  }

  function extractTreeFromDom() {
    var root = document.getElementById('ddRoot');
    var ul = root ? root.querySelector('ul.dd-list') : null;
    if (!ul) return [];

    function walkUl(ulEl) {
      var out = [];
      Array.from(ulEl.children).forEach(function(li){
        if (!li.classList.contains('dd-item')) return;
        var id = parseInt(li.getAttribute('data-id')||'0',10);
        var childUl = li.querySelector(':scope > .dd-children > ul.dd-list');
        out.push({ id: id, children: childUl ? walkUl(childUl) : [] });
      });
      return out;
    }
    return walkUl(ul);
  }

  async function saveAjax() {
    var status = document.getElementById('ddStatus');
    try {
      var tree = extractTreeFromDom();
      status.textContent = 'Guardando...';

      var tokenEl = document.querySelector('input[name=_token]');
      var token = tokenEl ? tokenEl.value : '';

      var res = await fetch('/admin/menus/' + locationKey + '/save-json', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ tree: tree })
      });

      var data = await res.json().catch(function(){ return null; });
      if (!res.ok || !data || !data.ok) {
        status.textContent = 'Error al guardar';
        return;
      }

      status.textContent = 'Guardado ✅ (recarga para ver reflejado en el formulario)';
    } catch (err) {
      status.textContent = 'Error JS: ' + (err && err.message ? err.message : 'unknown');
    }
  }

  document.addEventListener('DOMContentLoaded', function(){
    mount();
    var btn = document.getElementById('ddSaveBtn');
    if (btn) btn.addEventListener('click', saveAjax);
  });
})();
</script>

</body>
</html>
