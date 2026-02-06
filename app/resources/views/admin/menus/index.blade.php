<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Menús</title>
<style>
body{font-family:system-ui,Arial;margin:24px}
a{color:#111}
.card{border:1px solid #eee;border-radius:12px;padding:14px;max-width:860px}
.row{display:flex;gap:10px;align-items:center;justify-content:space-between}
.btn{border:1px solid #ddd;border-radius:10px;padding:8px 10px;background:#fff;cursor:pointer;text-decoration:none}
</style>
</head>
<body>
<h1>Menús</h1>
<div class="card">
  <div class="row">
    <div>
      <strong>Header</strong>
      <div style="opacity:.7;font-size:13px">Menú principal</div>
    </div>
    <a class="btn" href="/admin/menus/header">Editar</a>
  </div>
  <hr style="border:0;border-top:1px solid #eee;margin:14px 0">
  <div class="row">
    <div>
      <strong>Footer</strong>
      <div style="opacity:.7;font-size:13px">Menú del pie</div>
    </div>
    <a class="btn" href="/admin/menus/footer">Editar</a>
  </div>
</div>
</body>
</html>
