<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Semestres Cursados - ITVE</title>
  <link rel="stylesheet" href="{{ asset('css/Administrador/Semestres-cursados.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <div class="header">
    <div class="header-logos">
      <img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="Logo Educación">
      <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Logo TecNM">
      <img src="{{ asset('Imagenes/Logo IT Valle de etla.png') }}" alt="Logo ITVE">
    </div>
    <div class="header-user" style="position: relative;">
      <i class="fas fa-user-circle" id="iconoPerfil" style="cursor: pointer;"></i>

      <!-- Menú desplegable del perfil (oculto por defecto) -->
      <div id="perfilDropdown" style="display:none; position: absolute; right: 0; top: 48px; background: white; border: 1px solid #e5e5e5; box-shadow: 0 6px 18px rgba(0,0,0,0.08); border-radius: 6px; min-width:200px; z-index:2000;">
        <div style="padding:12px 14px; border-bottom:1px solid #f0f0f0;">
          <strong>{{ $user->nombre ?? 'Usuario' }}</strong>
          <div style="font-size:13px; color:#666;">{{ $user->contacto ?? $user->unidad_academica ?? '' }}</div>
        </div>
        <div style="padding:8px 10px;">
          <button id="btnCerrarSesion" style="width:100%; background:#dc3545; color:#fff; border:none; padding:8px 10px; border-radius:4px; cursor:pointer;">Cerrar sesión</button>
        </div>
      </div>

      <!-- Formulario de logout oculto -->
      <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>
  </div>

  <div class="title-bar">
    <h1> Gestión de Semestres Cursados - Administrador</h1>
    <p>Sistema de Actividades Extraescolares - ITVE</p>
  </div>

  <div class="main-container">
    <div class="semestres-container">
      <div class="semestres-header">
        <h2>Historial de Semestres</h2>
        <p class="descripcion-seccion">
          En esta sección puede administrar los diferentes períodos académicos del Instituto Tecnológico del Valle de Etla.
        </p>
        <p class="descripcion-seccion">
          Gestione los semestres cursados, registre nuevos períodos y consulte la información histórica de actividades extraescolares.
        </p>
      </div>

      <div class="crear-semestre">
        <button id="btnCrearSemestre">
          <i class="fas fa-plus-circle"></i>
          Crear Nuevo Semestre
        </button>
      </div>

      <div class="semestres-grid" id="contenedorSemestres">
        <!-- Las tarjetas de semestres se cargan desde la base de datos -->
        @forelse($semestres as $semestre)
          <div class="semestre-card" data-id="{{ $semestre->id_semestre }}" style="cursor: pointer;">
            <div class="semestre-header">
              <span class="semestre-periodo">{{ $semestre->nombre }}</span>
              <div class="semestre-header-actions">
                @if(isset($semestre->estatus) && $semestre->estatus == 1)
                  <span class="semestre-estado activo">Activo</span>
                @else
                  <form method="POST" action="{{ route('admin.semestres.activar', ['id' => $semestre->id_semestre]) }}" style="display:inline;">
                    @csrf
                    <button class="btn-activar" type="submit"><i class="fas fa-toggle-off"></i> Activar</button>
                  </form>
                @endif
              </div>
            </div>
            <div class="semestre-body">
              <div class="semestre-info">
                <p><i class="fas fa-calendar-alt"></i> <strong>Fecha inicio:</strong> {{ date('d/m/Y', strtotime($semestre->fecha_inicio)) }}</p>
                <p><i class="fas fa-calendar-check"></i> <strong>Fecha fin:</strong> {{ date('d/m/Y', strtotime($semestre->fecha_fin)) }}</p>
                @php
// Actividades de este semestre
$actividades_count = \Illuminate\Support\Facades\DB::table('actividades')
    ->where('id_semestre', $semestre->id_semestre)
    ->count();

// Estudiantes de este semestre (a través de actividades)
$estudiantes_count = 0;
if ($actividades_count > 0) {
    $actividad_ids = \Illuminate\Support\Facades\DB::table('actividades')
        ->where('id_semestre', $semestre->id_semestre)
        ->pluck('id_actividad')
        ->toArray();
    
    $estudiantes_count = \Illuminate\Support\Facades\DB::table('estudiantes')
        ->whereIn('id_actividad', $actividad_ids)
        ->count();
}
@endphp

