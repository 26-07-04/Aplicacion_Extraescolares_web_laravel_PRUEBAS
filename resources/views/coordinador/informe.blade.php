<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Coordinador - {{ $unidad ?? auth()->user()->unidad_academica ?? 'Unidad Académica' }} - {{ $semestre->nombre ?? '' }}</title>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/UnionHidalgo-panel.css') }}">
  <link rel="stylesheet" href="{{ asset('css/informe.css') }}">
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
        </li>
        <li>
          <a href="{{ route('coordinador.verestudiantes', ['unidad' => $unidad ?? $user->unidad_academica ?? '']) }}">
            <i class="fas fa-users"></i> Ver Estudiantes
          </a>
        </li>
        <li>
          <a href="{{ route('coordinador.constancia', ['unidad' => $unidad ?? $user->unidad_academica ?? '']) }}"class="active">
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
        <a href="{{ route('coordinador.semestres.union') }}" class="btn-regresar-header" title="Regresar a Semestres cursados" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#1B396A; color:#fff; padding:6px 10px; min-width:36px; border-radius:6px; text-decoration:none;">
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
        Tecnológico Nacional de México - {{ $unidad ?? auth()->user()->unidad_academica ?? 'Unidad Académica' }}
      </div>

      <!-- Welcome Section -->
      <div class="welcome-section">
        <h1>Bienvenido al Sistema de Actividades Extraescolares</h1>
        <div class="unidad-nombre">{{ $unidad ?? auth()->user()->unidad_academica ?? 'Unidad Académica' }}</div>
      </div>

  
  <!-- Contenedor exclusivo para el informe -->
  <div class="informe-container">
    <table class="encabezado">
      <tr>
        <td class="logo" rowspan="2">
          <img src="{{ asset('Imagenes/sgc.jpg') }}" alt="Logo SGC" class="sgc-logo" />
        </td>
        <td class="titulo">Informe de Actividad Cultural y/o Deportiva</td>
        <td class="derecha">Código: TecNM-VI-PO-003-02</td>
      </tr>
      <tr>
        <td style="text-align: center;">Referencia a la Norma ISO 9001:2015 &nbsp; 8.2.2</td>
        <td style="text-align: right;">Revisión: 0 <br>Página 1 de 2</td>
      </tr>
    </table>

  <div class="informe-header">
    <h2 class="main-title">INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA</h2>
    <span class="spacer"></span>
    <h3 class="subtitle">Subdirección de Planeación y Vinculación</h3>
    <h3 class="subtitle">DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES<br>OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA</h3>
    <h3 class="subtitle periodo">Informe Semestral del Periodo
      <span class="editable" data-value="">
        <input type="text" oninput="this.parentElement.dataset.value = this.value" placeholder="Periodo">
      </span>
    </h3>
  </div>


    <div class="header-inputs">
      <label>Actividad Cultural 
        <span class="editable" data-value="">
          <input type="text" oninput="this.parentElement.dataset.value = this.value">
        </span>
      </label>
      <label>Deportiva 
        <span class="editable" data-value="">
          <input type="text" oninput="this.parentElement.dataset.value = this.value">
        </span>
      </label>
    </div>

    <table class="tabla-estilo" style="width: 100%; border-collapse: collapse; margin-top: 38px;">
      <thead>
        <tr style="background: #1B396A; color: #fff;">
          <th>No.</th>
          <th>Nombre del Evento</th>
          <th>Institución Organizadora</th>
          <th>Fecha</th>
          <th>Participantes</th>
          <th>M</th>
          <th>H</th>
          <th>Resultados</th>
        </tr>
      </thead>
      <tbody id="eventos-body">
        <tr>
          <td>1</td>
          <td><span class="editable" data-value=""><input type="text" oninput="this.parentElement.dataset.value = this.value" placeholder="Evento"></span></td>
          <td><span class="editable" data-value=""><input type="text" oninput="this.parentElement.dataset.value = this.value" placeholder="Institución"></span></td>
          <td><span class="editable" data-value=""><input type="date" oninput="this.parentElement.dataset.value = this.value"></span></td>
          <td><span class="editable" data-value=""><input type="number" min="0" oninput="this.parentElement.dataset.value = this.value"></span></td>
          <td><span class="editable" data-value=""><input type="number" min="0" oninput="this.parentElement.dataset.value = this.value"></span></td>
          <td><span class="editable" data-value=""><input type="number" min="0" oninput="this.parentElement.dataset.value = this.value"></span></td>
          <td><span class="editable" data-value=""><input type="text" placeholder="Resultados" oninput="this.parentElement.dataset.value = this.value"></span></td>
        </tr>
      </tbody>
    </table>

    <div class="contenedor-tabla">
      <table class="tabla-alumnos">
        <!-- ...tabla... -->
      </table>
    </div>

  <!-- Lugar y Fecha -->
<div class="lugar-fecha-container">
  <div class="lugar-fecha" data-value="">
    <label>Lugar y Fecha:</label>
    <input type="text" id="lugarFecha" placeholder="Oaxaca, 06 de agosto de 2025" 
           oninput="this.parentElement.setAttribute('data-value', this.value)">
  </div>
</div>

    <!-- Botones -->
    <div class="footer-botones">
      <button class="btn-accion" onclick="agregarFila()">Agregar Evento</button>
      <button class="btn-accion" onclick="eliminarUltimaFila()">Eliminar Evento</button>
      <button class="btn-accion" onclick="window.print()">Imprimir</button>
    </div>


    <!-- Firmas -->
    <div class="footer-firmas">
      <div class="footer-content">
        <div class="firma">
          <div></div>
          <span>Jefe(a) de la oficina de promoción</span>
        </div>
        <div class="firma">
          <div></div>
          <span>Jefe(a) del Departamento</span>
        </div>
      </div>
    </div>
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

