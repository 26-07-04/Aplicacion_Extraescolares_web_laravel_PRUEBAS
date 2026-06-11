<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Complementarias — Panel Coordinador - Demetrio Vallejo Martínez - {{ $semestre->nombre ?? '' }}</title>
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
      cursor: pointer;
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
      <h3>Actividades Complementarias</h3>
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
          <a href="{{ url()->current() }}?show=evaluaciones_impresion" class="{{ $show === 'evaluaciones_impresion' ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i> Impresión de evaluación
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

      @if(empty($show))
      <!-- Welcome Section -->
      <div class="welcome-section">
        <h1>Bienvenido al Sistema de Actividades Complementarias</h1>
        <div class="unidad-nombre">Unidad Académica Demetrio Vallejo Martínez - El Espinal</div>
        <p>Semestre: <strong>{{ $semestre->nombre ?? '—' }}</strong></p>
        
        <p>Desde este panel podrás gestionar estudiantes, constancias y visualizar resultados de actividades complementarias para el semestre seleccionado.</p>
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

        <div class="stat-card" style="display:none;" aria-hidden="true">
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
      <!-- Modal de previsualización de Excel (abre al clicar en una actividad) -->
      <div id="excelModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:3000; align-items:flex-start; justify-content:center; overflow:auto; padding-top:32px; padding-bottom:32px;">
        <div style="background:#fff; width:92%; max-width:980px; border-radius:8px; padding:16px; box-shadow:0 10px 40px rgba(0,0,0,0.35);">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <div>
              <h3 style="margin:0;">Previsualización — <span id="excelModalActividadName"></span></h3>
              <div style="font-size:12px; color:#666; margin-top:6px;"><span id="excelModalInfo"></span></div>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
              <button id="excelModalUpload" style="padding:8px 10px; border-radius:6px; background:#28a745; color:#fff; border:none; cursor:pointer; display:none;">Subir estudiantes</button>
              <button id="excelModalClose" style="padding:8px 10px; border-radius:6px; background:#6c757d; color:#fff; border:none; cursor:pointer;">Cerrar</button>
            </div>
          </div>
          <div style="display:flex; gap:12px; align-items:center; margin-bottom:12px;">
            <button id="excelModalSelectFile" style="padding:8px 10px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:8px;"><i class="fas fa-file-excel"></i>Seleccionar archivo</button>
            <div style="font-size:13px; color:#444;">Actividad seleccionada: <strong><span id="excelModalActividadNameSmall"></span></strong></div>
          </div>
          <div style="max-height:70vh; overflow:auto; background:#fff; border-radius:6px; box-shadow:0 3px 10px rgba(0,0,0,0.06);">
            <table id="excelModalTable" style="width:100%; border-collapse:collapse; min-width:600px;">
              <thead>
                <tr>
                  <th style="position:sticky; top:0; text-align:left; padding:10px 12px; border-bottom:2px solid rgba(0,0,0,0.08); background:#1B396A; color:#fff; font-weight:700; z-index:5;">No. Control</th>
                  <th style="position:sticky; top:0; text-align:left; padding:10px 12px; border-bottom:2px solid rgba(0,0,0,0.08); background:#1B396A; color:#fff; font-weight:700; z-index:5;">Nombre</th>
                  <th style="position:sticky; top:0; text-align:left; padding:10px 12px; border-bottom:2px solid rgba(0,0,0,0.08); background:#1B396A; color:#fff; font-weight:700; z-index:5;">Carrera</th>
                  <th style="position:sticky; top:0; text-align:left; padding:10px 12px; border-bottom:2px solid rgba(0,0,0,0.08); background:#1B396A; color:#fff; font-weight:700; z-index:5;">Sexo</th>
                  <th style="position:sticky; top:0; text-align:left; padding:10px 12px; border-bottom:2px solid rgba(0,0,0,0.08); background:#1B396A; color:#fff; font-weight:700; z-index:5;">Semestre</th>
                  <th style="position:sticky; top:0; text-align:center; padding:10px 12px; border-bottom:2px solid rgba(0,0,0,0.08); background:#1B396A; color:#fff; font-weight:700; z-index:5; width:80px;">Estado</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Input oculto global para Excel (usado por el modal) -->
      <input type="file" id="fileExcelInputGlobal" accept=".xlsx,.xls,.csv" style="display:none;">

      <!-- Activities Section (estática para diseño) -->
      @if(empty($show))
      <div class="activities-section" style="margin:22px auto 0; max-width:1200px;">
        <div style="display:flex; justify-content:flex-start; align-items:center; margin-bottom:12px; gap:12px;">
          <h2 style="font-size:1.05rem; margin:0; color:#fff; background:#1B396A; padding:8px 12px; border-radius:8px;">Actividades complementarias</h2>
        </div>

        <div style="background:#f7fbff; padding:16px; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.03);">
          <div class="activities-grid">
            @if(!empty($actividades) && $actividades->count() > 0)
              @foreach($actividades as $actividad)
                <div class="activity-card" data-actividad-id="{{ $actividad->id ?? $actividad->id_actividad ?? '' }}" data-actividad-nombre="{{ $actividad->nombre_actividad }}">
                  <div class="card-head"><h3 style="margin:0 0 8px 0; font-size:1.05rem; color:#111;">{{ $actividad->nombre_actividad }}</h3></div>
                  <div class="img-wrap">
                    @php $img = $actividad->imagen_url ?? null; @endphp
                    @if($img)
                      <img src="{{ asset($img) }}" alt="{{ $actividad->nombre_actividad }}" style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                    @else
                      <img src="{{ asset('Imagenes/placeholder-actividad.png') }}" alt="Actividad" style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                    @endif
                  </div>
                  <div class="card-body"><p style="margin:0; color:#444; font-size:0.85rem; line-height:1.35;">{{ $actividad->descripcion ?? 'Sin descripción disponible.' }}</p></div>
                </div>
              @endforeach
            @else
              <div style="grid-column:1/-1; padding:18px; color:#444;">
                <p style="margin:0;">No hay actividades complementarias registradas para este semestre y unidad académica.</p>
                @if(!empty($actividades_semestre) && $actividades_semestre->count() > 0)
                  <div style="margin-top:8px; font-size:0.9rem; color:#666;">
                    <strong>Nota:</strong> Existen actividades en el semestre pero ninguna coincide con la unidad académica del usuario.
                    <div style="margin-top:6px;">
                      <table style="width:100%; border-collapse:collapse; font-size:0.9rem; color:#444;">
                        <thead>
                          <tr>
                            <th style="text-align:left; padding:6px; border-bottom:1px solid #e6e6e6;">Actividad</th>
                            <th style="text-align:left; padding:6px; border-bottom:1px solid #e6e6e6;">id_unidad</th>
                            <th style="text-align:left; padding:6px; border-bottom:1px solid #e6e6e6;">unidad nombre</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($actividades_semestre_data as $row)
                            <tr>
                              <td style="padding:6px; border-bottom:1px solid #f0f0f0;">{{ $row['nombre_actividad'] }}</td>
                              <td style="padding:6px; border-bottom:1px solid #f0f0f0;">{{ $row['id_unidad'] }}</td>
                              <td style="padding:6px; border-bottom:1px solid #f0f0f0;">{{ $row['unidad_nombre'] ?? '—' }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>
      @endif
      @if(request()->get('show') === 'estudiantes')
        @include('coordinador.demetrio_vallejo.complementarias.estudiantes_table')
      @endif

      @if(request()->get('show') === 'constancias')
        @include('coordinador.demetrio_vallejo.complementarias.constancias_table')
      @endif

      @if(request()->get('show') === 'evaluaciones_impresion')
        @include('coordinador.partials.evaluaciones_impresion_table', [
          'rutaEvalImpPrint' => 'coordinador.demetrio.evaluacion-formulario.print.complementarias',
          'rutaEvalImpPrintAll' => 'coordinador.demetrio.evaluacion-formulario.print-all.complementarias',
        ])
      @endif

      @if(request()->get('show') === 'resultados')
        @include('coordinador.demetrio_vallejo.complementarias.resultados')
      @endif

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
            width="100%" height="140" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>
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

  <!-- SheetJS y script para manejar la carga desde Activities list -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @include('coordinador.partials.swal_alerta_helper')
  <script>
    (function(){
      // Definir variables globales al inicio
      const __csrf = '{{ csrf_token() }}';
      const __currentSemestreId = '{{ $semestre->id ?? $semestre->id_semestre ?? request()->route('id') ?? 0 }}';
      const __tipoProgramaPanel = @json($tipo_programa_informes_panel ?? \App\Models\Actividad::TIPO_COMPLEMENTARIA);
      const __currentUnidadId = '{{ $user->id_unidad ?? 0 }}';
      
      // Mapear actividades a su id_unidad
      const actividadesUnidadMap = {
        @foreach($actividades ?? [] as $act)
          '{{ $act->id_actividad }}': '{{ $act->id_unidad ?? 0 }}',
        @endforeach
      };

      const fileInput = document.getElementById('fileExcelInputGlobal');
      const modal = document.getElementById('excelModal');
      const modalTableBody = document.querySelector('#excelModalTable tbody');
      const modalActividadName = document.getElementById('excelModalActividadName');
      const modalActividadNameSmall = document.getElementById('excelModalActividadNameSmall');
      const modalInfo = document.getElementById('excelModalInfo');
      const modalClose = document.getElementById('excelModalClose');
      const modalUpload = document.getElementById('excelModalUpload');
      const modalSelectFile = document.getElementById('excelModalSelectFile');

      let actividadSeleccionada = { id: null, nombre: '' };

      // Abrir modal al clicar la tarjeta completa (activity-card)
      document.querySelectorAll('.activity-card[data-actividad-id]').forEach(el => {
        el.addEventListener('click', function(){
          actividadSeleccionada.id = this.dataset.actividadId || null;
          actividadSeleccionada.nombre = this.dataset.actividadNombre || '';
          // Mostrar modal
          if (modal) {
            modal.style.display = 'flex';
            modalActividadName.textContent = actividadSeleccionada.nombre || '';
            modalActividadNameSmall.textContent = actividadSeleccionada.nombre || '';
            modalInfo.textContent = '';
            modalTableBody.innerHTML = '<tr><td colspan="6" style="padding:12px; text-align:center; color:#666;">Seleccione un archivo para previsualizar</td></tr>';
            if (modalUpload) modalUpload.style.display = 'none';
          }
        });
      });

      if (!fileInput) return;

      // Cuando el usuario pulsa el botón dentro del modal para seleccionar archivo
      if (modalSelectFile) modalSelectFile.addEventListener('click', function(){
        // limpiar para permitir re-carga del mismo archivo
        fileInput.value = null;
        fileInput.click();
      });

      // Procesar archivo seleccionado
      fileInput.addEventListener('change', function(e){
        const f = e.target.files[0];
        if (!f) return;
        const reader = new FileReader();
        reader.onload = function(evt) {
          try {
            const data = evt.target.result;
            const workbook = XLSX.read(data, { type: 'array' });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
            if (!rows || rows.length === 0) {
              swalAlerta('El archivo está vacío o no se pudo leer.');
              return;
            }

            // Normalizar encabezados (quitar acentos y caracteres especiales)
            const rawHeaders = rows[0].map(h => ('' + (h || '')).trim());
            function normalizeHeader(s) {
              try { return String(s || '').normalize('NFD').replace(/\p{Diacritic}/gu, '').replace(/[^a-zA-Z0-9]/g, '').toLowerCase(); }
              catch (e) { return String(s || '').replace(/[^a-zA-Z0-9]/g, '').toLowerCase(); }
            }

            const normHeaders = rawHeaders.map(h => normalizeHeader(h));
            const headerMap = {};
            let headerRowPresent = false;
            if (normHeaders.some(h => h && /[a-z]/i.test(h))) {
              headerRowPresent = true;
              normHeaders.forEach((h, i) => {
                if (h.includes('nombre')) headerMap[i] = 'Nombre';
                else if (h.includes('control') || h.includes('nocontrol') || h.includes('numcontrol')) headerMap[i] = 'No_control';
                else if (h.includes('carrera')) headerMap[i] = 'Carrera';
                else if (h.includes('sexo')) headerMap[i] = 'Sexo';
                else if (h.includes('semestre')) headerMap[i] = 'Semestre';
                else headerMap[i] = h || ('col' + i);
              });
            } else {
              headerMap[0] = 'No_control';
              headerMap[1] = 'Nombre';
              headerMap[2] = 'Carrera';
              headerMap[3] = 'Sexo';
              headerMap[4] = 'Semestre';
              headerRowPresent = false;
            }

            const mapped = [];
            const startRow = headerRowPresent ? 1 : 0;
            for (let r = startRow; r < rows.length; r++) {
              const row = rows[r];
              if (!row || row.length === 0) continue;
              const obj = {};
              for (let c = 0; c < row.length; c++) {
                const key = headerMap[c] || ('col' + c);
                const val = row[c] !== undefined && row[c] !== null ? row[c] : '';
                obj[key] = val;
              }
              if (!obj['Nombre'] && !obj['No_control']) continue;
              mapped.push(obj);
            }

            if (modalInfo) {
              modalInfo.textContent = headerRowPresent ? '' : 'Se usó mapeo automático por columnas (orden: No_control, Nombre, Carrera, Sexo, Semestre).';
            }

            // Guardar mapeo en memoria para poder enviarlo al servidor
            window.__lastExcelMapped = mapped;

            // Render en modal
            if (modalActividadName) modalActividadName.textContent = actividadSeleccionada.nombre || sheetName || '';
            if (modalActividadNameSmall) modalActividadNameSmall.textContent = actividadSeleccionada.nombre || sheetName || '';
            modalTableBody.innerHTML = '';
            if (!mapped.length) {
              modalTableBody.innerHTML = '<tr><td colspan="6" style="padding:12px; text-align:center; color:#666;">No hay registros.</td></tr>';
            } else {
              // Validar duplicados antes de renderizar
              const numeroControles = mapped.map(r => r.No_control || r.no_control || r.Control || '');
              
              fetch('/coordinador/actividades/' + actividadSeleccionada.id + '/estudiantes/check-duplicates', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': __csrf,
                  'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                  numeroControles: numeroControles,
                  id_semestre: __currentSemestreId
                })
              }).then(r => r.json()).then(json => {
                const duplicados = json.duplicados || {};
                
                // Renderizar tabla con estado de duplicados
                modalTableBody.innerHTML = mapped.map((r, idx) => {
                  const no = r.No_control || r.no_control || r.Control || '';
                  const esDuplicado = duplicados[no] === true;
                  const bgColor = esDuplicado ? '#fff3cd' : '#fff';
                  const textColor = esDuplicado ? '#856404' : '#000';
                  
                  return `
                    <tr style="background:${bgColor}; ${esDuplicado ? 'opacity:0.7;' : ''}">
                      <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; color:${textColor};">${no}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; color:${textColor};">${r.Nombre || r.nombre || ''}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; color:${textColor};">${r.Carrera || r.carrera || ''}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; color:${textColor};">${r.Sexo || r.sexo || ''}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; color:${textColor};">${r.Semestre || r.semestre || '{{ $semestre->nombre ?? "" }}'}</td>
                      <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; text-align:center; color:${esDuplicado ? '#dc3545' : '#28a745'}; font-weight:700; width:60px;">
                        ${esDuplicado ? '✗ Existe' : '✓ Nuevo'}
                      </td>
                    </tr>`;
                }).join('');
                
                // Contar duplicados y actualizar estado del botón
                const hayDuplicados = Object.values(duplicados).some(v => v === true);
                const totalFilas = mapped.length;
                const filasNuevas = totalFilas - Object.values(duplicados).filter(v => v === true).length;
                
                window.__hasDuplicates = hayDuplicados;
                
                if (modalUpload) {
                  if (filasNuevas > 0) {
                    // Hay al menos un alumno nuevo - permitir subida
                    let btnText = 'Subir estudiantes';
                    if (hayDuplicados) {
                      btnText = `Subir ${filasNuevas} estudiante${filasNuevas !== 1 ? 's' : ''} (ignorando ${totalFilas - filasNuevas} duplicado${totalFilas - filasNuevas !== 1 ? 's' : ''})`;
                    }
                    modalUpload.textContent = btnText;
                    modalUpload.disabled = false;
                    modalUpload.style.opacity = '1';
                    modalUpload.style.cursor = 'pointer';
                  } else {
                    // Todos son duplicados - no permitir subida
                    modalUpload.textContent = 'Todos son duplicados (no hay nada que subir)';
                    modalUpload.disabled = true;
                    modalUpload.style.opacity = '0.5';
                    modalUpload.style.cursor = 'not-allowed';
                  }
                }
              }).catch(err => {
                console.error('Error validando duplicados:', err);
                // Si falla la validación, renderizar sin validación
                modalTableBody.innerHTML = mapped.map(r => `
                  <tr>
                    <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${r.No_control || r.no_control || r.Control || ''}</td>
                    <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${r.Nombre || r.nombre || ''}</td>
                    <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${r.Carrera || r.carrera || ''}</td>
                    <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${r.Sexo || r.sexo || ''}</td>
                    <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${r.Semestre || r.semestre || '{{ $semestre->nombre ?? "" }}'}</td>
                    <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; text-align:center;">—</td>
                  </tr>`).join('');
                if (modalUpload) {
                  modalUpload.textContent = 'Subir estudiantes';
                  modalUpload.disabled = false;
                }
              });
              
              // Mostrar botón de subir
              if (modalUpload) modalUpload.style.display = 'inline-block';
            }

            // Asegurar modal visible
            if (modal) modal.style.display = 'flex';
          } catch (err) {
            console.error(err);
            swalAlerta('Error al procesar el archivo. Asegúrate de que sea un Excel válido.');
          }
        };
        reader.readAsArrayBuffer(f);
      });

      // Enviar al servidor la previsualización (bulk import)
      if (modalUpload) modalUpload.addEventListener('click', function(){
        const mapped = window.__lastExcelMapped || [];
        if (!mapped || mapped.length === 0) {
          swalAlerta('No hay datos para subir. Carga primero un archivo.');
          return;
        }
        if (!actividadSeleccionada.id) {
          swalAlerta('No se detectó la actividad seleccionada. Vuelve a abrir el modal desde la actividad deseada.');
          return;
        }

        // Obtener id_unidad de la actividad, no del usuario
        const unidadIdFromActividad = actividadesUnidadMap[actividadSeleccionada.id] || __currentUnidadId;

        const payload = {
          students: mapped,
          id_unidad: unidadIdFromActividad,
          id_semestre: __currentSemestreId,
          tipo_programa: __tipoProgramaPanel
        };

        modalUpload.disabled = true;
        modalUpload.textContent = 'Subiendo...';

        fetch('/coordinador/actividades/' + actividadSeleccionada.id + '/estudiantes/import', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': __csrf,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        }).then(r => r.json()).then(json => {
          modalUpload.disabled = false;
          modalUpload.textContent = 'Subir estudiantes';
          if (json.inserted !== undefined) {
            const actividadNombre = actividadSeleccionada.nombre || 'la actividad';
            const insertados = json.inserted || 0;
            const totalMsg = insertados === 1 ? '1 alumno ha' : insertados + ' alumnos han';
            
            // Mostrar alerta SweetAlert2 con auto-cierre
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Importación completada',
              html: `<div style="font-size:14px;"><strong>${totalMsg} sido agregados</strong> a <strong>${actividadNombre}</strong>` + 
                    (json.skipped > 0 ? `<br><small style="color:#666; font-size:12px;">(${json.skipped} omitidos)</small>` : '') + `</div>`,
              showConfirmButton: false,
              timer: 2500,
              timerProgressBar: true,
              didOpen: (modal) => {
                const titleEl = modal.querySelector('.swal2-title');
                if (titleEl) titleEl.style.fontSize = '18px';
              }
            });
            
            // Cerrar modal y limpiar
            setTimeout(() => {
              if (modal) modal.style.display = 'none';
              modalTableBody.innerHTML = '';
              if (modalInfo) modalInfo.textContent = '';
            }, 500);
          } else if (json.errors) {
            swalAlerta('Error: ' + JSON.stringify(json));
          } else {
            swalAlerta('Respuesta inesperada del servidor.');
          }
        }).catch(err => {
          console.error(err);
          modalUpload.disabled = false;
          modalUpload.textContent = 'Subir estudiantes';
          swalAlerta('Error al comunicarse con el servidor. Revisa la consola.');
        });
      });

      // Cerrar modal
      if (modalClose) modalClose.addEventListener('click', function(){
        if (modal) modal.style.display = 'none';
        modalTableBody.innerHTML = '';
        if (modalInfo) modalInfo.textContent = '';
      });

      // Cerrar al hacer click fuera del contenido
      if (modal) modal.addEventListener('click', function(e){
        if (e.target === modal) {
          modal.style.display = 'none';
          modalTableBody.innerHTML = '';
          if (modalInfo) modalInfo.textContent = '';
        }
      });
    })();
  </script>

</body>
</html>
