@php
  /** @var array $items */
  $level = $level ?? 0;
@endphp

<ul class="menu menu-level-{{ $level }}">
  @foreach($items as $item)
    @php $hasChildren = !empty($item['children']); @endphp

    <li class="menu-item {{ $hasChildren ? 'has-children' : '' }}">
      <a class="menu-link" href="{{ $item['url'] }}">
        {{ $item['label'] }}
      </a>

      @if($hasChildren)
        @include('partials.menu-tree', ['items' => $item['children'], 'level' => $level + 1])
      @endif
    </li>
  @endforeach
</ul>
