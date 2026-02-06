@extends('layouts.app')

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="h3 mb-4">Blog</h1>

    @forelse($posts as $p)
      <div class="card mb-3 post-card">
        <div class="card-body">
          <div class="small muted mb-1">
            {{ optional($p->published_at)->format('d/m/Y') ?? '' }}
          </div>
          <h2 class="h5 mb-2">
            <a class="text-decoration-none" href="/blog/{{ $p->slug }}">{{ $p->title }}</a>
          </h2>
          <div class="muted">
            {!! \Illuminate\Support\Str::limit(strip_tags($p->content ?? ''), 180) !!}
          </div>
        </div>
      </div>
    @empty
      <div class="alert alert-light border">No hay posts publicados.</div>
    @endforelse

    <div class="mt-3">
      {{ $posts->links() }}
    </div>
  </div>
</section>
@endsection
