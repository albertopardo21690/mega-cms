@php
  /** @var array $items */
  $level = $level ?? 0;
@endphp

<ul class="menu menu-level-{{ $level }}" data-menu-level="{{ $level }}">
  @foreach($items as $item)
    @php
      $hasChildren = !empty($item['children']);
      $submenuId = 'submenu-' . $level . '-' . $item['id'];
    @endphp

    <li class="menu-item {{ $hasChildren ? 'has-children' : '' }}">
      <div class="menu-row">
        <a class="menu-link" href="{{ $item['url'] }}">
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
