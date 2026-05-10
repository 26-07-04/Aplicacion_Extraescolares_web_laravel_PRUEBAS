<!-- Partial: Tabla de estudiantes (estática, sin JS) -->
<div class="tabla-container" style="margin:20px 0;">
  <div class="tabla-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
    <h2 style="margin:0;">Estudiantes — actividades complementarias</h2>
    <div class="search-container" style="display:flex; align-items:center; gap:8px;">
      <input type="text" id="buscador-nombre" placeholder="Buscar por nombre..." class="buscador" style="padding:8px; border-radius:6px; border:1px solid #ddd;">
      <button id="btn-buscar-nombre" class="btn-buscar" title="Buscar" style="padding:8px 10px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer;">
        <i class="fas fa-search"></i>
      </button>
      <button id="btn-recargar" class="btn-recargar" title="Recargar tabla" style="padding:8px 10px; border-radius:6px; background:#ff7f00; color:#fff; border:none; cursor:pointer;">
        <i class="fas fa-sync-alt"></i>
      </button>
    </div>
  </div>

  <div style="overflow:auto; background:#fff; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06);">
    <table id="dataTable" class="display nowrap" style="width:100%; border-collapse:collapse; min-width:780px;">
      <thead style="background:#f6f8fb;">
        <tr>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">No. Control</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Nombre</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Carrera</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Sexo</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Complementaria</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Semestre</th>
          <th style="text-align:center; padding:12px 16px; border-bottom:1px solid #eee;">Acciones</th>
        </tr>
      </thead>
      <tbody id="estudiantesTableBody">
        @php
          // Traer todos los estudiantes de las actividades del semestre y unidad
          $allEstudiantes = \App\Models\Estudiante::whereIn('estudiantes.id_actividad', 
            $actividades->pluck('id_actividad')->toArray()
          )
          ->join('actividades', 'estudiantes.id_actividad', '=', 'actividades.id_actividad')
          ->select('estudiantes.*', 'actividades.nombre_actividad')
          ->orderBy('estudiantes.nombre', 'asc')
          ->get();

          // Paginar de 50 en 50
          $estudiantesPorPagina = 50;
          $totalEstudiantes = $allEstudiantes->count();
          $totalPaginas = ceil($totalEstudiantes / $estudiantesPorPagina);
          $paginaActual = 1;
          
          // Estudiantes de la primera página
          $estudiantes = $allEstudiantes->slice(0, $estudiantesPorPagina);
        @endphp

        @if($allEstudiantes && $allEstudiantes->count() > 0)
          @foreach($estudiantes as $estudiante)
            <tr class="estudiante-row" data-index="{{ $loop->index }}">
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">{{ $estudiante->numero_control ?? '—' }}</td>
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">{{ $estudiante->nombre ?? '—' }}</td>
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">{{ $estudiante->carrera ?? '—' }}</td>
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">{{ $estudiante->sexo ?? '—' }}</td>
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">{{ $estudiante->nombre_actividad ?? '—' }}</td>
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">{{ $estudiante->semestre ?? '—' }}</td>
              <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; text-align:center; white-space:nowrap;">
                <button class="btn-editar" data-id="{{ $estudiante->id_alumno }}" data-nombre="{{ $estudiante->nombre }}" data-numero="{{ $estudiante->numero_control }}" data-carrera="{{ $estudiante->carrera }}" data-sexo="{{ $estudiante->sexo }}" data-semestre="{{ $estudiante->semestre }}" style="display:inline-block; margin-right:12px; background:none; border:none; color:#ff7f00; cursor:pointer; font-size:16px; vertical-align:middle;" title="Editar"><i class="fas fa-edit"></i></button>
                <button class="btn-eliminar" data-id="{{ $estudiante->id_alumno }}" data-nombre="{{ $estudiante->nombre }}" style="display:inline-block; background:none; border:none; color:#dc3545; cursor:pointer; font-size:16px; vertical-align:middle;" title="Eliminar"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="7" style="padding:20px 16px; text-align:center; color:#999; border-bottom:1px solid #f2f2f2;">
              No hay estudiantes registrados para este semestre y unidad académica.
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <!-- Controles de paginación -->
  @php
    $totalEstudiantes = $allEstudiantes->count();
    $estudiantesPorPagina = 50;
    $totalPaginas = ceil($totalEstudiantes / $estudiantesPorPagina);
  @endphp

  <div class="pagination-container" style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:16px; flex-wrap:wrap;">
    <button id="btnPaginaAnterior" class="btn-paginacion" style="padding:8px 12px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer; display:none;">
      <i class="fas fa-chevron-left"></i> Anterior
    </button>
    
    <div id="paginasContainer" style="display:flex; gap:4px;">
      @for($i = 1; $i <= min($totalPaginas, 5); $i++)
        <button class="btn-pagina" data-pagina="{{ $i }}" style="padding:8px 12px; border-radius:6px; background:{{ $i === 1 ? '#1B396A' : '#e0e0e0' }}; color:{{ $i === 1 ? '#fff' : '#333' }}; border:none; cursor:pointer; font-weight:{{ $i === 1 ? '700' : '400' }};">
          {{ $i }}
        </button>
      @endfor
      @if($totalPaginas > 5)
        <span style="padding:8px 12px; color:#999;">...</span>
        <button class="btn-pagina" data-pagina="{{ $totalPaginas }}" style="padding:8px 12px; border-radius:6px; background:#e0e0e0; color:#333; border:none; cursor:pointer;">
          {{ $totalPaginas }}
        </button>
      @endif
    </div>
    
    <button id="btnPaginaSiguiente" class="btn-paginacion" style="padding:8px 12px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer;">
      Siguiente <i class="fas fa-chevron-right"></i>
    </button>
    
    <span style="margin-left:16px; font-size:13px; color:#666;">
      Página <span id="numeroPagina">1</span> de {{ $totalPaginas }} ({{ $totalEstudiantes }} estudiantes)
    </span>
  </div>
