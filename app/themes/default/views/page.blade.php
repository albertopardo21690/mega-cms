@extends('layouts.app')

@section('content')
<section class="py-5">
  <div class="container" style="max-width:860px">
    <h1 class="h3 mb-3">{{ $title ?? ($page->title ?? '') }}</h1>
    <article>
      {!! $html ?? ($page->content ?? '') !!}
    </article>
  </div>
</section>
@endsection
