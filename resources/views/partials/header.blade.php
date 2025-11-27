<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="{{ route('home') }}">
      <img src="{{ asset('Imagenes/1.png') }}" alt="ITVE" class="brand-logo">
      <span class="brand-text">Actividades Extraescolares</span>
    </a>

    <nav class="main-nav" id="mainNav">
      <a href="{{ route('home') }}">Inicio</a>
      <a href="{{ route('about') }}">Acerca de</a>
      <a href="{{ route('contact') }}">Contacto</a>
      @if (Route::has('login'))
        @auth
          <a href="{{ route('dashboard') }}">Dashboard</a>
        @else
          <a href="{{ route('login') }}">Iniciar sesión</a>
        @endauth
      @endif
      @auth
        @if(in_array(auth()->user()->rol ?? '', ['Coordinador', 'Administrador']))
          {{-- Pass the current unidad if the page provides one, otherwise fallback to the authenticated user's unidad --}}
          <a href="{{ route('coordinador.verestudiantes', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">Ver Estudiantes</a>
          <a href="{{ route('coordinador.constancia', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">Constancias</a>
          <a href="{{ route('coordinador.informe', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">Informe de Actividad</a>
        @endif
      @endauth
    </nav>

    <button class="nav-toggle" id="navToggle" aria-label="Abrir menú">
      <i class="fas fa-bars"></i>
    </button>

    @auth
      <div style="margin-left:12px; display:flex; align-items:center; gap:12px;">
        <span style="font-weight:600;">{{ auth()->user()->nombre }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" style="border:none;background:transparent;color:inherit;cursor:pointer;">Cerrar sesión</button>
        </form>
      </div>
    @endauth
  </div>
</header>
