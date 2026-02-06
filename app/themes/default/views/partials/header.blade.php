<header style="padding:16px 24px;border-bottom:1px solid #eee;">
  <div style="max-width:980px;margin:0 auto;display:flex;gap:16px;align-items:center;justify-content:space-between;">
    <div>
      <strong>{{ $site->name ?? 'Sitio' }}</strong>
      @if(!empty($tenantSettings['site_tagline'] ?? null))
        <div style="font-size:13px;opacity:.75;">{{ $tenantSettings['site_tagline'] }}</div>
      @endif
    </div>

    <nav class="nav">
      @if(!empty($headerMenu))
        @include('partials.menu-tree', ['items' => $headerMenu, 'level' => 0])
      @else
        <ul class="menu menu-level-0">
          <li class="menu-item"><a class="menu-link" href="/">Inicio</a></li>
          <li class="menu-item"><a class="menu-link" href="/blog">Blog</a></li>
        </ul>
      @endif
    </nav>

  </div>
</header>
