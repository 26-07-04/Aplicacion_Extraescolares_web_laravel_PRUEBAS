@php
  // Normalizar datos: $actividades como array de nombres, $alumnos por actividad
  $unidadNombre = $unidad ?? 'Unidad';
  
  // CAMBIO: Asegurar que $id_semestre esté disponible
  $id_semestre = $id_semestre ?? request()->input('id_semestre') ?? 0;

  // Si no vienen datos desde el backend, podemos usar ejemplos estáticos para vista previa
  if ((!isset($actividades) || (is_countable($actividades) && count($actividades) === 0)) ) {
    $actividades = [
      ['id' => 1, 'nombre' => 'Fútbol'],
      ['id' => 2, 'nombre' => 'Banda de Guerra'],
      ['id' => 3, 'nombre' => 'Robótica']
    ];
  }

  // Estructura de alumnos por actividad (ejemplo). Si el backend envía `$estudiantes` como colección, se puede adaptar.
  if (!isset($estudiantes) || !is_array($estudiantes)) {
    $estudiantes = [
      'Fútbol' => [
        ['nombre' => 'María López', 'control' => '20201001', 'semestre' => '4', 'carrera' => 'ISC'],
        ['nombre' => 'Juan Pérez', 'control' => '20201002', 'semestre' => '6', 'carrera' => 'IME']
      ],
      'Banda de Guerra' => [
        ['nombre' => 'Ana Gómez', 'control' => '20201005', 'semestre' => '2', 'carrera' => 'IGE']
      ],
      'Robótica' => []
    ];
  }

  // Convertir colecciones a arrays simples cuando sea necesario
  if (
    (is_object($actividades) && method_exists($actividades, 'toArray')) || (is_object($actividades) && method_exists($actividades, 'count'))
  ) {
    try { $actividades = (array) $actividades; } catch (
    Throwable $e) { }
  }

  $firstActividad = $actividades[0]['nombre'] ?? $actividades[0] ?? null;
@endphp

