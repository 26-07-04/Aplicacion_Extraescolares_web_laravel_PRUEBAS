<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Actividades Extraescolares')</title>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @stack('head')
</head>
<body>
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
      </nav>

      <button class="nav-toggle" id="navToggle" aria-label="Abrir menú">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </header>

  <div class="site-content">
    @yield('content')
  </div>

  <footer class="site-footer">
    <div class="contact-bar">
      <div class="container contact-inner">
        <div class="contact-left">
          <div class="contact-block">
            <h4>Dirección</h4>
            <p>Abasolo S/N, Barrio del Agua Buena,<br> Santiago Suchilquitongo Oaxaca, C.P. 68230</p>
          </div>

          <div class="contact-block">
            <h4>Contacto</h4>
            <p>Email: <a href="mailto:info@vetla.tecnm.mx">info@vetla.tecnm.mx</a></p>
            <p>Teléfono: 951 305 29 27</p>
          </div>

          <div class="contact-block">
            <p class="whatsapp-row">
              <span class="whatsapp-text"><strong>Preguntar por WhatsApp</strong></span>
              <a class="whatsapp-icon-link" href="https://wa.me/529513052927" target="_blank" rel="noopener" aria-label="Abrir WhatsApp">
                <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="WhatsApp" class="whatsapp-icon">
              </a>
            </p>
          </div>
        </div>

        <div class="contact-right">
          <iframe
            src="https://www.google.com/maps?q=Tecnol%C3%B3gico%20Nacional%20de%20M%C3%A9xico%20campus%20Instituto%20Tecnol%C3%B3gico%20del%20Valle%20de%20Etla&output=embed"
            width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
  </footer>

    <script src="{{ asset('js/home.js') }}"></script>
    @stack('scripts')
  </body>
  </html>
