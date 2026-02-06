@extends('layouts.app')

@section('content')
<section class="py-5">
  <div class="container" style="max-width:860px">
    <div class="mb-3">
      <a href="/blog" class="text-decoration-none">← Volver</a>
    </div>

    <h1 class="h3 mb-2">{{ $title ?? ($post->title ?? '') }}</h1>
    <div class="small muted mb-4">
      {{ optional($post->published_at)->format('d/m/Y') ?? '' }}
    </div>

    <article class="prose">
      {!! $html ?? ($post->content ?? '') !!}
    </article>
  </div>
</section>
@endsection
