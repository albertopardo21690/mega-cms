<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Blog - {{ $site->name }}</title></head>
<body>
<h1>Blog - {{ $site->name }}</h1>
<p><a href="/">← Inicio</a></p>

@foreach($posts as $p)
  <article style="margin-bottom:16px;">
    <h2><a href="/blog/{{ $p->slug }}">{{ $p->title }}</a></h2>
    <small>{{ $p->published_at }}</small>
    <p>{{ $p->excerpt }}</p>
  </article>
@endforeach

{{ $posts->links() }}
</body>
</html>
