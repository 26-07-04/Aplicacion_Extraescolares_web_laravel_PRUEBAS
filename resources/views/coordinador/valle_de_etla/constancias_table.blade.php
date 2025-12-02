<!-- Partial: Tabla de Constancias de Cumplimiento (estática) -->
<style>
    /* Solo estilos necesarios para la tabla y sus controles */
    :root{
        --color-azul-principal: #1a3461;
        --color-verde-accion: #007a4d;
        --color-verde-accion-hover: #00633e;
        --color-fondo-claro: #f8f9fa;
        --color-fondo-tarjeta: #ffffff;
        --color-texto-oscuro: #333;
        --color-texto-secundario: #6c757d;
        --color-borde-suave: #e0e0e0;
        --color-borde-fuerte: #ced4da;
        --sombra-profesional: 0 4px 12px rgba(0,0,0,0.08);
        --transicion-fluida: all 0.3s ease;
        /* Icon colors */
        --icon-primary: #0b5ed7; /* azul para indicadores */
        --icon-evaluated: #28a745; /* verde para evaluados */
        --icon-pending: #ffc107; /* amarillo para pendientes */
        --icon-muted: #6c757d; /* iconos secundarios */
        --icon-action: #ffffff; /* iconos dentro de botones oscuros */
    }
    /* Modo oscuro local para este partial (se activa con data-theme="dark" en el body) */
    body[data-theme="dark"] {
        --color-fondo-claro: #121212;
        --color-fondo-tarjeta: #1e1e1e;
        --color-texto-oscuro: #e6e6e6;
        --color-texto-secundario: #a0a0a0;
        --color-borde-suave: #2b2b2b;
        --color-borde-fuerte: #3a3a3a;
        --sombra-profesional: 0 6px 18px rgba(0,0,0,0.6);
        --icon-primary: #79a7ff;
        --icon-evaluated: #57d68a;
        --icon-pending: #ffd963;
        --icon-muted: #bfc6d9;
        --icon-action: #ffffff;
    }
    .container { flex-grow: 1; padding: 20px; max-width: 1100px; margin: 30px auto; background-color: var(--color-fondo-tarjeta); border: 1px solid var(--color-borde-suave); border-radius: 8px; box-shadow: var(--sombra-profesional); transition: background-color 0.3s, border-color 0.3s; }
    .stats-panel { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 25px; }
    .stat-card { background-color: var(--color-fondo-claro); padding: 20px; border-radius: 8px; text-align: center; border: 1px solid var(--color-borde-suave); }
    .stat-card .stat-value { font-size: 2.2em; font-weight: 700; color: var(--color-azul-principal); display:flex; align-items:center; justify-content:center; gap:10px; }
    .stat-card .stat-label { font-size: 1em; font-weight: 500; color: var(--color-texto-secundario); margin-top: 5px; }
    .stat-card .stat-value i { color: var(--icon-primary); font-size: 0.9em; }
    /* Colores específicos por icono */
    .stat-card .fa-users { color: var(--icon-primary); }
    .stat-card .fa-user-check { color: var(--icon-evaluated); }
    .stat-card .fa-user-clock { color: var(--icon-pending); }
    /* Reglas más específicas por posición para asegurar aplicación */
    .stats-panel .stat-card:nth-child(1) .stat-value i { color: var(--icon-primary); }
    .stats-panel .stat-card:nth-child(2) .stat-value i { color: var(--icon-evaluated); }
    .stats-panel .stat-card:nth-child(3) .stat-value i { color: var(--icon-pending); }
    .table-controls { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; padding: 10px 15px; background-color: var(--color-azul-principal); color: white; border-top-left-radius: 6px; border-top-right-radius: 6px; }
    .table-controls .title { font-size: 1.2em; font-weight: bold; margin: 0; }
    .controls-right { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
    .search-wrapper{ position: relative; display: inline-flex; align-items: center; }
    /* Asegurar que la lupa se muestre sobre el input y esté centrada verticalmente */
    .table-controls .search-wrapper i{
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--icon-muted);
        pointer-events: none;
        z-index: 2;
        font-size: 0.95em;
    }
    /* Asegurar suficiente padding en el input para la lupa */
    #searchInput{ padding-left: 40px; }
    /* Focus suave en azul en lugar de contorno negro */
    #searchInput:focus, #searchInput:focus-visible {
        outline: none;
        border-color: var(--color-azul-principal);
        box-shadow: 0 0 0 4px rgba(26,52,97,0.12); /* azul suave */
    }
    /* En modo oscuro usar una variante más clara del azul */
    body[data-theme="dark"] #searchInput:focus, body[data-theme="dark"] #searchInput:focus-visible {
        outline: none;
        border-color: var(--icon-primary);
        box-shadow: 0 0 0 4px rgba(121,167,255,0.12);
    }
    #searchInput { background-color: var(--color-fondo-tarjeta); color: var(--color-texto-oscuro); padding: 8px 12px 8px 35px; border-radius: 5px; border: 1px solid var(--color-borde-fuerte); font-size: 0.9em; transition: var(--transicion-fluida); }
    .btn-action { background-color: var(--color-verde-accion); color: var(--icon-action); border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 0.9em; transition: var(--transicion-fluida); display: inline-flex; align-items: center; gap: 8px; font-weight: 500; }
    .btn-export { background-color: #17a2b8; }
    .btn-export:hover { background-color: #138496; }
    .btn-action i, .btn-export i { color: var(--icon-action); }
    .table-controls i { color: var(--icon-action); margin-right: 6px; }
    /* theme switch removed - no icons/controls here anymore */
    table { width: 100%; border-collapse: collapse; border: 1px solid var(--color-borde-suave); border-top: none; }
    th, td { padding: 12px 15px; border-bottom: 1px solid var(--color-borde-suave); text-align: left; }
    thead th { background-color: var(--color-fondo-claro); color: var(--color-texto-oscuro); font-weight: 700; }
    thead th.sortable { cursor: pointer; user-select: none; }
    tbody tr:hover { background-color: var(--color-borde-suave); }
    .empty-row td { text-align: center; padding: 40px; color: var(--color-texto-secundario); font-style: italic; }
    .status-badge { padding: 3px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 700; color: #fff; text-transform: uppercase; }
    .status-pending { background-color: #ffc107; }
    .status-completed { background-color: #6c757d; }
    .actions-container { display: flex; align-items: center; gap: 10px; }
    .btn-generar-constancia { background-color: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; font-size: 0.9em; transition: var(--transicion-fluida); display: inline-flex; align-items: center; gap: 5px; }
    .btn-generar-constancia:disabled { background-color: #aaa; cursor: not-allowed; }
    @media (max-width: 768px) {
        .table-controls { flex-direction: column; align-items: stretch; }
    }
    table { white-space: nowrap; overflow-x: auto; }
    th, td { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
    th:nth-child(2), td:nth-child(2) { max-width: 250px; }
    .pagination-container { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 16px; flex-wrap: wrap; }
    .btn-pagina { padding: 8px 12px; border-radius: 6px; background: #e0e0e0; color: #333; border: none; cursor: pointer; font-weight: 400; transition: var(--transicion-fluida); }
    .btn-pagina.activo { background: #1B396A; color: #fff; font-weight: 700; }
    .btn-paginacion { padding: 8px 12px; border-radius: 6px; background: #1B396A; color: #fff; border: none; cursor: pointer; }
    .btn-paginacion:disabled { background: #ccc; cursor: not-allowed; }
    .numero-pagina { padding: 0 8px; color: var(--color-texto-oscuro); font-weight: 500; }
</style>

<div class="container">
    <div class="stats-panel">
        <div class="stat-card"><div class="stat-value"><i class="fas fa-users"></i> <span id="stat-total">0</span></div><div class="stat-label">Total de Alumnos</div></div>
        <div class="stat-card"><div class="stat-value"><i class="fas fa-user-check"></i> <span id="stat-evaluated">0</span></div><div class="stat-label">Alumnos Evaluados</div></div>
        <div class="stat-card"><div class="stat-value"><i class="fas fa-user-clock"></i> <span id="stat-pending">0</span></div><div class="stat-label">Alumnos Pendientes</div></div>
    </div>
    <div class="table-controls">
        <h3 class="title">Actividad</h3>
        <div class="controls-right">
            <button class="btn-action btn-export" onclick="exportToCSV()"><i class="fas fa-file-csv"></i> Exportar a CSV</button>
            <div class="search-wrapper"><i class="fas fa-search"></i><input type="text" id="searchInput" onkeyup="buscar()" placeholder="Buscar alumno..."></div>
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th class="sortable" data-column="numero_control">No. Control</th>
                    <th class="sortable" data-column="nombre">Nombre</th>
                    <th class="sortable" data-column="carrera">Carrera</th>
                    <th class="sortable" data-column="semestre">Semestre</th>
                    <th class="sortable" data-column="status">Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-alumnos">
            </tbody>
            <tbody id="empty-state-tbody" style="display: none;"><tr class="empty-row"><td colspan="6">No hay estudiantes</td></tr></tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="pagination-container">
        <button id="btnAnterior" class="btn-paginacion" onclick="paginaAnterior()">
            <i class="fas fa-chevron-left"></i> Anterior
        </button>
        <div id="paginasContainer" style="display: flex; gap: 4px;"></div>
        <button id="btnSiguiente" class="btn-paginacion" onclick="paginaSiguiente()">
            Siguiente <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<script>
    // Datos de estudiantes cargados desde la base de datos
    @php
        $allEstudiantes = \App\Models\Estudiante::whereIn('estudiantes.id_actividad', 
            $actividades->pluck('id_actividad')->toArray()
        )
        ->join('actividades', 'estudiantes.id_actividad', '=', 'actividades.id_actividad')
        ->select('estudiantes.*', 'actividades.nombre_actividad')
        ->orderBy('estudiantes.nombre', 'asc')
        ->get();
        
        $estudiantesArray = [];
        foreach ($allEstudiantes as $est) {
            $estudiantesArray[] = [
                'numero_control' => $est->numero_control ?? '',
                'nombre' => $est->nombre ?? '',
                'carrera' => $est->carrera ?? '',
                'semestre' => $est->semestre ?? '',
                'status' => 'pending',
                'id_alumno' => $est->id_alumno ?? null,
                'nombre_actividad' => $est->nombre_actividad ?? ''
            ];
        }
    @endphp
    
    const todosEstudiantes = @json($estudiantesArray);
    let estudiantesFiltrados = [...todosEstudiantes];
    
    const tablaBody = document.getElementById('tabla-alumnos');
    const searchInput = document.getElementById('searchInput');
    const paginasContainer = document.getElementById('paginasContainer');
    const btnAnterior = document.getElementById('btnAnterior');
    const btnSiguiente = document.getElementById('btnSiguiente');
    
    const estudiantesPorPagina = 10;
    let paginaActual = 1;
    let totalPaginas = 1;

    function actualizarStats(list) {
        const total = list.length;
        const evaluated = list.filter(s => s.status === 'completed').length;
        const pending = list.filter(s => s.status !== 'completed').length;
        document.getElementById('stat-total').textContent = total;
        document.getElementById('stat-evaluated').textContent = evaluated;
        document.getElementById('stat-pending').textContent = pending;
    }

    function renderizarPagina(pagina) {
        if (pagina < 1 || pagina > totalPaginas) return;
        
        paginaActual = pagina;
        const inicio = (pagina - 1) * estudiantesPorPagina;
        const fin = inicio + estudiantesPorPagina;
        const estudiantesEnPagina = estudiantesFiltrados.slice(inicio, fin);

        // Limpiar tabla
        tablaBody.innerHTML = '';

        if (estudiantesEnPagina.length === 0) {
            document.getElementById('empty-state-tbody').style.display = '';
        } else {
            document.getElementById('empty-state-tbody').style.display = 'none';
            estudiantesEnPagina.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:120px;">${s.numero_control}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:180px; overflow:hidden; text-overflow:ellipsis;">${s.nombre}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:140px; overflow:hidden; text-overflow:ellipsis;">${s.carrera}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:120px;">${s.semestre}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2;"><span class="status-badge ${s.status === 'completed' ? 'status-completed' : 'status-pending'}">${s.status === 'completed' ? 'CUMPLE' : 'PENDIENTE'}</span></td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; text-align:center;"><button class="btn-generar-constancia" data-id="${s.id_alumno}" onclick="evaluarEstudiante(this)">Evaluar</button></td>
                `;
                tablaBody.appendChild(tr);
            });
        }

        actualizarStats(estudiantesFiltrados);
        actualizarPaginacion();
    }

    function actualizarPaginacion() {
        paginasContainer.innerHTML = '';
        const maxBotones = 5;
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, inicio + maxBotones - 1);
        if (fin - inicio < maxBotones - 1) {
            inicio = Math.max(1, fin - maxBotones + 1);
        }

        for (let i = inicio; i <= fin; i++) {
            const btn = document.createElement('button');
            btn.className = `btn-pagina ${i === paginaActual ? 'activo' : ''}`;
            btn.textContent = i;
            btn.onclick = () => renderizarPagina(i);
            paginasContainer.appendChild(btn);
        }

        if (fin < totalPaginas) {
            const span = document.createElement('span');
            span.textContent = '...';
            span.style.padding = '0 8px';
            paginasContainer.appendChild(span);

            const btnUltima = document.createElement('button');
            btnUltima.className = 'btn-pagina';
            btnUltima.textContent = totalPaginas;
            btnUltima.onclick = () => renderizarPagina(totalPaginas);
            paginasContainer.appendChild(btnUltima);
        }

        btnAnterior.disabled = paginaActual === 1;
        btnSiguiente.disabled = paginaActual === totalPaginas;
    }

    function paginaAnterior() {
        renderizarPagina(paginaActual - 1);
    }

    function paginaSiguiente() {
        renderizarPagina(paginaActual + 1);
    }

    function buscar() {
        const termino = (searchInput.value || '').toLowerCase();
        
        if (termino.trim() === '') {
            estudiantesFiltrados = [...todosEstudiantes];
        } else {
            estudiantesFiltrados = todosEstudiantes.filter(est =>
                est.numero_control.toLowerCase().includes(termino) ||
                est.nombre.toLowerCase().includes(termino) ||
                est.carrera.toLowerCase().includes(termino)
            );
        }
        
        totalPaginas = Math.ceil(estudiantesFiltrados.length / estudiantesPorPagina);
        if (totalPaginas === 0) totalPaginas = 1;
        paginaActual = 1;
        renderizarPagina(1);
    }

    function exportToCSV() {
        const rows = [
            ['No. Control','Nombre','Carrera','Semestre','Estado']
        ];
        estudiantesFiltrados.forEach(s => rows.push([s.numero_control, s.nombre, s.carrera, s.semestre, s.status]));
        const csv = rows.map(r => r.map(c => '"' + String(c).replace(/"/g,'""') + '"').join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'constancias.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function evaluarEstudiante(button) {
        const idAlumno = button.getAttribute('data-id');
        if (!idAlumno) {
            alert('Error: ID del alumno no encontrado');
            return;
        }
        console.log('Evaluar estudiante con ID:', idAlumno);
        alert('Funcionalidad de evaluación en desarrollo.\nEstudiante ID: ' + idAlumno);
    }

    // Inicializar
    totalPaginas = Math.ceil(estudiantesFiltrados.length / estudiantesPorPagina);
    renderizarPagina(1);
</script>
