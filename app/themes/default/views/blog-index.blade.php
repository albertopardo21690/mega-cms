@extends('layouts.app')

@section('content')
  <h1>Blog</h1>

  @if($posts->count() === 0)
    <p>No hay entradas todavía.</p>
  @else
    @foreach($posts as $post)
      <article style="margin: 0 0 18px 0;">
        <h2 style="margin: 0 0 6px 0;">
          <a href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
        </h2>

        @if(!empty($post->excerpt))
          <p style="margin: 0;">{{ $post->excerpt }}</p>
        @endif
      </article>
    @endforeach

    <div style="margin-top: 18px;">
      {{ $posts->links() }}
    </div>
  @endif
@endsection
