<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Panel Administrador</title>
  <link rel="stylesheet" href="/css/home.css">
  <style>
    .topbar { display:flex; justify-content:flex-end; padding:12px 20px; background:#f5f7fa; }
    .user-menu { position:relative; }
    .user-button { cursor:pointer; padding:6px 10px; border-radius:6px; background:#fff; border:1px solid #ddd; }
    .menu { position:absolute; right:0; top:36px; background:#fff; border:1px solid #ddd; border-radius:6px; display:none; min-width:160px; }
    .menu a, .menu form { display:block; padding:8px 12px; color:#111; text-decoration:none; }
    .menu a:hover { background:#f0f0f0; }
  </style>
</head>
<body>
  <div class="topbar">
    <div class="user-menu">
      <div class="user-button" id="userBtn">{{ $user->nombre }}</div>
      <div class="menu" id="userMenu">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" style="background:none;border:none;padding:8px 12px;width:100%;text-align:left;cursor:pointer;">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </div>

  <main style="padding:40px;">
    <h1>Panel de Administrador</h1>
    <p>Estás logeado como Administrador.</p>
  </main>

  <script>
    document.getElementById('userBtn').addEventListener('click', function(){
      const m = document.getElementById('userMenu');
      m.style.display = (m.style.display === 'block') ? 'none' : 'block';
    });
    document.addEventListener('click', function(e){
      const menu = document.getElementById('userMenu');
      const btn = document.getElementById('userBtn');
      if (!btn.contains(e.target) && !menu.contains(e.target)) menu.style.display='none';
    });
  </script>
</body>
</html>
