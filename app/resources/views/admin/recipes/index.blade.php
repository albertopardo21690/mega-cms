<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Recipes</title></head>
<body>
<h1>Instalar Recetas</h1>
<p><a href="/admin">← Dashboard</a></p>

@if(session('ok')) <p><strong>{{ session('ok') }}</strong></p> @endif

<form method="post" action="{{ route('admin.recipes.install') }}">
@csrf
<select name="recipe">
  <option value="corporate">Corporate</option>
  <option value="blog">Blog</option>
</select>
<button type="submit">Instalar</button>
</form>
</body>
</html>
