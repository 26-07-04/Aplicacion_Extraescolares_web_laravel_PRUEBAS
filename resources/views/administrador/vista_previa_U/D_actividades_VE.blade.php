<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de Actividad Extraescolar - {{ $actividad->nombre ?? 'Detalle' }}</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/Administrador/D_actividades.css') }}">
</head>
<body>
  <div class="wrapper">
    <!-- Botón regresar -->
    <a href="{{ url()->previous() }}"
       onclick="event.preventDefault(); if (history.length > 1) history.back(); else window.location.href='{{ route('admin.semestres') }}';"
       class="btn-flecha-back" title="Regresar" aria-label="Regresar">
      <svg viewBox="0 0 24 24" class="icon-flecha" aria-hidden="true" focusable="false" role="img">
        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
      </svg>
    </a>

    <div class="logo-gobierno">
      <a href="https://www.gob.mx/sep" target="_blank"><img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="SEP"></a>
      <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="TecNM"></a>
      <a href="{{ url('/') }}" target="_blank"><img src="{{ asset('Imagenes/pleca-ITVE.png') }}" alt="ITVE"></a>
    </div>

    <main>
      <div class="contenido-detalle">
        <div class="info-actividad">
          <h2 id="nombre-actividad">{{ $actividad->nombre ?? 'Nombre de la Actividad' }}</h2>
          <p id="descripcion-actividad">{{ $actividad->descripcion ?? 'Descripción de la actividad...' }}</p>
        </div>

        <div class="tabla-container">
          <table>
            <thead>
              <tr>
                <th>Nombre del Estudiante</th>
                <th>No. de Control</th>
                <th>Carrera</th>
                <th>Semestre</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="tabla-estudiantes">
              <!-- Datos estáticos de ejemplo (Valle de Etla) -->
              <tr>
                <td>Luisa Torres</td>
                <td>2042003</td>
                <td>Ingeniería en Sistemas</td>
                <td>5</td>
                <td class="acciones-celda">
                  <button class="btn-accion btn-editar" title="Editar" data-id="301" data-nombre="Luisa Torres" data-numero_control="2042003" data-carrera="Ingeniería en Sistemas" data-semestre="5">
                    <i class="bi bi-pen-fill" style="color:#002147; font-size:1.2em;"></i>
                  </button>
                  <button class="btn-accion btn-eliminar" title="Eliminar" data-id="301">
                    <i class="bi bi-trash-fill" style="color:#002147; font-size:1.2em;"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>Óscar Velasco</td>
                <td>2042010</td>
                <td>Ingeniería en Energías</td>
                <td>6</td>
                <td class="acciones-celda">
                  <button class="btn-accion btn-editar" title="Editar" data-id="302" data-nombre="Óscar Velasco" data-numero_control="2042010" data-carrera="Ingeniería en Energías" data-semestre="6">
                    <i class="bi bi-pen-fill" style="color:#002147; font-size:1.2em;"></i>
                  </button>
                  <button class="btn-accion btn-eliminar" title="Eliminar" data-id="302">
                    <i class="bi bi-trash-fill" style="color:#002147; font-size:1.2em;"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>Fernanda Ruiz</td>
                <td>2042031</td>
                <td>Diseño Industrial</td>
                <td>3</td>
                <td class="acciones-celda">
                  <button class="btn-accion btn-editar" title="Editar" data-id="303" data-nombre="Fernanda Ruiz" data-numero_control="2042031" data-carrera="Diseño Industrial" data-semestre="3">
                    <i class="bi bi-pen-fill" style="color:#002147; font-size:1.2em;"></i>
                  </button>
                  <button class="btn-accion btn-eliminar" title="Eliminar" data-id="303">
                    <i class="bi bi-trash-fill" style="color:#002147; font-size:1.2em;"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Modal (misma estructura que las demás vistas) -->
    <div id="modal-editar" class="modal" aria-hidden="true">
      <div class="modal-contenido" role="dialog" aria-modal="true" aria-labelledby="modal-titulo-editar">
        <div class="modal-header">
          <h3 id="modal-titulo-editar">Editar Estudiante</h3>
          <button class="cerrar" id="cerrar-modal-editar" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
          <form id="form-editar-estudiante" novalidate>
            <input type="hidden" id="editar-id" value="">

            <label for="editar-nombre">Nombre</label>
            <input type="text" id="editar-nombre" required>

            <label for="editar-numero-control">No. de Control</label>
            <input type="text" id="editar-numero-control" required>

            <label for="editar-carrera">Carrera</label>
            <input type="text" id="editar-carrera" required>

            <label for="editar-semestre">Semestre</label>
            <input type="number" id="editar-semestre" min="1" max="12" required>

            <div class="modal-footer">
              <button type="button" class="btn-secundario" id="btn-cancelar-edicion">Cancelar</button>
              <button type="submit" class="btn-primario" id="btn-guardar-estudiante">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer (igual que las otras vistas) -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-text">
          <h3>Dirección</h3>
          <p>Abasolo S/N, Barrio del Agua Buena<br>Santiago Suchilquitongo Oaxaca, C.P. 68230</p>

          <h3>Contacto</h3>
          <p>Email: info@vetla.tecnm.mx<br>Teléfono: 951 305 29 27</p>

          <h3>Preguntar por Whatsapp</h3>
          <a href="https://wa.me/9513052927?text=Buen%20d%C3%ADa%2C%20deseo%20informaci%C3%B3n%20" target="_blank" class="whatsapp-link">
            <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="Whatsapp">
          </a>
        </div>

        <div class="footer-map">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15241.367048623282!2d-96.8702191!3d17.2506929!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85c6e36d891967db%3A0x512f566ad941ae57!2sTecnol%C3%B3gico%20Nacional%20de%20M%C3%A9xico%20campus%20Instituto%20Tecnol%C3%B3gico%20del%20Valle%20de%20Etla!5e0!3m2!1ses!2smx!4v1621983062705!5m2!1ses!2smx"
            allowfullscreen>
          </iframe>
        </div>
      </div>

      <div class="footer-copyright">
        <p>© {{ date('Y') }} Tecnológico Nacional de México - Campus Valle de Etla. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <!-- Configuración de endpoints para el JS (VE) -->
  <script>
    window.DActividadesVE = {
      id_actividad: "{{ request()->query('id_actividad') ?? ($actividad->id ?? '') }}",
      endpoints: {
        obtenerActividad: "{{ url('php/obtener_actividad_ve.php') }}",
        obtenerAlumnos: "{{ url('php/obtener_alumnos_inscritos_ve.php') }}",
        eliminarAlumno: "{{ url('php/eliminar_alumno_ve.php') }}",
        guardarAlumno: "{{ url('php/guardar_alumno_ve.php') }}",
        editarAlumno: "{{ url('php/editar_alumno_ve.php') }}"
      }
    };
  </script>

  <script>
    // Poblado inteligente del título/descripcion:
    (function(){
      const elNombre = document.getElementById('nombre-actividad');
      const elDesc = document.getElementById('descripcion-actividad');

      // 1) valores server-side si llegaron
      const nombreServer = {!! json_encode($actividad->nombre ?? $actividad->nombre_actividad ?? null) !!};
      const descServer = {!! json_encode($actividad->descripcion ?? null) !!};
      if (nombreServer) elNombre.textContent = nombreServer;
      if (descServer) elDesc.textContent = descServer;

      // 2) parámetros URL (si el servidor no envió datos)
      const params = new URLSearchParams(window.location.search);
      if (!nombreServer) {
        const n = params.get('nombre');
        if (n) elNombre.textContent = n;
      }
      if (!descServer) {
        const d = params.get('descripcion');
        if (d) elDesc.textContent = d;
      }

      // 3) intentar fetch si aún no hay datos y hay endpoint + id
      const id = window.DActividadesVE && window.DActividadesVE.id_actividad;
      const ep = window.DActividadesVE && window.DActividadesVE.endpoints && window.DActividadesVE.endpoints.obtenerActividad;
      if ((!nombreServer || !descServer) && id && ep) {
        fetch(ep + '?id_actividad=' + encodeURIComponent(id))
          .then(r => r.ok ? r.json() : Promise.reject())
          .then(act => {
            if (!act) return;
            if (!nombreServer && (act.nombre_actividad || act.nombre)) elNombre.textContent = act.nombre_actividad || act.nombre;
            if (!descServer && act.descripcion) elDesc.textContent = act.descripcion;
          })
          .catch(()=>{/* silencioso */});
      }
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/D_actividades.js') }}"></script>
</body>
</html>