<p><i class="fas fa-users"></i> <strong>Alumnos: </strong> {{ $estudiantes_count }}</p>
<p><i class="fas fa-clipboard-list"></i> <strong>Actividades:</strong> {{ $actividades_count }}</p>
              </div>
            </div>
            <div class="semestre-footer">
              <button class="btn-semestre btn-editar"><i class="fas fa-edit"></i> Editar</button>
              <button class="btn-semestre btn-eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>
            </div>
          </div>
        @empty
          <div class="semestres-empty-card">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            <h3>No hay semestres registrados aún</h3>
            <p>Usa "Crear Nuevo Semestre" para agregar uno.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <div id="modalSemestre" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" id="cerrarModal">&times;</span>
      <h2 id="tituloModal">Crear Nuevo Semestre</h2>
      
      <form id="formSemestre">
        <input type="hidden" id="semestreId">
        
        <div class="form-group">
          <label for="nombreSemestre">Nombre del Período:</label>
          <input type="text" id="nombreSemestre" placeholder="Ej: Agosto - Diciembre 2025" required>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="fechaInicio">Fecha de Inicio:</label>
            <input type="date" id="fechaInicio" required>
          </div>
          
          <div class="form-group">
            <label for="fechaFin">Fecha de Fin:</label>
            <input type="date" id="fechaFin" required>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" id="btnCancelar" class="btn-cancelar">Cancelar</button>
          <button type="submit" id="btnGuardarSemestre" class="btn-guardar">Guardar Semestre</button>
        </div>
      </form>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-content">
      <p>&copy; 2025 Instituto Tecnológico del Valle de Etla. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

      // Cerrar al hacer clic fuera
      document.addEventListener('click', function (e) {
        if (!dropdown) return;
        const target = e.target;
        if (target === dropdown || dropdown.contains(target) || target === icon) return;
        hideDropdown();
      });

      // Enviar formulario de logout
      btnCerrar && btnCerrar.addEventListener('click', function (e) {
        e.preventDefault();
        if (!logoutForm) return window.location.href = '/';
        logoutForm.submit();
      });
    })();
  </script>
  <script>
    (function(){
      const btnCrear = document.getElementById('btnCrearSemestre');
      const modal = document.getElementById('modalSemestre');
      const cerrar = document.getElementById('cerrarModal');
      const btnCancelar = document.getElementById('btnCancelar');
      const form = document.getElementById('formSemestre');
      const contenedor = document.getElementById('contenedorSemestres');
      const semestreIdInput = document.getElementById('semestreId');

      function abrirModal(forEdit = false){
        if(!modal) return;
        document.getElementById('tituloModal').textContent = forEdit ? 'Editar Semestre' : 'Crear Nuevo Semestre';
        if(!forEdit) form.reset();
        modal.style.display = 'block';
      }

      function cerrarModal(){
        if(!modal) return;
        modal.style.display = 'none';
        semestreIdInput.value = '';
      }

      btnCrear && btnCrear.addEventListener('click', function(e){
        e.preventDefault();
        abrirModal(false);
      });

      cerrar && cerrar.addEventListener('click', function(){ cerrarModal(); });
      btnCancelar && btnCancelar.addEventListener('click', function(e){ e.preventDefault(); cerrarModal(); });

      // Cerrar al hacer clic fuera del contenido
      window.addEventListener('click', function(e){
        if(e.target === modal) cerrarModal();
      });

      // Helper: convertir dd/mm/yyyy -> yyyy-mm-dd
      function dmyToIso(dmy){
        if(!dmy) return '';
        const parts = dmy.split('/');
        if(parts.length !== 3) return '';
        const [d, m, y] = parts;
        return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
      }

      // Delegación de eventos para editar / eliminar
      contenedor && contenedor.addEventListener('click', function(e){
        const editarBtn = e.target.closest('.btn-editar');
        const eliminarBtn = e.target.closest('.btn-eliminar');
        if(editarBtn){
          const card = editarBtn.closest('.semestre-card');
          if(!card) return;
          const id = card.getAttribute('data-id');
          // Obtener datos desde el DOM
          const nombre = card.querySelector('.semestre-periodo')?.textContent?.trim() || '';
          const pElems = card.querySelectorAll('.semestre-info p');
          let fechaInicioText = '';
          let fechaFinText = '';
          pElems.forEach(p => {
            const txt = p.textContent || '';
            if(txt.includes('Fecha inicio')) fechaInicioText = txt.replace(/.*Fecha inicio:\s*/,'').trim();
            if(txt.includes('Fecha fin')) fechaFinText = txt.replace(/.*Fecha fin:\s*/,'').trim();
          });

          // Rellenar formulario (convertir a ISO yyyy-mm-dd)
          document.getElementById('nombreSemestre').value = nombre;
          document.getElementById('fechaInicio').value = dmyToIso(fechaInicioText);
          document.getElementById('fechaFin').value = dmyToIso(fechaFinText);
          semestreIdInput.value = id;
          abrirModal(true);
          return;
        }

        if(eliminarBtn){
          const card = eliminarBtn.closest('.semestre-card');
          if(!card) return;
          const id = card.getAttribute('data-id');
          Swal.fire({
            title: '¿Eliminar semestre?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then(result => {
            if(result.isConfirmed){
              const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
              fetch(`/admin/semestres/${id}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': token,
                  'Accept': 'application/json'
                }
              })
              .then(async res => {
                const json = await res.json().catch(()=>({}));
                if(res.ok && json.success){
                  Swal.fire({
                    title: 'Eliminado',
                    text: 'Semestre eliminado correctamente',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                  });
                  card.remove();
                } else {
                  const msg = json.message || 'Error al eliminar semestre';
                  Swal.fire('Error', msg, 'error');
                }
              })
              .catch(err => {
                console.error(err);
                Swal.fire('Error','No se pudo conectar al servidor','error');
              });
            }
          });
        }
        // Si se hizo clic en la tarjeta (pero no en botones de acción), abrir el panel principal filtrado por este semestre
        const cardClick = e.target.closest('.semestre-card');
        if(!editarBtn && !eliminarBtn && cardClick){
          const id = cardClick.getAttribute('data-id');
          if(id){
            window.location.href = `/admin/principal/${id}`;
            return;
          }
        }
      });

      // Enviar formulario via fetch (create o update según hidden semestreId)
      form && form.addEventListener('submit', function(e){
        e.preventDefault();
        const nombre = document.getElementById('nombreSemestre').value.trim();
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        const id = semestreIdInput.value;

        if(!nombre || !fechaInicio || !fechaFin){
          Swal.fire('Error','Complete todos los campos requeridos','warning');
          return;
        }

        const payload = { nombre: nombre, fecha_inicio: fechaInicio, fecha_fin: fechaFin };
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const isEdit = !!id;
        const url = isEdit ? `/admin/semestres/${id}` : "{{ route('admin.semestres.store') }}";
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        })
        .then(async res => {
          const json = await res.json().catch(()=>({}));
          if(res.ok && json.success){
            if(isEdit){
              Swal.fire({
                title: 'Actualizado',
                text: 'Semestre actualizado correctamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
              });
              // actualizar tarjeta existente
              const s = json.semestre;
              const card = contenedor.querySelector(`.semestre-card[data-id="${s.id_semestre}"]`);
              if(card){
                card.querySelector('.semestre-periodo').textContent = s.nombre;
                const formato = d => ('0'+d.getDate()).slice(-2) + '/' + ('0'+(d.getMonth()+1)).slice(-2) + '/' + d.getFullYear();
                const inicio = new Date(s.fecha_inicio);
                const fin = new Date(s.fecha_fin);
                const pElems = card.querySelectorAll('.semestre-info p');
                // reemplazar los dos primeros p que contienen fechas
                if(pElems[0]) pElems[0].innerHTML = `<i class="fas fa-calendar-alt"></i> <strong>Fecha inicio:</strong> ${formato(inicio)}`;
                if(pElems[1]) pElems[1].innerHTML = `<i class="fas fa-calendar-check"></i> <strong>Fecha fin:</strong> ${formato(fin)}`;
              }
            } else {
              Swal.fire({
                title: 'Guardado',
                text: 'Semestre creado correctamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
              });
              // Añadir tarjeta al DOM
              const s = json.semestre;
              const card = document.createElement('div');
              card.className = 'semestre-card';
              card.setAttribute('data-id', s.id_semestre);
              card.style.cursor = 'pointer';
              const inicio = new Date(s.fecha_inicio);
              const fin = new Date(s.fecha_fin);
              const formato = d => ('0'+d.getDate()).slice(-2) + '/' + ('0'+(d.getMonth()+1)).slice(-2) + '/' + d.getFullYear();

              // Construir la parte de acciones del header según estatus devuelto por la API
              let headerActionsHtml = '';
              if(s.estatus == 1 || s.estatus === true){
                headerActionsHtml = `<span class="semestre-estado activo">Activo</span>`;
              } else {
                headerActionsHtml = `
                  <form method="POST" action="/admin/semestres/${s.id_semestre}/activar" style="display:inline;">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <button class="btn-activar" type="submit"><i class="fas fa-toggle-off"></i> Activar</button>
                  </form>
                `;
              }

              card.innerHTML = `
                <div class="semestre-header">
                  <span class="semestre-periodo">${s.nombre}</span>
                  <div class="semestre-header-actions">${headerActionsHtml}</div>
                </div>
                <div class="semestre-body">
                  <div class="semestre-info">
                    <p><i class="fas fa-calendar-alt"></i> <strong>Fecha inicio:</strong> ${formato(inicio)}</p>
                    <p><i class="fas fa-calendar-check"></i> <strong>Fecha fin:</strong> ${formato(fin)}</p>
                    <p><i class="fas fa-users"></i> <strong>Alumnos:</strong> 0</p>
                    <p><i class="fas fa-clipboard-list"></i> <strong>Actividades:</strong> 0</p>
                  </div>
                </div>
                <div class="semestre-footer">
                  <button class="btn-semestre btn-editar"><i class="fas fa-edit"></i> Editar</button>
                  <button class="btn-semestre btn-eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>
                </div>
              `;
              contenedor && contenedor.insertBefore(card, contenedor.firstChild);
            }
            cerrarModal();
          } else {
            const msg = (json && json.message) ? json.message : (json.errors ? Object.values(json.errors).flat().join('\n') : 'Error al procesar');
            Swal.fire('Error', msg, 'error');
          }
        })
        .catch(err => {
          console.error(err);
          Swal.fire('Error','No se pudo conectar al servidor','error');
        });
      });
    })();
  </script>
</body>
</html>
