<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Blog - {{ $site->name }}</title></head>
<body>
<h1>Blog - {{ $site->name }}</h1>
<p><a href="/">← Inicio</a></p>

@foreach($posts as $p)
  @extends('layouts.app')

  @section('content')
  <h1>Blog</h1>

  @foreach($posts as $post)
      <article>
          <h2>
              <a href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
          </h2>
      </article>
  @endforeach

  {{ $posts->links() }}
  @endsection

</body>
</html>
