<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','Admin')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">

  <div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r hidden md:block">
      <div class="p-4 font-bold text-lg">
        {{ app('currentSite')->name ?? 'Admin' }}
        <div class="text-xs text-gray-500">Tenant</div>
      </div>

      <nav class="px-3 pb-6 space-y-1">
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin">Dashboard</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin/page">Páginas</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin/post">Posts</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin/media">Medios</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin/taxonomies">Taxonomías</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin/menus">Menús</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-gray-100" href="/admin/recipes">Recetas</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1">

      <!-- Topbar -->
      <header class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
          <div class="font-semibold">@yield('header','Panel')</div>

          <div class="flex items-center gap-2">
            <a class="text-sm px-3 py-2 rounded-lg border bg-white hover:bg-gray-50" href="/">Ver sitio</a>
          </div>
        </div>
      </header>

      <div class="max-w-6xl mx-auto p-4">
        @yield('content')
      </div>

    </main>
  </div>

</body>
</html>
