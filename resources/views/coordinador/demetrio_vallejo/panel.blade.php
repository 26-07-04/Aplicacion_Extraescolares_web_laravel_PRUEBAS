<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Coordinador - Demetrio Vallejo Martínez - {{ $semestre->nombre ?? '' }}</title>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/UnionHidalgo-panel.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('Imagenes/ITVE.png') }}" alt="ITVE" class="sidebar-logo">
      <h3>Actividades Extraescolares</h3>
    </div>
    <div class="sidebar-menu">
      <ul>
        <li>
          <a href="javascript:void(0)" class="active">
            <i class="fas fa-home"></i> Inicio
          </a>
        </li>
        <li>
          <a href="{{ route('coordinador.verestudiantes', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">
            <i class="fas fa-users"></i> Ver Estudiantes
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fas fa-file-signature"></i> Constancia de Cumplimiento
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fas fa-file-pdf"></i> Informe de Actividad
          </a>
        </li>
        <li>
          <a href="#">
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
        <a href="{{ route('coordinador.semestres.demetrio') }}" class="btn-regresar-header" title="Regresar a Semestres cursados" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#1B396A; color:#fff; padding:6px 10px; min-width:36px; border-radius:6px; text-decoration:none;">
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
        Tecnológico Nacional de México - Unidad Académica Demetrio Vallejo Martínez - El Espinal
      </div>

      <!-- Welcome Section -->
      <div class="welcome-section">
        <h1>Bienvenido al Sistema de Actividades Extraescolares</h1>
        <div class="unidad-nombre">Unidad Académica Demetrio Vallejo Martínez - El Espinal</div>
        <p>Semestre: <strong>{{ $semestre->nombre ?? '—' }}</strong></p>
        <p>Desde este panel podrás gestionar estudiantes, constancias, informes y visualizar resultados para el semestre seleccionado.</p>
      </div>

      <!-- Stats Cards -->
      <div class="stats-cards">
        <div class="stat-card">
          <div class="stat-card-header">
            <span class="stat-card-title">Ver Estudiantes</span>
            <div class="stat-card-icon blue"><i class="fas fa-users"></i></div>
          </div>
          <div class="stat-card-value">—</div>
          <div class="stat-card-footer">Consultar y gestionar la información de los estudiantes registrados en este semestre.</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <span class="stat-card-title">Constancias</span>
            <div class="stat-card-icon orange"><i class="fas fa-file-signature"></i></div>
          </div>
          <div class="stat-card-value">—</div>
          <div class="stat-card-footer">Generar y descargar constancias de cumplimiento para los estudiantes.</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <span class="stat-card-title">Informes</span>
            <div class="stat-card-icon green"><i class="fas fa-file-pdf"></i></div>
          </div>
          <div class="stat-card-value">—</div>
          <div class="stat-card-footer">Registrar y consultar reportes de actividades realizadas.</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <span class="stat-card-title">Resultados</span>
            <div class="stat-card-icon purple"><i class="fas fa-chart-line"></i></div>
          </div>
          <div class="stat-card-value">—</div>
          <div class="stat-card-footer">Visualizar indicadores y estadísticas del semestre.</div>
        </div>
      </div>

    </div>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-text" style="margin-top:18px">
          <h3>Dirección</h3>
          <p>1RA., 2da Secc, 70117 El Espinal, Oax.</p>
          <h3>Contacto</h3>
          <p>Sitio web: <a href="https://vetla.tecnm.mx/" target="_blank" rel="noopener" style="color:#fff;text-decoration:underline">https://vetla.tecnm.mx/</a></p>
          
        </div>
        <div class="footer-map">
          <iframe
            src="https://www.google.com/maps?q=TecNM+Campus+Valle+de+Etla+Unidad+Demetrio+Vallejo&output=embed"
            width="100%" height="280" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>
          <div style="margin-top:8px; text-align:right;">
            <a href="https://www.google.com/maps/search/?api=1&query=TecNM+Campus+Valle+de+Etla+Unidad+Demetrio+Vallejo" target="_blank" rel="noopener" style="color:#fff; text-decoration:underline;">Abrir en Google Maps</a>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <p>© 2025 Tecnológico Nacional de México - Demetrio Vallejo Martínez. Todos los derechos reservados.</p>
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
