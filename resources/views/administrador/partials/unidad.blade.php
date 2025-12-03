@php
  // Asegurar que las variables existan
  $unidadNombre = $unidad ?? 'Unidad';
  $id_semestre = $id_semestre ?? request()->input('id_semestre') ?? 0;
  
  // Verificar que $actividades sea una colección/array válida
  if (!isset($actividades) || empty($actividades)) {
    $actividades = collect();
  }
  
  // Verificar que $estudiantesPorActividad sea un array válido
  // Esta variable debe venir del controlador con la estructura: 
  // [id_actividad => [estudiantes], id_actividad2 => [estudiantes], ...]
  if (!isset($estudiantesPorActividad) || !is_array($estudiantesPorActividad)) {
    $estudiantesPorActividad = [];
  }
  
  // Obtener la primera actividad para mostrar inicialmente
  $firstActividad = null;
  $firstActividadId = null;
  $firstActividadNombre = null;
  
  if ($actividades->count() > 0) {
    $firstActividad = $actividades->first();
    $firstActividadId = $firstActividad->id_actividad ?? $firstActividad->id ?? null;
    $firstActividadNombre = $firstActividad->nombre_actividad ?? $firstActividad->nombre ?? 'Actividad';
  }
  
  // Estudiantes de la primera actividad
  $estudiantesPrimeraActividad = $estudiantesPorActividad[$firstActividadId] ?? [];
@endphp

