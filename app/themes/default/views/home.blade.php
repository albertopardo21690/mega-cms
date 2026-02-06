@extends('layouts.app')

@section('content')
<section class="hero">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <h1 class="display-5 mb-2">{{ $site->name ?? 'Sitio' }}</h1>
        <p class="lead muted mb-4">{{ $tenantSettings['site_tagline'] ?? 'Soluciones profesionales' }}</p>
        <div class="d-flex gap-2">
          <a class="btn btn-light fw-semibold" href="/blog">Ver blog</a>
          <a class="btn btn-outline-light" href="/admin">Administrar</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h2 class="h4 mb-3">Últimas publicaciones</h2>
    <p class="muted">Aquí pondremos un bloque con los últimos posts (en el siguiente paso lo conectamos).</p>
  </div>
</section>
@endsection
