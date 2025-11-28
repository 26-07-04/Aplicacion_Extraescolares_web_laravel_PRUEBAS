<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Coordinador - {{ $unidad ?? auth()->user()->unidad_academica ?? '' }}</title>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/verestudiantes.css') }}">
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
          <a href="{{ route('coordinator.panel') }}" >
            <i class="fas fa-home"></i> Inicio
          </a>
        <li>
          <a href="{{ route('coordinador.verestudiantes', ['unidad' => $unidad ?? $user->unidad_academica ?? 'CIDERS Union Hidalgo']) }}" class="active">
            <i class="fas fa-users"></i> Ver Estudiantes
          </a>
        </li>
        <li>
          <a href="{{ route('coordinador.constancia', ['unidad' => $unidad ?? $user->unidad_academica ?? 'CIDERS Union Hidalgo']) }}">
            <i class="fas fa-file-signature"></i> Constancia de Cumplimiento
          </a>
        </li>
        <li>
          <a href="{{ route('coordinador.informe', ['unidad' => $unidad ?? $user->unidad_academica ?? 'CIDERS Union Hidalgo']) }}">
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
        <a href="{{ route('coordinator.panel') }}" class="btn-regresar-header" title="Regresar al panel" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#1B396A; color:#fff; padding:6px 10px; min-width:36px; border-radius:6px; text-decoration:none;">
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
        Tecnológico Nacional de México - {{ $unidad ?? auth()->user()->unidad_academica ?? '' }}
      </div>

      <!-- Welcome Section -->
      <div class="welcome-section">
        <h1>Bienvenido al Sistema de Actividades Extraescolares</h1>
        <div class="unidad-nombre">{{ $unidad ?? auth()->user()->unidad_academica ?? '' }}</div>
        <p style="margin-top:6px; font-size:14px; color:#333;">Mostrando estudiantes para: <strong>{{ $unidad ?? auth()->user()->unidad_academica ?? '' }}</strong></p>
      </div>

    <!-- Contenedor de la tabla -->
<div class="tabla-container">
    <div class="tabla-header">
        <h2>Usuarios Registrados</h2>
        <div class="search-container">
            <input type="text" id="buscador-nombre" placeholder="Buscar por nombre..." class="buscador">
            <button id="btn-buscar-nombre" class="btn-buscar" title="Buscar">
                <i class="fas fa-search"></i>
            </button>
            <button id="btn-agregar" class="btn-agregar" title="Agregar estudiante">
                <i class="fas fa-plus"></i>
            </button>
            <button id="btn-recargar" class="btn-recargar" title="Recargar tabla">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    
    <table id="dataTable" class="display nowrap">
        <thead>
            <tr>
                <th>No. Control</th>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Extraescolar</th>
                <th>Semestre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se cargarán dinámicamente mediante AJAX -->
        </tbody>
    </table>
</div>


    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-text">
          <h3>Dirección</h3>
          <p>I.m. Altamirano 1776, Centro<br>70150 Unión Hidalgo, Oax.</p>
          <h3>Contacto</h3>
          <p>Teléfono: <a href="tel:+529631251607" style="color:#fff;text-decoration:none">+52 963 125 1607</a></p>
          <h3>Preguntar por Whatsapp</h3>
          <p>
            <a href="https://wa.me/529631251607" target="_blank" rel="noopener">
              <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="WhatsApp" class="footer-whatsapp">
            </a>
          </p>
        </div>
        <div class="footer-map">
          <iframe
            src="https://maps.google.com/maps?q=I.m.%20Altamirano%201776%2C%20Centro%2C%2070150%20Uni%C3%B3n%20Hidalgo%2C%20Oax.&output=embed"
            width="100%" height="280" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
      <div class="footer-copyright">
        <p>© 2025 Tecnológico Nacional de México - Unión Hidalgo. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <!-- Modal para agregar estudiante -->
  <div id="modalAgregarEstudiante" class="modal-overlay" style="display:none;">
    <div class="modal-content-form">
      <div class="modal-header-form">
        <h2>Agregar Nuevo Estudiante</h2>
        <button class="btn-close-modal" onclick="cerrarModalAgregar()">&times;</button>
      </div>
      <form id="formAgregarEstudiante">
        @csrf
        <div class="form-grid">
          <div class="form-group">
            <label for="numero_control">Número de Control *</label>
            <input type="text" id="numero_control" name="numero_control" required>
          </div>
          <div class="form-group">
            <label for="nombre">Nombre Completo *</label>
            <input type="text" id="nombre" name="nombre" required>
          </div>
          <div class="form-group">
            <label for="carrera">Carrera *</label>
            <select id="carrera" name="carrera" required>
              <option value="">Seleccione una carrera</option>
              <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
              <option value="Ingeniería Industrial">Ingeniería Industrial</option>
              <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
              <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
              <option value="Ingeniería Mecatrónica">Ingeniería Mecatrónica</option>
              <option value="Contador Público">Contador Público</option>
            </select>
          </div>
          <div class="form-group">
            <label for="semestre">Semestre *</label>
            <select id="semestre" name="semestre" required>
              <option value="">Seleccione semestre</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
            </select>
          </div>
          <div class="form-group">
            <label for="actividad_extraescolar">Actividad Extraescolar *</label>
            <input type="text" id="actividad_extraescolar" name="actividad_extraescolar" required>
          </div>
          <div class="form-group">
            <label for="contacto">Contacto</label>
            <input type="text" id="contacto" name="contacto" placeholder="Teléfono o email">
          </div>
        </div>
        <div class="form-actions">
          <button type="button" class="btn-cancelar" onclick="cerrarModalAgregar()">Cancelar</button>
          <button type="submit" class="btn-guardar">Guardar Estudiante</button>
        </div>
      </form>
    </div>
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

    // Funciones para el modal de agregar estudiante
    function abrirModalAgregar() {
      document.getElementById('modalAgregarEstudiante').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalAgregar() {
      document.getElementById('modalAgregarEstudiante').style.display = 'none';
      document.body.style.overflow = '';
      document.getElementById('formAgregarEstudiante').reset();
    }

    // Event listener para el botón de agregar
    document.getElementById('btn-agregar').addEventListener('click', abrirModalAgregar);

    // Cerrar modal al hacer clic fuera de él
    document.getElementById('modalAgregarEstudiante').addEventListener('click', function(e) {
      if (e.target === this) cerrarModalAgregar();
    });

    // Manejar el envío del formulario
    document.getElementById('formAgregarEstudiante').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const unidad = '{{ $unidad ?? auth()->user()->unidad_academica ?? "" }}';
      formData.append('unidad_academica', unidad);
      
      try {
        const response = await fetch('{{ route("coordinador.estudiantes.store") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
          alert('Estudiante agregado exitosamente');
          cerrarModalAgregar();
          // Recargar la tabla (aquí deberías tener tu función de recarga)
          location.reload();
        } else {
          alert(data.message || 'Error al agregar estudiante');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
      }
    });
  </script>

</body>
</html>