<div style="padding:18px;">
  <style>
    /* Estilos para actividades: barra y botones */
    .barra-actividades { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; background:#1B396A; padding:6px 10px; border-radius:8px; }
    .btn-actividad { background: transparent; color:#ffffff; border:none; padding:6px 12px; border-radius:8px; cursor:pointer; transition:box-shadow .12s, transform .08s, background .12s, color .12s; font-weight:600; font-size:15px; }
    .btn-actividad:hover { background: rgba(255,255,255,0.06); box-shadow:0 3px 10px rgba(0,0,0,0.05); transform:translateY(-1px); color: #ffffff; }
    .btn-actividad.activo { background: rgba(255,255,255,0.16); color:#ffffff; font-weight:700; box-shadow:0 3px 10px rgba(0,0,0,0.05); }

    /* Encabezado */
    .encabezado-modal { position: relative; margin-bottom:6px; padding-left:170px; min-height:92px; }
    .encabezado-modal .logo-modal { position: absolute; left:0; top:-8px; width:150px; height:auto; display:block; z-index:2; }
    .encabezado-modal .titulo-modal { margin:0 0 0 0; color:#000; font-size:26px; font-weight:700; line-height:1; transform: translateY(-12px); }

    /* Tabla de alumnos */
    .seccion-alumnos { margin-top:26px; }
    .contenedor-busqueda { display:flex; align-items:center; gap:12px; margin-top:22px; margin-bottom:16px; }
    .contenedor-busqueda .busqueda-input { padding:8px 10px; border-radius:6px; border:1px solid #ccc; width:320px; }
    .contador-alumnos { color:#333; font-weight:600; }
    .contenedor-tabla { background:#fbfbfe; border-radius:8px; padding:8px; }
    .tabla-alumnos thead th { text-align:center; color:#2b2b2b; font-weight:700; font-size:13px; padding:8px 12px; background:#dfe6ea; line-height:1.1; }
    .tabla-alumnos tbody td { text-align:center; padding:12px; color:#222; font-size:14px; }
    .tabla-alumnos tbody tr td:first-child { width:56px; }
    .tabla-alumnos tbody tr td:nth-child(2) { text-align:left; padding-left:14px; }
    
    .btn-vista-previa { background-color:#ff7f00; color:white; padding:6px 10px; text-decoration:none; border-radius:4px; font-weight:700; font-size:13px; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 5px rgba(0,0,0,0.12); }
    .btn-vista-previa i { font-size:14px; }

    #tituloActividad { color: #000 !important; font-size:20px; font-weight:700; margin:0; }
  </style>

  <div class="encabezado-modal">
    <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Logo TecNM" class="logo-modal">
    <div>
      <h2 class="titulo-modal">Actividades Extraescolares - {{ $unidadNombre }}</h2>
      <div style="color: #1B396A; font-size: 14px; margin-top: 5px;">
        <i class="fas fa-calendar-alt"></i> Semestre ID: {{ $id_semestre }}
      </div>
    </div>
  </div>

  @if($actividades->count() > 0)
    <div class="barra-actividades" style="margin-bottom:16px;">
      @foreach($actividades as $index => $actividad)
        @php
          $actividadId = $actividad->id_actividad ?? $actividad->id ?? $index;
          $actividadNombre = $actividad->nombre_actividad ?? $actividad->nombre ?? 'Actividad';
        @endphp
        <button class="boton-actividad btn-actividad {{ $index === 0 ? 'activo' : '' }}" 
                data-actividad-id="{{ $actividadId }}"
                data-actividad-nombre="{{ $actividadNombre }}">
          {{ $actividadNombre }}
        </button>
      @endforeach
    </div>

    <div class="seccion-alumnos">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <h3 id="tituloActividad" style="margin:0; color:#1B396A;">
          Alumnos inscritos a <span id="nombreActividadActual">{{ $firstActividadNombre }}</span>
        </h3>
        <a href="{{ route('administrador.vista_previa', ['unidad' => $unidadNombre, 'id_semestre' => $id_semestre]) }}" class="btn-vista-previa">
          <i class="fas fa-eye"></i>Vista Previa
        </a>
      </div>

      <div class="contenedor-busqueda" style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px; flex-wrap:wrap;">
        <input type="text" id="busquedaAlumnos" placeholder="Buscar alumno..." class="busqueda-input" style="padding:8px; border:1px solid #ccc; border-radius:6px; width:320px;" onkeyup="filtrarTabla()">
        <div class="contador-alumnos" id="contadorAlumnos" style="color:#333; font-weight:600;">
          <span class="numero">{{ count($estudiantesPrimeraActividad) }}</span> alumnos inscritos
        </div>
      </div>

      <div class="contenedor-tabla">
        <table class="tabla-alumnos" style="width:100%; border-collapse:collapse; font-family: 'Segoe UI', sans-serif;">
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>No. de Control</th>
              <th>Semestre</th>
              <th>Carrera</th>
            </tr>
          </thead>
          <tbody id="cuerpoTablaAlumnos">
            @if(count($estudiantesPrimeraActividad) > 0)
              @foreach($estudiantesPrimeraActividad as $i => $alumno)
                <tr class="fila-alumno" style="border-bottom:1px solid #eee;">
                  <td>{{ $i + 1 }}</td>
                  <td style="text-align:left; padding-left:12px;">{{ $alumno->nombre ?? $alumno['nombre'] ?? '—' }}</td>
                  <td>{{ $alumno->control ?? $alumno['control'] ?? '—' }}</td>
                  <td>{{ $alumno->semestre ?? $alumno['semestre'] ?? '—' }}</td>
                  <td>{{ $alumno->carrera ?? $alumno['carrera'] ?? '—' }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" style="text-align:center; padding:20px; color:#666;">
                  No hay alumnos inscritos para esta actividad.
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div class="sin-actividades" style="text-align:center; padding:40px; background:white; border-radius:8px; margin-top:20px;">
      <i class="fas fa-exclamation-circle" style="font-size:40px; color:#ff7f00; margin-bottom:12px;"></i>
      <h3 style="color:#1B396A; margin-bottom:8px;">No hay actividades registradas</h3>
      <p style="color:#555; font-size:15px;">No se encontraron actividades extraescolares para esta unidad académica.</p>
      <a href="{{ route('administrador.vista_previa', ['unidad' => $unidadNombre, 'id_semestre' => $id_semestre]) }}" class="btn-vista-previa" style="margin-top:16px; display:inline-flex; align-items:center;">
        <i class="fas fa-eye"></i>Ir a gestión de actividades
      </a>
    </div>
  @endif
</div>

<script>
  (function(){
    // Datos de estudiantes por actividad desde PHP
    const estudiantesPorActividad = @json($estudiantesPorActividad);
    
    // Elementos DOM
    const botones = document.querySelectorAll('.btn-actividad');
    const titulo = document.getElementById('tituloActividad');
    const nombreActividadSpan = document.getElementById('nombreActividadActual');
    const contador = document.getElementById('contadorAlumnos');
    const cuerpo = document.getElementById('cuerpoTablaAlumnos');
    const busquedaInput = document.getElementById('busquedaAlumnos');

    // Activar botón
    function setActive(button) {
      botones.forEach(b => b.classList.remove('activo'));
      button.classList.add('activo');
    }

    // Renderizar estudiantes de una actividad
    function renderEstudiantes(actividadId, actividadNombre) {
      const estudiantes = estudiantesPorActividad[actividadId] || [];
      
      // Actualizar título
      nombreActividadSpan.textContent = actividadNombre;
      
      // Actualizar contador
      contador.innerHTML = `<span class="numero">${estudiantes.length}</span> alumnos inscritos`;
      
      // Renderizar tabla
      if (estudiantes.length === 0) {
        cuerpo.innerHTML = `
          <tr>
            <td colspan="5" style="text-align:center; padding:20px; color:#666;">
              No hay alumnos inscritos para esta actividad.
            </td>
          </tr>
        `;
      } else {
        cuerpo.innerHTML = estudiantes.map((estudiante, index) => {
          // Manejar tanto objetos como arrays
          const nombre = estudiante.nombre || estudiante['nombre'] || '—';
          const control = estudiante.control || estudiante['control'] || '—';
          const semestre = estudiante.semestre || estudiante['semestre'] || '—';
          const carrera = estudiante.carrera || estudiante['carrera'] || '—';
          
          return `
            <tr class="fila-alumno" style="border-bottom:1px solid #eee;">
              <td>${index + 1}</td>
              <td style="text-align:left; padding-left:12px;">${nombre}</td>
              <td>${control}</td>
              <td>${semestre}</td>
              <td>${carrera}</td>
            </tr>
          `;
        }).join('');
      }
      
      // Aplicar filtro si hay texto en búsqueda
      if (busquedaInput.value.trim()) {
        filtrarTabla();
      }
    }

    // Eventos para botones de actividad
    botones.forEach(btn => {
      btn.addEventListener('click', function() {
        setActive(this);
        const actividadId = this.dataset.actividadId;
        const actividadNombre = this.dataset.actividadNombre;
        renderEstudiantes(actividadId, actividadNombre);
      });
    });

    // Filtro de búsqueda
    window.filtrarTabla = function() {
      const term = busquedaInput.value.toLowerCase().trim();
      const filas = cuerpo.querySelectorAll('.fila-alumno');
      
      let visibleCount = 0;
      
      filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        const isVisible = texto.includes(term);
        fila.style.display = isVisible ? '' : 'none';
        
        if (isVisible) {
          visibleCount++;
        }
      });
      
      // Actualizar contador de visibles
      if (term) {
        contador.innerHTML = `<span class="numero">${visibleCount}</span> alumnos encontrados`;
      }
    };

    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
      if (botones.length > 0) {
        // Activar el primer botón si no está activo
        const activo = document.querySelector('.btn-actividad.activo');
        if (!activo && botones[0]) {
          botones[0].click();
        }
      }
    });

  })();
</script>