<div style="padding:18px;">
  <style>
    /* Estilos para actividades: barra y botones */
    /* Barra de actividades: tamaño ligeramente menor para reducir altura */
    .barra-actividades { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; background:#1B396A; padding:6px 10px; border-radius:8px; }
    /* Botones de actividad: tamaño ligeramente menor y padding reducido */
    .btn-actividad { background: transparent; color:#ffffff; border:none; padding:6px 12px; border-radius:8px; cursor:pointer; transition:box-shadow .12s, transform .08s, background .12s, color .12s; font-weight:600; font-size:15px; }
    .btn-actividad:hover { background: rgba(255,255,255,0.06); box-shadow:0 3px 10px rgba(0,0,0,0.05); transform:translateY(-1px); color: #ffffff; }
    /* Estado activo: texto en blanco y negrita moderada, con recuadro semitransparente */
    .btn-actividad.activo { background: rgba(255,255,255,0.16); color:#ffffff; font-weight:700; box-shadow:0 3px 10px rgba(0,0,0,0.05); }

    /* Encabezado: logo mayor y título alineado a la izquierda */
    /* Encabezado: logo fijado en posición (no se moverá cuando cambie el título) */
    .encabezado-modal { position: relative; margin-bottom:6px; padding-left:170px; min-height:92px; }
    .encabezado-modal .logo-modal { position: absolute; left:0; top:-8px; width:150px; height:auto; display:block; z-index:2; }
    .encabezado-modal .titulo-modal { margin:0 0 0 0; color:#000; font-size:26px; font-weight:700; line-height:1; transform: translateY(-12px); }

    /* Tabla de alumnos: encabezado menos pesado, tamaño levemente menor y fondo gris claro */
    .seccion-alumnos { margin-top:26px; }
    /* Barra de búsqueda y contador: alineados y con separación superior para separarlos del header */
    .contenedor-busqueda { display:flex; align-items:center; gap:12px; margin-top:22px; margin-bottom:16px; }
    .contenedor-busqueda .busqueda-input { padding:8px 10px; border-radius:6px; border:1px solid #ccc; width:320px; }
    .contador-alumnos { color:#333; font-weight:600; }
    .contenedor-tabla { background:#fbfbfe; border-radius:8px; padding:8px; }
    .tabla-alumnos thead th { text-align:center; color:#2b2b2b; font-weight:700; font-size:13px; padding:8px 12px; background:#dfe6ea; line-height:1.1; }
    .tabla-alumnos tbody td { text-align:center; padding:12px; color:#222; font-size:14px; }
    .tabla-alumnos tbody tr td:first-child { width:56px; }
    .tabla-alumnos tbody tr td:nth-child(2) { text-align:left; padding-left:14px; }
    /* Botón Vista Previa pequeño con icono */
    .btn-vista-previa { background-color:#ff7f00; color:white; padding:6px 10px; text-decoration:none; border-radius:4px; font-weight:700; font-size:13px; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 5px rgba(0,0,0,0.12); }
    .btn-vista-previa i { font-size:14px; }

    /* Título de actividad: más grande y en negro */
    #tituloActividad { color: #000 !important; font-size:20px; font-weight:700; margin:0; }
  </style>

  <div class="encabezado-modal">
    <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Logo TecNM" class="logo-modal">
    <div>
      <h2 class="titulo-modal">Actividades Extraescolares - {{ $unidadNombre }}</h2>
      <!-- CAMBIO: Mostrar ID del semestre actual -->
      <div style="color: #1B396A; font-size: 14px; margin-top: 5px;">
        <i class="fas fa-calendar-alt"></i> Semestre ID: {{ $id_semestre }}
      </div>
    </div>
  </div>

  @if(!empty($actividades))
    <div class="barra-actividades" style="margin-bottom:16px;">
      @foreach($actividades as $index => $actividad)
        @php $nombreAct = is_array($actividad) ? ($actividad['nombre'] ?? $actividad[0] ?? 'Actividad') : $actividad; @endphp
        <button class="boton-actividad btn-actividad {{ $index === 0 ? 'activo' : '' }}" data-actividad="{{ $nombreAct }}">{{ $nombreAct }}</button>
      @endforeach
    </div>

    <div class="seccion-alumnos">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <h3 id="tituloActividad" style="margin:0; color:#1B396A;">Alumnos inscritos a {{ $firstActividad }}</h3>
        <!-- CAMBIO 1: Botón Vista Previa con id_semestre -->
        <a href="{{ route('administrador.vista_previa', ['unidad' => $unidadNombre, 'id_semestre' => $id_semestre]) }}" class="btn-vista-previa">
          <i class="fas fa-eye"></i>Vista Previa
        </a>
      </div>

      <div class="contenedor-busqueda" style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px; flex-wrap:wrap;">
        <input type="text" id="busquedaAlumnos" placeholder="Buscar alumno..." class="busqueda-input" style="padding:8px; border:1px solid #ccc; border-radius:6px; width:320px;" onkeyup="filtrarTabla()">
        <div class="contador-alumnos" id="contadorAlumnos" style="color:#333; font-weight:600;">
          <span class="numero">{{ count($estudiantes[$firstActividad] ?? []) }}</span> alumnos inscritos
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
            @php
              $listaInicial = $estudiantes[$firstActividad] ?? [];
            @endphp
            @if(!empty($listaInicial))
              @foreach($listaInicial as $i => $alumno)
                <tr class="fila-alumno" style="border-bottom:1px solid #eee;">
                  <td>{{ $i + 1 }}</td>
                  <td style="text-align:left; padding-left:12px;">{{ $alumno['nombre'] ?? ($alumno->nombre ?? '—') }}</td>
                  <td>{{ $alumno['control'] ?? ($alumno->control ?? '—') }}</td>
                  <td>{{ $alumno['semestre'] ?? ($alumno->semestre ?? '—') }}</td>
                  <td>{{ $alumno['carrera'] ?? ($alumno->carrera ?? '—') }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" style="text-align:center; padding:20px; color:#666;">No hay alumnos inscritos para esta actividad.</td>
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
      <!-- CAMBIO 2: Botón con id_semestre -->
      <a href="{{ route('administrador.vista_previa', ['unidad' => $unidadNombre, 'id_semestre' => $id_semestre]) }}" class="btn-vista-previa" style="margin-top:16px; display:inline-flex; align-items:center;">
        <i class="fas fa-eye"></i>Ir a gestión de actividades
      </a>
    </div>
  @endif
</div>

<script>
  (function(){
    // Manejo de selección de actividad
    const botones = document.querySelectorAll('.btn-actividad');
    const titulo = document.getElementById('tituloActividad');
    const contador = document.getElementById('contadorAlumnos');
    const cuerpo = document.getElementById('cuerpoTablaAlumnos');

    function setActive(button) {
      botones.forEach(b => b.classList.remove('activo'));
      button.classList.add('activo');
    }

    function renderLista(actividad) {
      // El contenido real vendrá del servidor; aquí manejamos datos embebidos en el DOM (Blade)
      try {
        const dataScript = document.getElementById('data_estudiantes');
        const data = dataScript ? JSON.parse(dataScript.textContent) : {};
        const lista = data[actividad] || [];
        titulo.textContent = 'Alumnos inscritos a ' + actividad;
        contador.innerHTML = '<span class="numero">' + lista.length + '</span> alumnos inscritos';
        if (lista.length === 0) {
          cuerpo.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:#666;">No hay alumnos inscritos para esta actividad.</td></tr>';
          return;
        }
        cuerpo.innerHTML = lista.map((al,i) => `
          <tr style="border-bottom:1px solid #eee;"><td style="padding:8px;">${i+1}</td><td style="padding:8px;">${al.nombre}</td><td style="padding:8px;">${al.control}</td><td style="padding:8px;">${al.semestre}</td><td style="padding:8px;">${al.carrera}</td></tr>
        `).join('');
      } catch (e) { console.error(e); }
    }

    botones.forEach(btn => {
      btn.addEventListener('click', function(){
        setActive(this);
        const act = this.dataset.actividad;
        renderLista(act);
      });
    });

    // Filtro de búsqueda simple
    window.filtrarTabla = function() {
      const term = document.getElementById('busquedaAlumnos').value.toLowerCase().trim();
      document.querySelectorAll('#cuerpoTablaAlumnos tr').forEach(tr => {
        const text = tr.textContent.toLowerCase();
        tr.style.display = text.indexOf(term) !== -1 ? '' : 'none';
      });
    }

    // Inicializar: cargar datos embebidos
    document.addEventListener('DOMContentLoaded', function(){
      // Datos embebidos por Blade: objeto JSON con listas por actividad
      window.setTimeout(function(){
        const first = document.querySelector('.btn-actividad');
        if (first) {
          first.click();
        }
      }, 50);
    });
  })();
</script>

@php
  // Inyectar los datos PHP a un script JSON para que el JS los lea (si $estudiantes es un array)
  if (is_array($estudiantes)) {
    try {
      echo "<script id=\"data_estudiantes\" type=\"application/json\">" . json_encode($estudiantes, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) . "</script>";
    } catch (Throwable $e) { }
  }
@endphp