</div>

<!-- Script para paginación -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    (function(){
      const allEstudiantes = {!! json_encode($allEstudiantes->toArray()) !!};
      const estudiantesPorPagina = 50;
      const totalPaginas = {{ $totalPaginas }};  // Usar el valor del servidor
      let paginaActual = 1;

      const tableBody = document.getElementById('estudiantesTableBody');
      const btnAnterior = document.getElementById('btnPaginaAnterior');
      const btnSiguiente = document.getElementById('btnPaginaSiguiente');
      const numeroPagina = document.getElementById('numeroPagina');
      const botonesPagena = document.querySelectorAll('.btn-pagina');

      console.log('btnSiguiente:', btnSiguiente);
      console.log('btnAnterior:', btnAnterior);
      console.log('totalPaginas:', totalPaginas);
      console.log('allEstudiantes.length:', allEstudiantes.length);

    // Event listeners para botones de acciones (se define primero para usarse en renderizarPagina)
    function attachEventListeners() {
      // Botones editar
      document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModalEditar(this);
        });
      });

      // Botones eliminar
      document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const id = this.dataset.id;
          const nombre = this.dataset.nombre;

          // Mostrar alerta de confirmación directamente
          Swal.fire({
            title: '¿Estás seguro?',
            text: `No podrás revertir la eliminación de ${nombre}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff7f00',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              // Proceder con la eliminación
              const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

              fetch(`/coordinador/valle-de-etla/estudiantes/${id}/eliminar`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken,
                  'Accept': 'application/json'
                }
              }).then(r => r.json()).then(json => {
                if (json.success) {
                  // Mostrar alerta de éxito
                  Swal.fire({
                    title: '¡Eliminado!',
                    text: 'Estudiante eliminado exitosamente',
                    icon: 'success'
                  });
                  setTimeout(() => {
                    location.reload(); // Recargar página
                  }, 1500);
                } else {
                  Swal.fire({
                    title: 'Error',
                    text: json.message || 'Error desconocido',
                    icon: 'error'
                  });
                }
              }).catch(err => {
                console.error(err);
                Swal.fire({
                  title: 'Error',
                  text: 'Error al comunicarse con el servidor',
                  icon: 'error'
                });
              });
            }
          });
        });
      });
    }

    // Función para abrir modal de editar
    function abrirModalEditar(btn) {
      const id = btn.dataset.id;
      const nombre = btn.dataset.nombre;
      const numero = btn.dataset.numero;
      const carrera = btn.dataset.carrera;
      const sexo = btn.dataset.sexo || '';
      const semestre = btn.dataset.semestre;

      document.getElementById('editId').value = id;
      document.getElementById('editNombre').value = nombre;
      document.getElementById('editNumero').value = numero;
      document.getElementById('editCarrera').value = carrera;
      document.getElementById('editSexo').value = sexo;
      document.getElementById('editSemestre').value = semestre;
      document.getElementById('modalEditar').style.display = 'flex';
    }

    function renderizarPagina(pagina) {
      if (pagina < 1 || pagina > totalPaginas) return;
      
      paginaActual = pagina;
      const inicio = (pagina - 1) * estudiantesPorPagina;
      const fin = inicio + estudiantesPorPagina;
      const estudiantesEnPagina = allEstudiantes.slice(inicio, fin);

      // Limpiar tbody
      tableBody.innerHTML = '';

      // Renderizar estudiantes
      estudiantesEnPagina.forEach((est, idx) => {
        const tr = document.createElement('tr');
        tr.className = 'estudiante-row';
        tr.innerHTML = `
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.numero_control || '—'}</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.nombre || '—'}</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.carrera || '—'}</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.sexo || '—'}</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.nombre_actividad || '—'}</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.semestre || '—'}</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; text-align:center; white-space:nowrap;">
            <button class="btn-editar" data-id="${est.id_alumno}" data-nombre="${est.nombre}" data-numero="${est.numero_control}" data-carrera="${est.carrera}" data-sexo="${est.sexo || ''}" data-semestre="${est.semestre}" style="display:inline-block; margin-right:12px; background:none; border:none; color:#ff7f00; cursor:pointer; font-size:16px; vertical-align:middle;" title="Editar"><i class="fas fa-edit"></i></button>
            <button class="btn-eliminar" data-id="${est.id_alumno}" data-nombre="${est.nombre}" style="display:inline-block; background:none; border:none; color:#dc3545; cursor:pointer; font-size:16px; vertical-align:middle;" title="Eliminar"><i class="fas fa-trash"></i></button>
          </td>
        `;
        tableBody.appendChild(tr);
      });

      // Actualizar número de página
      numeroPagina.textContent = pagina;

      // Actualizar botones de páginas
      botonesPagena.forEach(btn => {
        const btnPagina = parseInt(btn.dataset.pagina);
        if (btnPagina === pagina) {
          btn.style.background = '#1B396A';
          btn.style.color = '#fff';
          btn.style.fontWeight = '700';
        } else {
          btn.style.background = '#e0e0e0';
          btn.style.color = '#333';
          btn.style.fontWeight = '400';
        }
      });

      // Habilitar/Deshabilitar botones de navegación
      btnAnterior.style.display = pagina === 1 ? 'none' : 'inline-block';
      btnSiguiente.style.display = pagina === totalPaginas ? 'none' : 'inline-block';
      
      // Agregar event listeners a los botones de acciones
      attachEventListeners();
    }

    // Event listeners
    if (btnAnterior) {
      btnAnterior.addEventListener('click', () => {
        console.log('Click anterior:', paginaActual - 1, 'totalPaginas:', totalPaginas);
        renderizarPagina(paginaActual - 1);
      });
    }

    if (btnSiguiente) {
      btnSiguiente.addEventListener('click', () => {
        console.log('Click siguiente:', paginaActual + 1, 'totalPaginas:', totalPaginas);
        renderizarPagina(paginaActual + 1);
      });
    }

    botonesPagena.forEach(btn => {
      btn.addEventListener('click', () => {
        const pagina = parseInt(btn.dataset.pagina);
        renderizarPagina(pagina);
      });
    });

    // Renderizar página inicial
    renderizarPagina(1);

    // Funcionalidad de búsqueda
    const buscadorNombre = document.getElementById('buscador-nombre');
    const btnBuscar = document.getElementById('btn-buscar-nombre');
    const btnRecargar = document.getElementById('btn-recargar');
    let estudiantesFiltrados = [...allEstudiantes];

    function buscar() {
      const termino = buscadorNombre.value.toLowerCase();
      
      if (termino.trim() === '') {
        estudiantesFiltrados = [...allEstudiantes];
      } else {
        estudiantesFiltrados = allEstudiantes.filter(est => 
          est.nombre.toLowerCase().includes(termino) ||
          est.numero_control.toLowerCase().includes(termino)
        );
      }
      
      // Recalcular paginación con resultados filtrados
      const totalPaginasFiltradas = Math.ceil(estudiantesFiltrados.length / estudiantesPorPagina);
      
      // Actualizar botones de página
      const paginasContainer = document.getElementById('paginasContainer');
      paginasContainer.innerHTML = '';
      
      for (let i = 1; i <= Math.min(totalPaginasFiltradas, 5); i++) {
        const btn = document.createElement('button');
        btn.className = 'btn-pagina';
        btn.dataset.pagina = i;
        btn.textContent = i;
        btn.style.cssText = `padding:8px 12px; border-radius:6px; background:${i === 1 ? '#1B396A' : '#e0e0e0'}; color:${i === 1 ? '#fff' : '#333'}; border:none; cursor:pointer; font-weight:${i === 1 ? '700' : '400'};`;
        paginasContainer.appendChild(btn);
        
        btn.addEventListener('click', () => {
          renderizarBusqueda(i);
        });
      }
      
      if (totalPaginasFiltradas > 5) {
        const span = document.createElement('span');
        span.textContent = '...';
        span.style.cssText = 'padding:8px 12px; color:#999;';
        paginasContainer.appendChild(span);
        
        const btnUltima = document.createElement('button');
        btnUltima.className = 'btn-pagina';
        btnUltima.dataset.pagina = totalPaginasFiltradas;
        btnUltima.textContent = totalPaginasFiltradas;
        btnUltima.style.cssText = 'padding:8px 12px; border-radius:6px; background:#e0e0e0; color:#333; border:none; cursor:pointer;';
        paginasContainer.appendChild(btnUltima);
        
        btnUltima.addEventListener('click', () => {
          renderizarBusqueda(totalPaginasFiltradas);
        });
      }
      
      // Renderizar primera página de búsqueda
      renderizarBusqueda(1);
    }

    function renderizarBusqueda(pagina) {
      paginaActual = pagina;
      const totalPaginasFiltradas = Math.ceil(estudiantesFiltrados.length / estudiantesPorPagina);
      
      if (pagina < 1 || pagina > totalPaginasFiltradas) return;
      
      const inicio = (pagina - 1) * estudiantesPorPagina;
      const fin = inicio + estudiantesPorPagina;
      const estudiantesEnPagina = estudiantesFiltrados.slice(inicio, fin);

      // Limpiar tbody
      tableBody.innerHTML = '';

      if (estudiantesEnPagina.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td colspan="7" style="padding:20px 16px; text-align:center; color:#999;">No se encontraron resultados</td>`;
        tableBody.appendChild(tr);
      } else {
        // Renderizar estudiantes
        estudiantesEnPagina.forEach((est) => {
          const tr = document.createElement('tr');
          tr.className = 'estudiante-row';
          tr.innerHTML = `
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.numero_control || '—'}</td>
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.nombre || '—'}</td>
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.carrera || '—'}</td>
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.sexo || '—'}</td>
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.nombre_actividad || '—'}</td>
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">${est.semestre || '—'}</td>
            <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; text-align:center; white-space:nowrap;">
              <button class="btn-editar" data-id="${est.id_alumno}" data-nombre="${est.nombre}" data-numero="${est.numero_control}" data-carrera="${est.carrera}" data-sexo="${est.sexo || ''}" data-semestre="${est.semestre}" style="display:inline-block; margin-right:12px; background:none; border:none; color:#ff7f00; cursor:pointer; font-size:16px; vertical-align:middle;" title="Editar"><i class="fas fa-edit"></i></button>
              <button class="btn-eliminar" data-id="${est.id_alumno}" data-nombre="${est.nombre}" style="display:inline-block; background:none; border:none; color:#dc3545; cursor:pointer; font-size:16px; vertical-align:middle;" title="Eliminar"><i class="fas fa-trash"></i></button>
            </td>
          `;
          tableBody.appendChild(tr);
        });
      }

      // Actualizar número de página y total
      numeroPagina.textContent = pagina;
      
      // Actualizar botones de páginas
      document.querySelectorAll('.btn-pagina').forEach(btn => {
        const btnPagina = parseInt(btn.dataset.pagina);
        if (btnPagina === pagina) {
          btn.style.background = '#1B396A';
          btn.style.color = '#fff';
          btn.style.fontWeight = '700';
        } else {
          btn.style.background = '#e0e0e0';
          btn.style.color = '#333';
          btn.style.fontWeight = '400';
        }
      });

      // Habilitar/Deshabilitar botones de navegación
      btnAnterior.style.display = pagina === 1 ? 'none' : 'inline-block';
      btnSiguiente.style.display = pagina === totalPaginasFiltradas ? 'none' : 'inline-block';
      
      attachEventListeners();
    }

    // Event listeners para búsqueda
    btnBuscar.addEventListener('click', buscar);
    buscadorNombre.addEventListener('input', buscar);  // Buscar en tiempo real mientras escribes
    buscadorNombre.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') buscar();
    });
    btnRecargar.addEventListener('click', () => {
      // Mostrar animación de carga
      btnRecargar.style.animation = 'spin 1s linear infinite';
      
      // Recargar la página después de 500ms
      setTimeout(() => {
        location.reload();
      }, 500);
    });

    // Agregar animación de spin
    const styleRecargar = document.createElement('style');
    styleRecargar.textContent = `
      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(styleRecargar);

    // Agregar event listeners iniciales
    attachEventListeners();

    // Re-agregar listeners después de cambiar de página
    const originalRenderizar = renderizarPagina;
    renderizarPagina = function(pagina) {
      originalRenderizar.call(this, pagina);
      attachEventListeners();
    };
    })();
  });
</script>

<!-- Modal Editar -->
<div id="modalEditar" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:8px; padding:24px; width:90%; max-width:500px; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
    <h2 style="margin-top:0; margin-bottom:20px; color:#1B396A;">Editar Estudiante</h2>
    <form id="formEditar">
      @csrf
      <input type="hidden" id="editId">
      <div style="margin-bottom:16px;">
        <label style="display:block; font-weight:700; margin-bottom:4px; color:#333;">Nombre:</label>
        <input type="text" id="editNombre" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block; font-weight:700; margin-bottom:4px; color:#333;">No. Control:</label>
        <input type="text" id="editNumero" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block; font-weight:700; margin-bottom:4px; color:#333;">Carrera:</label>
        <input type="text" id="editCarrera" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block; font-weight:700; margin-bottom:4px; color:#333;">Sexo:</label>
        <input type="text" id="editSexo" placeholder="Opcional" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
      </div>
      <div style="margin-bottom:20px;">
        <label style="display:block; font-weight:700; margin-bottom:4px; color:#333;">Semestre:</label>
        <input type="text" id="editSemestre" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
      </div>
      <div style="display:flex; gap:12px;">
        <button type="button" id="btnCerrarEditar" style="flex:1; padding:10px; border-radius:6px; background:#6c757d; color:#fff; border:none; cursor:pointer;">Cancelar</button>
        <button type="button" id="btnGuardarEditar" style="flex:1; padding:10px; border-radius:6px; background:#28a745; color:#fff; border:none; cursor:pointer;">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:8px; padding:24px; width:90%; max-width:500px; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
    <h2 style="margin-top:0; margin-bottom:20px; color:#dc3545;">Confirmar Eliminación</h2>
    <p style="margin-bottom:20px; color:#666;">¿Estás seguro de que deseas eliminar al estudiante <strong id="eliminarNombre">—</strong>?</p>
    <input type="hidden" id="eliminarId">
    <div style="display:flex; gap:12px;">
      <button id="btnCerrarEliminar" style="flex:1; padding:10px; border-radius:6px; background:#6c757d; color:#fff; border:none; cursor:pointer;">Cancelar</button>
      <button id="btnConfirmarEliminar" style="flex:1; padding:10px; border-radius:6px; background:#dc3545; color:#fff; border:none; cursor:pointer;">Eliminar</button>
    </div>
  </div>
</div>

<script>
  // Cerrar modales
  document.getElementById('btnCerrarEditar').addEventListener('click', function() {
    document.getElementById('modalEditar').style.display = 'none';
  });

  document.getElementById('btnCerrarEliminar').addEventListener('click', function() {
    document.getElementById('modalEliminar').style.display = 'none';
  });

  // Cerrar modal al hacer click fuera
  document.querySelectorAll('[id^="modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === this) {
        this.style.display = 'none';
      }
    });
  });

  // Guardar cambios
  document.getElementById('btnGuardarEditar').addEventListener('click', function() {
    const id = document.getElementById('editId').value;
    const nombre = document.getElementById('editNombre').value;
    const numero = document.getElementById('editNumero').value;
    const carrera = document.getElementById('editCarrera').value;
    const sexo = document.getElementById('editSexo').value.trim();
    const semestre = document.getElementById('editSemestre').value;

    if (!nombre || !numero || !carrera || !semestre) {
      alert('Por favor completa todos los campos');
      return;
    }

    const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    fetch(`/coordinador/valle-de-etla/estudiantes/${id}/actualizar`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        nombre: nombre,
        numero_control: numero,
        carrera: carrera,
        sexo: sexo || null,
        semestre: semestre
      })
    }).then(r => r.json()).then(json => {
      if (json.success) {
        // Mostrar alerta de éxito con SweetAlert2
        Swal.fire({
          title: '¡Listo!',
          text: 'Estudiante actualizado exitosamente',
          icon: 'success',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          confirmButtonColor: '#1B396A'
        });
        document.getElementById('modalEditar').style.display = 'none';
        setTimeout(() => {
          location.reload(); // Recargar página después de 2 segundos
        }, 2000);
      } else {
        alert('Error: ' + (json.message || 'Error desconocido'));
      }
    }).catch(err => {
      console.error(err);
      alert('Error al comunicarse con el servidor');
    });
  });
</script>
