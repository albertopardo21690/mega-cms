<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($pages as $p)
  <url>
    <loc>{{ url(($p->type==='post'?'blog/':'').$p->slug) }}</loc>
    <lastmod>{{ $p->updated_at->toAtomString() }}</lastmod>
  </url>
@endforeach
</urlset>
