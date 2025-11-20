<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Administrador')</title>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @stack('head')
  <style>
    .topbar-admin{display:flex;justify-content:space-between;align-items:center;padding:12px 20px;background:#f6f7fb;border-bottom:1px solid #e6e9ef}
    .topbar-admin .brand{font-weight:700}
    .user-menu{position:relative}
    .user-button{cursor:pointer;padding:6px 10px;border-radius:6px;background:#fff;border:1px solid #ddd}
    .menu{position:absolute;right:0;top:36px;background:#fff;border:1px solid #ddd;border-radius:6px;display:none;min-width:160px}
    .menu form{margin:0}
  </style>
</head>
<body>
  <header class="topbar-admin">
    <div class="brand">Panel Administrador</div>
    <div class="user-menu">
      <div class="user-button" id="userBtn">{{ auth()->user()->nombre ?? 'Usuario' }}</div>
      <div class="menu" id="userMenu">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" style="background:none;border:none;padding:8px 12px;width:100%;text-align:left;cursor:pointer;">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </header>

  <main style="padding:24px;">
    @yield('content')
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

  @stack('scripts')
</body>
</html>
