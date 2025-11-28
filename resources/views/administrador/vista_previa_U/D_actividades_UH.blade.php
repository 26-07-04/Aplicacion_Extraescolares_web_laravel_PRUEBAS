<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de Actividad Extraescolar - {{ $actividad->nombre ?? 'Detalle' }}</title>

  <!-- CSRF token requerido por fetch -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Bootstrap Icons (necesario para las clases bi bi-*) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/Administrador/D_actividades.css') }}">
</head>
<body>
    <div class="wrapper">
      <!-- Botón regresar (flecha) igual que en actividadesUH -->
      <a href="{{ url()->previous() }}"
         onclick="event.preventDefault(); if (history.length > 1) history.back(); else window.location.href='{{ route('admin.semestres') }}';"
         class="btn-flecha-back" title="Regresar" aria-label="Regresar">
        <svg viewBox="0 0 24 24" class="icon-flecha" aria-hidden="true" focusable="false" role="img">
          <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
        </svg>
      </a>

      <!-- Logo fijo -->
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
                 <!-- filas cargadas dinámicamente por JS -->
               </tbody>
             </table>
           </div>
        </div>
      </main>

      <!-- Modal para editar/agregar estudiante -->
      <div id="modal-editar" class="modal" aria-hidden="true" style="display:none;">
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

      <!-- Footer (restaurado al diseño original) -->
      <footer class="site-footer">
        <div class="footer-container">
          <!-- Información de contacto -->
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
          
          <!-- Mapa -->
          <div class="footer-map">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15241.367048623282!2d-96.8702191!3d17.2506929!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85c6e36d891967db%3A0x512f566ad941ae57!2sTecnol%C3%B3gico%20Nacional%20de%20M%C3%A9xico%20campus%20Instituto%20Tecnol%C3%B3gico%20del%20Valle%20de%20Etla!5e0!3m2!1ses!2smx!4v1621983062705!5m2!1ses!2smx"
              allowfullscreen>
            </iframe>
          </div>
        </div>
        
        <!-- Derechos reservados -->
        <div class="footer-copyright">
          <p>© {{ date('Y') }} Tecnológico Nacional de México - Campus Valle de Etla. Todos los derechos reservados.</p>
        </div>
      </footer>
    </div>

    <!-- Configuración de endpoints para el JS (ajustada a rutas Laravel) -->
    <script>
      window.DActividadesUH = {
        // id pasado por query o desde servidor
        id_actividad: "{{ request()->query('id_actividad') ?? ($actividad->id ?? '') }}",
        endpoints: {
          obtenerActividad: "{{ url('administrador/actividades') }}",
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
        const idFromQuery = params.get('id_actividad') || (window.DActividadesUH && window.DActividadesUH.id_actividad) || '';
        if (!nombreServer) {
          const n = params.get('nombre');
          if (n) elNombre.textContent = n;
        }
        if (!descServer) {
          const d = params.get('descripcion');
          if (d) elDesc.textContent = d;
        }

        // 3) si aún no hay datos, pedir al endpoint REST /administrador/actividades/{id}
        const id = idFromQuery;
        const epBase = window.DActividadesUH && window.DActividadesUH.endpoints && window.DActividadesUH.endpoints.obtenerActividad;
        if ((!nombreServer || !descServer) && id && epBase) {
          const url = epBase.replace(/\/+$/,'') + '/' + encodeURIComponent(id);
          fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(r => { if (!r.ok) return Promise.reject(r); return r.json(); })
            .then(act => {
              if (!act) return;
              // el controller puede devolver la actividad dentro de 'data' o directamente
              const actividad = act.data ?? act;
              if (!nombreServer && (actividad.nombre_actividad || actividad.nombre)) {
                elNombre.textContent = actividad.nombre_actividad || actividad.nombre;
              }
              if (!descServer && actividad.descripcion) {
                elDesc.textContent = actividad.descripcion;
              }
            })
            .catch(()=>{});
        }
      })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/D_actividades.js') }}"></script>

    <script>