<script>

    function agregarFila() {
  const tbody = document.getElementById('eventos-body');
  const rowCount = tbody.rows.length + 1;
  
  if (rowCount > 12) {
    alert("No se pueden agregar más de 12 eventos.");
    return; // Detiene la función si ya hay 13 filas
  }
  
  const row = document.createElement('tr');
  row.innerHTML = `
    <td>${rowCount}</td>
    <td><span class="editable" data-value=""><input type="text" oninput="this.parentElement.dataset.value = this.value" placeholder="Evento"></span></td>
    <td><span class="editable" data-value=""><input type="text" oninput="this.parentElement.dataset.value = this.value" placeholder="Institución"></span></td>
    <td><span class="editable" data-value=""><input type="date" oninput="this.parentElement.dataset.value = this.value"></span></td>
    <td><span class="editable" data-value=""><input type="number" min="0" oninput="this.parentElement.dataset.value = this.value"></span></td>
    <td><span class="editable" data-value=""><input type="number" min="0" oninput="this.parentElement.dataset.value = this.value"></span></td>
    <td><span class="editable" data-value=""><input type="number" min="0" oninput="this.parentElement.dataset.value = this.value"></span></td>
    <td><span class="editable" data-value=""><input type="text" placeholder="Resultados" oninput="this.parentElement.dataset.value = this.value"></span></td>
  `;
  tbody.appendChild(row);
}

function eliminarUltimaFila() {
  const tbody = document.getElementById('eventos-body');
  if (tbody && tbody.rows.length > 0) {
    tbody.deleteRow(tbody.rows.length - 1);
  } else {
    alert("No hay filas para eliminar.");
  }
}

function prepararParaImprimir() {
  const filasPorPagina = 12;
  const tbody = document.getElementById('eventos-body');
  const filas = tbody.querySelectorAll('tr');
  const totalPaginas = Math.ceil(filas.length / filasPorPagina);
  
    // Limpiar grupos anteriores si existen
    document.querySelectorAll('.grupo-filas').forEach(g => g.remove());
    // Eliminar cualquier print-value previo
    document.querySelectorAll('.print-value').forEach(n => n.remove());

    // Crear nodos de texto imprimibles para reemplazar inputs/placeholder durante la impresión
    document.querySelectorAll('.editable, .lugar-fecha').forEach(function(el) {
      // remove previous print-values in case
      const existing = el.querySelectorAll('.print-value');
      existing.forEach(e => e.remove());

      const input = el.querySelector('input, textarea, select');
      let text = '';
      if (input) {
        // preferir el valor real del control (value), si está vacío usar dataset.value creado por oninput
        text = input.value || el.dataset.value || '';
      } else {
        text = el.dataset.value || '';
      }
      // Si el texto está vacío, no se agrega (evita mostrar placeholder)
      if (text !== '') {
        const p = document.createElement('span');
        p.className = 'print-value';
        p.textContent = text;
        el.appendChild(p);
      }
    });
  
  // Crear grupos de filas
  for (let i = 0; i < totalPaginas; i++) {
      const grupo = document.createElement('div');
      grupo.className = 'grupo-filas';
      
      // Clonar la tabla completa (para mantener estructura)
      const tablaClon = document.querySelector('.tabla-estilo').cloneNode(true);
      const tbodyClon = tablaClon.querySelector('tbody');
      
      // Limpiar el tbody clonado
      while (tbodyClon.firstChild) {
          tbodyClon.removeChild(tbodyClon.firstChild);
      }
      
      // Agregar solo las filas de esta página
      const inicio = i * filasPorPagina;
      const fin = inicio + filasPorPagina;
      
      for (let j = inicio; j < fin && j < filas.length; j++) {
          tbodyClon.appendChild(filas[j].cloneNode(true));
      }
      
      grupo.appendChild(tablaClon);
      
      // Clonar y agregar firmas
      const firmasClon = document.querySelector('.footer-firmas').cloneNode(true);
      grupo.appendChild(firmasClon);
      
      // Insertar en el documento
      document.querySelector('.informe-container').insertBefore(grupo, document.querySelector('.footer-botones'));
  }
  
  // Ocultar la tabla original
  document.querySelector('.tabla-estilo').style.display = 'none';
}

// Modificar el botón de imprimir para que primero prepare el documento
document.addEventListener('DOMContentLoaded', function() {
  const btnImprimir = document.querySelector('.btn-accion[onclick="window.print()"]');
  if (btnImprimir) {
    btnImprimir.onclick = function() {
      prepararParaImprimir();
      setTimeout(() => {
          window.print();
          // Restaurar la tabla original después de imprimir
          document.querySelector('.tabla-estilo').style.display = '';
          document.querySelectorAll('.grupo-filas').forEach(g => g.remove());
          // Eliminar nodos de impresión (print-value) para regresar la UI editable
          document.querySelectorAll('.print-value').forEach(n => n.remove());
      }, 500);
    };
  }

  // Actualizar el data-value automáticamente
  const lugarFecha = document.getElementById('lugarFecha');
  if (lugarFecha) {
    lugarFecha.addEventListener('input', function() {
      this.parentElement.setAttribute('data-value', this.value);
    });

    // Configurar el valor inicial si es necesario
    if (lugarFecha.value) {
      lugarFecha.parentElement.setAttribute('data-value', lugarFecha.value);
    }
  }
});
</script>
</body>
</html>
