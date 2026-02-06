<header style="padding:16px 24px;border-bottom:1px solid #eee;">
  <div style="max-width:980px;margin:0 auto;display:flex;gap:16px;align-items:center;justify-content:space-between;">
    <div>
      <strong>{{ $site->name ?? 'Sitio' }}</strong>
      @if(!empty($tenantSettings['site_tagline'] ?? null))
        <div style="font-size:13px;opacity:.75;">{{ $tenantSettings['site_tagline'] }}</div>
      @endif
    </div>

    <nav style="display:flex;gap:12px;align-items:center;">
      @forelse($headerMenu as $item)
        <a href="{{ $item['url'] }}" style="text-decoration:none;">
          {{ $item['label'] }}
        </a>
      @empty
        <a href="/" style="text-decoration:none;">Inicio</a>
        <a href="/blog" style="text-decoration:none;">Blog</a>
      @endforelse
</nav>

  </div>
</header>
