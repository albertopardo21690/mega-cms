@php
  /** @var array $items */
  $level = $level ?? 0;

  $currentPath = '/'.ltrim(request()->path(), '/');
  if ($currentPath === '//') $currentPath = '/';

  // Normaliza URL para comparar
  $norm = function (?string $url) {
      $u = trim((string)$url);
      if ($u === '') return '/';
      if ($u[0] !== '/' && !str_starts_with($u, 'http://') && !str_starts_with($u, 'https://')) {
          $u = '/'.$u;
      }
      // quita query/hash
      $u = preg_replace('/[?#].*$/', '', $u);
      return $u ?: '/';
  };

  // Detecta si URL es externa (http(s) y distinto host)
  $isExternal = function (?string $url) {
      $u = trim((string)$url);
      if (!str_starts_with($u, 'http://') && !str_starts_with($u, 'https://')) return false;
      $host = parse_url($u, PHP_URL_HOST);
      return $host && $host !== request()->getHost();
  };

  // Marca activo si coincide o si estamos dentro (ej: /blog y estás en /blog/post)
  $isActiveUrl = function (string $url) use ($currentPath, $norm) {
      $u = $norm($url);
      if ($u === '#') return false;
      if ($u === '/') return $currentPath === '/';
      return $currentPath === $u || str_starts_with($currentPath.'/', rtrim($u,'/').'/');
  };

  // Si un item tiene muchos hijos, lo tratamos como “mega menu” (desktop)
  $isMega = function (array $item) {
      return !empty($item['children']) && count($item['children']) >= 6;
  };
@endphp

<ul class="menu menu-level-{{ $level }}" data-menu-level="{{ $level }}">
  @foreach($items as $item)
    @php
      $hasChildren = !empty($item['children']);
      $submenuId = 'submenu-' . $level . '-' . $item['id'];

      $url = (string)($item['url'] ?? '/');
      $activeSelf = $isActiveUrl($url);

      // “Active” también si cualquier hijo está activo (para resaltar el padre)
      $activeChild = false;
      if ($hasChildren) {
        foreach ($item['children'] as $ch) {
          if ($isActiveUrl((string)($ch['url'] ?? ''))) { $activeChild = true; break; }
        }
      }
      $isActive = $activeSelf || $activeChild;

      $external = $isExternal($url);

      $classes = [
        'menu-item',
        $hasChildren ? 'menu-item--has-children' : '',
        $isActive ? 'menu-item--active' : '',
        ($hasChildren && $isMega($item) && $level === 0) ? 'menu-item--mega' : '',
      ];
      $classes = trim(implode(' ', array_filter($classes)));
    @endphp

    <li class="{{ $classes }}">
      <div class="menu-row">
        <a
          class="menu-link"
          href="{{ $url }}"
          @if($external) target="_blank" rel="noopener noreferrer" @endif
          aria-current="{{ $activeSelf ? 'page' : 'false' }}"
        >
          {{ $item['label'] }}
        </a>

        @if($hasChildren)
          <button
            class="submenu-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="{{ $submenuId }}"
            data-submenu-toggle="{{ $submenuId }}"
            title="Abrir submenú"
          >▾</button>
        @endif
      </div>

      @if($hasChildren)
        <div class="submenu" id="{{ $submenuId }}" hidden>
          @include('partials.menu-tree', ['items' => $item['children'], 'level' => $level + 1])
        </div>
      @endif
    </li>
  @endforeach
</ul>
