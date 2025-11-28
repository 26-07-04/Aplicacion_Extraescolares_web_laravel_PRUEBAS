<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Coordinador - Tlahuitoltepec - {{ $semestre->nombre ?? '' }}</title>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/UnionHidalgo-panel.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Overrides para compactar el footer y el mapa */
    .site-footer {
      padding: 12px 0 8px 0;
    }
    .footer-container {
      padding: 8px 16px;
      gap: 14px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .footer-text h3 {
      margin-bottom: 8px;
      font-size: 1.05em;
    }
    .footer-text p {
      margin-bottom: 8px;
      line-height: 1.4;
    }
    .footer-map iframe {
      width: 270px !important;
      height: 160px !important;
      max-width: 100%;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .footer-copyright {
      margin-top: 12px;
      padding-top: 10px;
      font-size: 0.85em;
    }
    /* Activities grid and cards (responsive) */
    .activities-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(260px, 1fr));
      gap: 16px;
      justify-content: center;
      align-items: start;
    }
    .activity-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.06);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      max-width: 360px;
    }
    .activity-card .img-wrap {
      padding: 8px 12px;
      height: 140px;
      overflow: hidden;
      display:flex;
      align-items:center;
      justify-content:center;
      background:transparent;
    }
    .activity-card .card-head { padding:12px 14px; }
    .activity-card .card-body { padding:10px 14px 14px 14px; margin-top:auto; }

    @media (max-width: 1000px) {
      .activities-grid { grid-template-columns: repeat(2, minmax(200px, 1fr)); }
    }
    @media (max-width: 640px) {
      .activities-grid { grid-template-columns: 1fr; }
      .activity-card { max-width: 100%; }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('Imagenes/ITVE.png') }}" alt="ITVE" class="sidebar-logo">
      <h3>Actividades Extraescolares</h3>
    </div>
    @php $show = request()->get('show'); @endphp
    <div class="sidebar-menu">
      <ul>
        <li>
          <a href="{{ url()->current() }}" class="{{ $show ? '' : 'active' }}">
            <i class="fas fa-home"></i> Inicio
          </a>
        </li>
        <li>
          <a href="{{ url()->current() }}?show=estudiantes" class="{{ $show === 'estudiantes' ? 'active' : '' }}">
            <i class="fas fa-users"></i> Ver Estudiantes
          </a>
        </li>
        <li>
          <a href="{{ url()->current() }}?show=constancias" class="{{ $show === 'constancias' ? 'active' : '' }}">
            <i class="fas fa-file-signature"></i> Constancia de Cumplimiento
          </a>
        </li>
        <li>
          <a href="{{ url()->current() }}?show=informe" class="{{ $show === 'informe' ? 'active' : '' }}">
            <i class="fas fa-file-pdf"></i> Informe de Actividad
          </a>
        </li>
        <li>
          <a href="{{ url()->current() }}?show=resultados" class="{{ $show === 'resultados' ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Resultados
          </a>
        </li>
      </ul>
    </div>
  </div>


  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navbar -->
      <div class="top-navbar">
      <div class="user-menu" style="position:relative; display:flex; align-items:center; gap:24px; margin-left:auto;">
        <a href="{{ route('coordinador.semestres.tlahuitoltepec') }}" class="btn-regresar-header" title="Regresar a Semestres cursados" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#1B396A; color:#fff; padding:6px 10px; min-width:36px; border-radius:6px; text-decoration:none;">
          <i class="fas fa-arrow-left" style="font-size:16px; color:#fff; line-height:1;"></i>
        </a>
        <i class="fas fa-user-circle" id="iconoPerfil" style="font-size:34px; color:#1B396A; cursor:pointer;"></i>
        <div id="perfilDropdown" style="display:none; position:absolute; right:0; top:50px; background:#fff; border:1px solid #e5e5e5; box-shadow:0 6px 18px rgba(0,0,0,0.08); border-radius:6px; min-width:220px; z-index:2000;">
          <div style="padding:12px 14px; border-bottom:1px solid #f0f0f0;">
            <strong>{{ $user->nombre ?? 'Usuario' }}</strong>
            <div style="font-size:13px; color:#666;">{{ $user->contacto ?? $user->unidad_academica ?? '' }}</div>
          </div>
          <div style="padding:10px;">
            <button id="btnCerrarSesion" style="width:100%; background:#dc3545; color:#fff; border:none; padding:8px 10px; border-radius:4px; cursor:pointer; font-weight:600;">Cerrar sesión</button>
          </div>
        </div>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
      </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper">
      <!-- Logos -->
      <div class="header-main">
        <div class="logo-enlace">
              <a href="https://www.gob.mx/" target="_blank"><img src="{{ asset('Imagenes/gobt.png') }}" alt="GobMX"></a>
              <a href="https://www.gob.mx/sep" target="_blank"><img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="SEP"></a>
              <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="TecNM"></a>
              <a href="http://www.vetla.tecnm.mx/" target="_blank"><img src="{{ asset('Imagenes/pleca-ITVE.png') }}" alt="ITVE"></a>
        </div>
      </div>

      <!-- Encabezado de unidad -->
      <div class="unidad-header">
        Tecnológico Nacional de México - Unidad Académica Santa María Tlahuitoltepec
      </div>
      @if(empty($show))
      <!-- Welcome Section -->
      <div class="welcome-section">
        <h1>Bienvenido al Sistema de Actividades Extraescolares</h1>
        <div class="unidad-nombre">Unidad Académica Santa María Tlahuitoltepec</div>
        <p>Semestre: <strong>{{ $semestre->nombre ?? '—' }}</strong></p>
        <p>Desde este panel podrás gestionar estudiantes, constancias, informes y visualizar resultados para el semestre seleccionado.</p>
      </div>

      <!-- Stats Cards (compact, uniform sizes only) -->
      <div class="stats-cards" style="display:flex; gap:12px; flex-wrap:nowrap; justify-content:space-between; align-items:stretch; width:100%;">
        <div class="stat-card" style="flex:0 0 24%; max-width:24%; box-sizing:border-box; padding:8px 6px; display:flex; flex-direction:column; justify-content:space-between; height:130px;">
          <div class="stat-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
            <span class="stat-card-title" style="font-size:0.85rem;">Ver Estudiantes</span>
            <div class="stat-card-icon blue" style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:14px; padding:0; border-radius:50%;"><i class="fas fa-users"></i></div>
          </div>
          <div class="stat-card-value" style="font-size:1rem; margin:6px 0;">—</div>
          <div class="stat-card-footer" style="font-size:0.72rem; color:inherit; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">Consultar y gestionar la información de los estudiantes registrados en este semestre.</div>
        </div>

        <div class="stat-card" style="flex:0 0 24%; max-width:24%; box-sizing:border-box; padding:8px 6px; display:flex; flex-direction:column; justify-content:space-between; height:130px;">
          <div class="stat-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
            <span class="stat-card-title" style="font-size:0.85rem;">Constancias</span>
            <div class="stat-card-icon orange" style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:14px; padding:0; border-radius:50%;"><i class="fas fa-file-signature"></i></div>
          </div>
          <div class="stat-card-value" style="font-size:1rem; margin:6px 0;">—</div>
          <div class="stat-card-footer" style="font-size:0.72rem; color:inherit; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">Generar y descargar constancias de cumplimiento para los estudiantes.</div>
        </div>

        <div class="stat-card" style="flex:0 0 24%; max-width:24%; box-sizing:border-box; padding:8px 6px; display:flex; flex-direction:column; justify-content:space-between; height:130px;">
          <div class="stat-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
            <span class="stat-card-title" style="font-size:0.85rem;">Informes</span>
            <div class="stat-card-icon green" style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:14px; padding:0; border-radius:50%;"><i class="fas fa-file-pdf"></i></div>
          </div>
          <div class="stat-card-value" style="font-size:1rem; margin:6px 0;">—</div>
          <div class="stat-card-footer" style="font-size:0.72rem; color:inherit; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">Registrar y consultar reportes de actividades realizadas.</div>
        </div>

        <div class="stat-card" style="flex:0 0 24%; max-width:24%; box-sizing:border-box; padding:8px 6px; display:flex; flex-direction:column; justify-content:space-between; height:130px;">
          <div class="stat-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
            <span class="stat-card-title" style="font-size:0.85rem;">Resultados</span>
            <div class="stat-card-icon purple" style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; font-size:14px; padding:0; border-radius:50%;"><i class="fas fa-chart-line"></i></div>
          </div>
          <div class="stat-card-value" style="font-size:1rem; margin:6px 0;">—</div>
          <div class="stat-card-footer" style="font-size:0.72rem; color:inherit; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">Visualizar indicadores y estadísticas del semestre.</div>
        </div>
      </div>
      @endif

      <!-- Activities Section (estática para diseño) -->
      @if(empty($show))
      <div class="activities-section" style="margin:22px auto 0; max-width:1200px;">
        <div style="display:flex; justify-content:flex-start; align-items:center; margin-bottom:12px; gap:12px;">
          <h2 style="font-size:1.05rem; margin:0; color:#fff; background:#1B396A; padding:8px 12px; border-radius:8px;">Actividades</h2>
        </div>

        <div style="background:#f7fbff; padding:16px; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.03);">
          <div class="activities-grid">
            <div class="activity-card">
              <div class="card-head"><h3 style="margin:0 0 8px 0; font-size:1.05rem; color:#111;">Futbol Rápido</h3></div>
              <div class="img-wrap"><img src="{{ asset('Imagenes/futbol.png') }}" alt="Futbol Rápido" style="width:100%; height:100%; object-fit:cover; border-radius:6px;"></div>
              <div class="card-body"><p style="margin:0; color:#444; font-size:0.85rem; line-height:1.35;">Entrena con tu equipo en horarios programados, mejora tu condición física y representa al TECNM en torneos locales y regionales.</p></div>
            </div>

            <div class="activity-card">
              <div class="card-head"><h3 style="margin:0 0 8px 0; font-size:1.05rem; color:#111;">Basquetbol</h3></div>
              <div class="img-wrap"><img src="{{ asset('Imagenes/basquetbol.png') }}" alt="Basquetbol" style="width:100%; height:100%; object-fit:cover; border-radius:6px;"></div>
              <div class="card-body"><p style="margin:0; color:#444; font-size:0.85rem; line-height:1.35;">Practica tu habilidad y estrategias en ligas internas y torneos intercolegiales.</p></div>
            </div>

            <div class="activity-card">
              <div class="card-head"><h3 style="margin:0 0 8px 0; font-size:1.05rem; color:#111;">Voleibol</h3></div>
              <div class="img-wrap"><img src="{{ asset('Imagenes/voleibol.jpg') }}" alt="Voleibol" style="width:100%; height:100%; object-fit:cover; border-radius:6px;"></div>
              <div class="card-body"><p style="margin:0; color:#444; font-size:0.85rem; line-height:1.35;">Entrena en equipo con enfoque en técnica, táctica y condición física.</p></div>
            </div>

            <div class="activity-card">
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(request()->get('show') === 'estudiantes')
        @include('coordinador.tlahuitoltepec.estudiantes_table')
      @endif

      @if(request()->get('show') === 'constancias')
        @include('coordinador.tlahuitoltepec.constancias_table')
      @endif

      @if(request()->get('show') === 'informe')
        @include('coordinador.tlahuitoltepec.informe_actividad')
      @endif

      @if(request()->get('show') === 'resultados')
        @include('coordinador.tlahuitoltepec.resultados')
      @endif

    </div>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-text">
          <h3>Dirección</h3>
          <p>Santa María Tlahuitoltepec, Oaxaca.</p>
          <h3>Contacto</h3>
          <p>Teléfono: <a href="tel:+529515284660" style="color:#fff;text-decoration:none">9515284660</a></p>
          <h3>Preguntar por Whatsapp</h3>
          <p>
            <a href="https://wa.me/529515284660" target="_blank" rel="noopener">
              <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="WhatsApp" class="footer-whatsapp">
            </a>
          </p>
        </div>
        <div class="footer-map">
          <iframe
            src="https://maps.google.com/maps?q=Santa%20Mar%C3%ADa%20Tlahuitoltepec&output=embed"
            width="100%" height="280" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
      <div class="footer-copyright">
        <p>© 2025 Tecnológico Nacional de México - Santa María Tlahuitoltepec. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <!-- Script para dropdown de perfil y logout -->
  <script>
    (function(){
      const icon = document.getElementById('iconoPerfil');
      const dropdown = document.getElementById('perfilDropdown');
      const btnCerrar = document.getElementById('btnCerrarSesion');
      const logoutForm = document.getElementById('logoutForm');

      if (!icon) return;

      function hideDropdown() { if (dropdown) dropdown.style.display = 'none'; }
      function showDropdown() { if (dropdown) dropdown.style.display = 'block'; }

      icon.addEventListener('click', function (e) {
        e.stopPropagation();
        if (!dropdown) return;
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
      });

      document.addEventListener('click', function (e) {
        if (!dropdown) return;
        const target = e.target;
        if (target === dropdown || dropdown.contains(target) || target === icon) return;
        hideDropdown();
      });

      if (btnCerrar) btnCerrar.addEventListener('click', function (e) {
        e.preventDefault();
        if (!logoutForm) return window.location.href = '/';
        logoutForm.submit();
      });
    })();
  </script>

</body>
</html>