(function(){
  const actividadId = "{{ request()->query('id_actividad') ?? ($actividad->id ?? $actividad->id_actividad ?? '') }}";
  if (!actividadId) return;
  const tabla = document.getElementById('tabla-estudiantes');

  async function cargarEstudiantes(){
    tabla.innerHTML = '<tr><td colspan="5">Cargando...</td></tr>';
    try {
      const res = await fetch(`/administrador/actividades/${encodeURIComponent(actividadId)}/estudiantes`, { credentials:'same-origin', headers:{'Accept':'application/json'}});
      if (!res.ok) throw new Error('Error al obtener estudiantes');
      const items = await res.json();
      if (!items || items.length === 0) {
        
        return;
      }
      tabla.innerHTML = '';
      items.forEach(e => {
        const tr = document.createElement('tr');
        tr.dataset.id = e.id_alumno;
        tr.innerHTML = `
          <td>${escapeHtml(e.nombre ?? '')}</td>
          <td>${escapeHtml(e.numero_control ?? '')}</td>
          <td>${escapeHtml(e.carrera ?? '')}</td>
          <td>${escapeHtml(e.semestre ?? '')}</td>
          <td class="acciones-celda">
            <button class="btn-accion btn-editar" title="Editar" data-id="${e.id_alumno}" data-nombre="${escapeAttr(e.nombre)}" data-numero_control="${escapeAttr(e.numero_control)}" data-carrera="${escapeAttr(e.carrera)}" data-semestre="${escapeAttr(e.semestre)}">
              <i class="bi bi-pen-fill" style="color:#002147; font-size:1.2em;"></i>
            </button>
            <button class="btn-accion btn-eliminar" title="Eliminar" data-id="${e.id_alumno}">
              <i class="bi bi-trash-fill" style="color:#002147; font-size:1.2em;"></i>
            </button>
          </td>
        `;
        tabla.appendChild(tr);
      });
    } catch (err) {
      console.error(err);
      tabla.innerHTML = '<tr><td colspan="5">Error cargando estudiantes</td></tr>';
    }
  }

  function escapeHtml(s){ return String(s).replace(/[&<>"]/g, c=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' }[c])); }
  function escapeAttr(s){ return String(s ?? '').replace(/"/g,'&quot;'); }

  // eliminar por delegación (envía id_actividad como query para validación)
  document.addEventListener('click', async (ev) => {
    const btnEliminar = ev.target.closest && ev.target.closest('.btn-eliminar');
    if (btnEliminar) {
      const idEst = btnEliminar.getAttribute('data-id');
      if (!idEst) return;
      const confirmed = await Swal.fire({
        title: 'Confirmar',
        text: '¿Eliminar estudiante?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
      });
      if (!confirmed.isConfirmed) return;
      try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const url = `/administrador/actividades/estudiantes/${encodeURIComponent(idEst)}?id_actividad=${encodeURIComponent(actividadId)}`;
        const res = await fetch(url, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
          credentials: 'same-origin'
        });
        const text = await res.text().catch(()=>null);
        if (!res.ok) {
          console.error('Delete response', res.status, text);
          if (res.status === 403) return Swal.fire('Error','Operación no permitida','error');
          if (res.status === 404) return Swal.fire('Error','Estudiante no encontrado','error');
          return Swal.fire('Error','No se pudo eliminar','error');
        }
        await cargarEstudiantes();
        // Toast de éxito (eliminación) - centrado
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Eliminado correctamente",
          showConfirmButton: false,
          timer: 1500
        });
      } catch (e) {
        console.error(e);
        Swal.fire('Error','No se pudo eliminar','error');
      }
    }
  });

  // abrir modal editar (delegación)
  document.addEventListener('click', (ev) => {
    const btnEditar = ev.target.closest && ev.target.closest('.btn-editar');
    if (!btnEditar) return;
    const idEst = btnEditar.getAttribute('data-id');
    document.getElementById('editar-id').value = idEst || '';
    document.getElementById('editar-nombre').value = btnEditar.getAttribute('data-nombre') || '';
    document.getElementById('editar-numero-control').value = btnEditar.getAttribute('data-numero_control') || '';
    document.getElementById('editar-carrera').value = btnEditar.getAttribute('data-carrera') || '';
    document.getElementById('editar-semestre').value = btnEditar.getAttribute('data-semestre') || '';
    document.getElementById('modal-editar').style.display = 'flex';
  });

  // submit editar -> actualiza en la base (envía id_actividad para validar)
  document.getElementById('form-editar-estudiante').addEventListener('submit', async function(e){
    e.preventDefault();
    const idEst = document.getElementById('editar-id').value;
    if (!idEst) return Swal.fire({ position: 'center', icon: 'error', title: 'ID de estudiante no encontrado' });

    const payload = new FormData();
    payload.append('nombre', document.getElementById('editar-nombre').value.trim());
    payload.append('numero_control', document.getElementById('editar-numero-control').value.trim());
    payload.append('carrera', document.getElementById('editar-carrera').value.trim());
    payload.append('semestre', document.getElementById('editar-semestre').value);
    payload.append('_method','PUT');
    payload.append('id_actividad', actividadId); // importante para validación en controlador

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    try {
      const res = await fetch(`/administrador/actividades/estudiantes/${encodeURIComponent(idEst)}`, {
        method: 'POST',
        headers: csrf ? { 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' } : { 'Accept':'application/json' },
        body: payload,
        credentials: 'same-origin'
      });

      // parseo seguro del body (intentar json, si falla obtener texto)
      let data = null;
      const ct = res.headers.get('content-type') || '';
      if (ct.includes('application/json')) {
        data = await res.json().catch(()=>null);
      } else {
        const text = await res.text().catch(()=>null);
        try { data = text ? JSON.parse(text) : null; } catch {_=>{ data = { message: text }; } }
      }

      if (res.ok) {
        await cargarEstudiantes();
        document.getElementById('modal-editar').style.display = 'none';
      }

      // manejo de errores según status
      if (res.status === 422) {
        const msgs = data?.errors ? Object.values(data.errors).flat().join('\n') : (data?.message || 'Error de validación');
        return Swal.fire({ position: 'center', icon: 'error', title: 'Validación', text: msgs });
      }
      if (res.status === 403) return Swal.fire({ position: 'center', icon: 'error', title: 'Operación no permitida', text: data?.message || '' });
      if (res.status === 404) return Swal.fire({ position: 'center', icon: 'error', title: 'Estudiante no encontrado' });
    } catch (err) {
      console.error('Fetch error update:', err);
      return Swal.fire({ position: 'center', icon: 'error', title: 'Error de conexión', text: 'No se pudo conectar al servidor' });
    }
  });

  document.addEventListener('DOMContentLoaded', cargarEstudiantes);
})();
</script>
  </body>
</html>