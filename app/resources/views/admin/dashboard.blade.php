<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>
</head>
<body>
  <h1>Admin (Tenant: {{ app('currentSite')->subdomain }})</h1>

  <ul>
    <li><a href="/admin/page">Pages</a></li>
    <li><a href="/admin/post">Posts</a></li>
    <li><a href="/admin/settings">Settings</a></li>
  </ul>
</body>
</html>
