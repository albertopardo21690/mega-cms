@php
  $siteName = $site->name ?? 'Sitio';
@endphp

<nav class="navbar navbar-expand-lg bg-white nav-shadow sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">{{ $siteName }}</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        @if(!empty($headerMenu))
          @foreach($headerMenu as $item)
            @php $hasKids = !empty($item['children']); @endphp

            @if($hasKids)
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="{{ $item['url'] ?? '#' }}" data-bs-toggle="dropdown">
                  {{ $item['label'] ?? 'Item' }}
                </a>
                <ul class="dropdown-menu">
                  @foreach($item['children'] as $ch)
                    <li><a class="dropdown-item" href="{{ $ch['url'] ?? '#' }}">{{ $ch['label'] ?? 'Subitem' }}</a></li>
                  @endforeach
                </ul>
              </li>
            @else
              <li class="nav-item">
                <a class="nav-link" href="{{ $item['url'] ?? '#' }}">{{ $item['label'] ?? 'Item' }}</a>
              </li>
            @endif
          @endforeach
        @else
          <li class="nav-item"><a class="nav-link" href="/">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
        @endif
      </ul>
    </div>
  </div>
</nav>
