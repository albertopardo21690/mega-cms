<footer class="py-5 bg-light mt-5 border-top">
  <div class="container">
    <div class="d-flex justify-content-between flex-wrap gap-2">
      <div class="small muted">© {{ date('Y') }} {{ $site->name ?? 'Sitio' }}</div>
      <div class="small">
        <a href="/admin" class="text-decoration-none">Admin</a>
      </div>
    </div>
  </div>
</footer>
