<footer style="padding:16px 24px;border-top:1px solid #eee;margin-top:32px;">
  <div style="margin-top:8px;display:flex;gap:12px;flex-wrap:wrap;">
    @foreach($footerMenu as $item)
      <a href="{{ $item['url'] }}" style="text-decoration:none;opacity:.85;">
        {{ $item['label'] }}
      </a>
    @endforeach
  </div>
</footer>
