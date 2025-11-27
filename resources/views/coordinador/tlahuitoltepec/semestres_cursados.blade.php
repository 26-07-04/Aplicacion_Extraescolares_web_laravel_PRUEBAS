<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Semestres Cursados - Unidad Académica Tlahuitoltepec</title>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/Semestres-cursados.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <div class="header">
    <div class="header-logos">
      <img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="Logo Educación">
      <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Logo TecNM">
    </div>
    <div class="header-user" style="position: relative;">
      <i class="fas fa-user-circle" id="iconoPerfil" style="cursor: pointer;"></i>

      <div id="perfilDropdown" style="display:none; position: absolute; right: 0; top: 48px; background: white; border: 1px solid #e5e5e5; box-shadow: 0 6px 18px rgba(0,0,0,0.08); border-radius: 6px; min-width:200px; z-index:2000;">
        <div style="padding:12px 14px; border-bottom:1px solid #f0f0f0;">
          <strong>{{ $user->nombre ?? 'Usuario' }}</strong>
          <div style="font-size:13px; color:#666;">{{ $user->contacto ?? $user->unidad_academica ?? '' }}</div>
        </div>
        <div style="padding:8px 10px;">
          <button id="btnCerrarSesion" style="width:100%; background:#dc3545; color:#fff; border:none; padding:8px 10px; border-radius:4px; cursor:pointer;">Cerrar sesión</button>
        </div>
      </div>

      <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>
  </div>

  <div class="title-bar">
    <h1> Semestres Cursados - Unidad Académica Tlahuitoltepec</h1>
    <p>Sistema de Actividades Extraescolares - ITVE</p>
  </div>

  <div class="main-container">
    <div class="semestres-container">
      <div class="semestres-header">
        <h2>Historial de Semestres - Tlahuitoltepec</h2>
        <p class="descripcion-seccion">
          Aquí se muestran los semestres disponibles creados por el Administrador para la Unidad Académica Tlahuitoltepec.
        </p>
      </div>

      <div class="semestres-grid" id="contenedorSemestres">
        @forelse($semestres as $semestre)
          <a href="{{ route('coordinador.tlahuitoltepec.panel', $semestre->id_semestre) }}" class="semestre-card-link">
          <div class="semestre-card" data-id="{{ $semestre->id_semestre }}">
            <div class="semestre-header">
              <span class="semestre-periodo">{{ $semestre->nombre }}</span>
              <div class="semestre-header-actions">
                @if(isset($semestre->estatus) && $semestre->estatus == 1)
                  <span class="semestre-estado activo">Activo</span>
                @endif
              </div>
            </div>
            <div class="semestre-body">
              <div class="semestre-info">
                <p><i class="fas fa-calendar-alt"></i> <strong>Fecha inicio:</strong> {{ date('d/m/Y', strtotime($semestre->fecha_inicio)) }}</p>
                <p><i class="fas fa-calendar-check"></i> <strong>Fecha fin:</strong> {{ date('d/m/Y', strtotime($semestre->fecha_fin)) }}</p>
              </div>
            </div>
          </div>
          </a>
        @empty
          <div class="semestres-empty-card">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            <h3>No hay semestres registrados aún</h3>
            <p>Contacte con el Administrador para registrar nuevos semestres.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-content">
      <p>&copy; 2025 Instituto Tecnológico del Valle de Etla. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script>
    (function(){
      const icon = document.getElementById('iconoPerfil');
      const dropdown = document.getElementById('perfilDropdown');
      const btnCerrar = document.getElementById('btnCerrarSesion');
      const logoutForm = document.getElementById('logoutForm');

      function hideDropdown() { dropdown.style.display = 'none'; }
      function showDropdown() { dropdown.style.display = 'block'; }

      icon && icon.addEventListener('click', function (e) {
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

      btnCerrar && btnCerrar.addEventListener('click', function (e) {
        e.preventDefault();
        if (!logoutForm) return window.location.href = '/';
        logoutForm.submit();
      });
    })();
  </script>

</body>
</html>