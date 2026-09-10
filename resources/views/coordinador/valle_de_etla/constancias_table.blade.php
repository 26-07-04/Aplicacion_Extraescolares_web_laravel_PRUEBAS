<!-- Partial: Tabla de Constancias de Cumplimiento Valle de Etla -->
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
    <!-- Selector de Documento Membretado -->
    <div style="background: linear-gradient(135deg, #1a3461 0%, #2d5aa0 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="color: white; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-file-image" style="font-size: 1.5em;"></i>
                <span style="font-weight: 600; font-size: 1.1em;">Documento Membretado:</span>
            </div>
            <select id="selectDocumentoMembrete" style="flex: 1; min-width: 300px; padding: 10px 15px; border: 2px solid white; border-radius: 6px; font-size: 0.95em; background: white; color: #333; cursor: pointer;">
                <option value="">Seleccione un documento...</option>
                @php
                    // Usar el semestre que fue pasado al panel (variable $semestre).
                    // Si no está disponible, caer a semestre activo como fallback.
                    $currentSemestre = $semestre ?? \App\Models\Semestre::where('estatus', 1)->first();
                    $documentosMembrete = [];
                    if ($currentSemestre) {
                        $semId = $currentSemestre->id_semestre ?? $currentSemestre->id ?? null;
                        if ($semId) {
                            $documentosMembrete = \App\Models\Documento::where('id_semestre', $semId)->get();
                        }
                    }
                @endphp
                @foreach($documentosMembrete as $doc)
                    <option value="{{ $doc->id }}">{{ $doc->nombre }}</option>
                @endforeach
            </select>
            <div id="estadoDocumento" style="display: none; color: #d4edda; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 6px; font-weight: 500;">
                <i class="fas fa-check-circle"></i> Documento seleccionado
            </div>
        </div>
    </div>

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
                    <th class="sortable" data-column="sexo">Sexo</th>
                    <th class="sortable" data-column="semestre">Semestre</th>
                    <th class="sortable" data-column="status">Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-alumnos">
            </tbody>
            <tbody id="empty-state-tbody" style="display: none;"><tr class="empty-row"><td colspan="7">No hay estudiantes</td></tr></tbody>
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
        // Obtener el semestre activo (estatus = 1 o true)
        $semestreActivo = \App\Models\Semestre::where('estatus', 1)->first();
        
        $tipoProgramaPanel = $tipo_programa_informes_panel ?? \App\Models\Actividad::TIPO_EXTRAESCOLAR;
        $idsActividades = ($actividades ?? collect())->pluck('id_actividad')->filter()->values()->all();
        $allEstudiantes = $idsActividades === [] ? collect() : \App\Support\EstudiantesPanelQuery::queryBase($idsActividades, $tipoProgramaPanel)
            ->leftJoin('evaluaciones', 'estudiantes.id_alumno', '=', 'evaluaciones.id_alumno')
            ->select('estudiantes.*', 'actividades.nombre_actividad', 'evaluaciones.id_evaluacion')
            ->orderBy('estudiantes.nombre', 'asc')
            ->get();
        
        $estudiantesArray = [];
        foreach ($allEstudiantes as $est) {
            $estudiantesArray[] = [
                'numero_control' => $est->numero_control ?? '',
                'nombre' => $est->nombre ?? '',
                'carrera' => $est->carrera ?? '',
                'sexo' => $est->sexo ?? '',
                'semestre' => $est->semestre ?? '',
                'status' => $est->id_evaluacion ? 'completed' : 'pending',
                'id_alumno' => $est->id_alumno ?? null,
                'id_evaluacion' => $est->id_evaluacion ?? null,
                'nombre_actividad' => $est->nombre_actividad ?? ''
            ];
        }
    @endphp
    
    const todosEstudiantes = @json($estudiantesArray);
    let estudiantesFiltrados = [...todosEstudiantes];
    
    // Datos del semestre (usar el semestre pasado al panel si existe, si no, caer al semestre activo)
    const semestreActivo = {
        fechaInicio: '{{ $semestre->fecha_inicio ?? $semestreActivo->fecha_inicio ?? "" }}',
        fechaFin: '{{ $semestre->fecha_fin ?? $semestreActivo->fecha_fin ?? "" }}',
        periodo: '{{ $semestre->nombre ?? $semestreActivo->nombre ?? "" }}',
        id: '{{ $semestre->id_semestre ?? $semestre->id ?? $semestreActivo->id_semestre ?? $semestreActivo->id ?? "" }}'
    };
    
    const tablaBody = document.getElementById('tabla-alumnos');
    const searchInput = document.getElementById('searchInput');
    const paginasContainer = document.getElementById('paginasContainer');
    const btnAnterior = document.getElementById('btnAnterior');
    const btnSiguiente = document.getElementById('btnSiguiente');
    
    const estudiantesPorPagina = 30;
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
                
                // Determinar qué botón mostrar según el estado
                let accionesHTML = '';
                if (s.status === 'completed' && s.id_evaluacion) {
                    // Ya fue evaluado - mostrar botón de descargar
                    accionesHTML = `
                        <button class="btn-generar-constancia" onclick="descargarConstancia(${s.id_evaluacion})" style="background-color: #17a2b8;">
                            <i class="fas fa-download"></i> Descargar
                        </button>
                    `;
                } else {
                    // No evaluado - mostrar botón evaluar
                    accionesHTML = `
                        <button class="btn-generar-constancia" data-id="${s.id_alumno}" onclick="evaluarEstudiante(this)">
                            <i class="fas fa-edit"></i> Evaluar
                        </button>
                    `;
                }
                
                tr.innerHTML = `
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:120px;">${s.numero_control}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:180px; overflow:hidden; text-overflow:ellipsis;">${s.nombre}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:140px; overflow:hidden; text-overflow:ellipsis;">${s.carrera}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:90px;">${s.sexo ?? ''}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; max-width:120px;">${s.semestre}</td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2;"><span class="status-badge ${s.status === 'completed' ? 'status-completed' : 'status-pending'}">${s.status === 'completed' ? 'CUMPLE' : 'PENDIENTE'}</span></td>
                    <td style="padding:12px 8px; border-bottom:1px solid #f2f2f2; text-align:center;">${accionesHTML}</td>
                `;
                tablaBody.appendChild(tr);
            });
        }

        actualizarStats(estudiantesFiltrados);
        actualizarPaginacion();
    }

    function actualizarPaginacion() {
        // Actualizar botones de páginas
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

        // Habilitar/Deshabilitar botones de navegación
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
                est.carrera.toLowerCase().includes(termino) ||
                String(est.sexo || '').toLowerCase().includes(termino)
            );
        }
        
        totalPaginas = Math.ceil(estudiantesFiltrados.length / estudiantesPorPagina);
        if (totalPaginas === 0) totalPaginas = 1;
        paginaActual = 1;
        renderizarPagina(1);
    }

    function exportToCSV() {
        const rows = [
            ['No. Control','Nombre','Carrera','Sexo','Semestre','Estado']
        ];
        estudiantesFiltrados.forEach(s => rows.push([s.numero_control, s.nombre, s.carrera, s.sexo ?? '', s.semestre, s.status]));
        const escapeCsv = (cell) => {
            let v = cell == null ? '' : String(cell);
            v = v.replace(/\r\n|\r|\n/g, ' ');
            return '"' + v.replace(/"/g, '""') + '"';
        };
        const lineSep = '\r\n';
        const csvBody = rows.map(r => r.map(escapeCsv).join(',')).join(lineSep);
        const csv = '\uFEFF' + csvBody;
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
            swalAlerta('Error: ID del alumno no encontrado');
            return;
        }
        
        // Buscar datos del estudiante
        const estudiante = todosEstudiantes.find(est => est.id_alumno == idAlumno);
        if (!estudiante) {
            swalAlerta('Error: No se encontraron datos del estudiante');
            return;
        }
        
        // Abrir el modal con los datos del estudiante
        abrirModalEvaluacion(estudiante);
    }

    function abrirModalEvaluacion(estudiante) {
        // Crear el modal con formulario completo (código igual a Demetrio Vallejo pero con rutas adaptadas)
        const modal = document.createElement('div');
        modal.id = 'modalEvaluacion';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            overflow-y: auto;
            padding: 20px;
        `;
        
        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; max-width: 1000px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                <div style="background: linear-gradient(135deg, #1a3461 0%, #2d5aa0 100%); color: white; padding: 25px 30px; border-radius: 12px 12px 0 0; position: relative;">
                    <h2 style="margin: 0 0 8px 0; font-size: 1.8em; font-weight: 700;">
                        <i class="fas fa-clipboard-check" style="margin-right: 10px;"></i>
                        Evaluación de Desempeño
                    </h2>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95em;">Actividad Cultural y/o Deportiva</p>
                    <button onclick="cerrarModalEvaluacion()" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 1.3em; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div style="padding: 30px;">
                    <form id="formEvaluacion">
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #1a3461;">
                            <h3 style="margin: 0 0 15px 0; color: #1a3461; font-size: 1.2em; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user-graduate"></i>
                                Información del Estudiante
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        (1) Nombre del estudiante:
                                    </label>
                                    <input type="text" id="nombreEstudiante" readonly value="${estudiante.nombre}" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; background: #e9ecef; color: #495057; font-size: 0.95em;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        (2) Actividad Cultural y/o Deportiva:
                                    </label>
                                    <input type="text" id="actividadCultural" readonly value="${estudiante.nombre_actividad || 'N/A'}" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; background: #e9ecef; color: #495057; font-size: 0.95em;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        (3) Periodo de realización:
                                    </label>
                                    <div style="margin-bottom: 10px; padding: 8px 12px; background: #e7f3ff; border-radius: 6px; border-left: 3px solid #0d6efd;">
                                        <small style="color: #0d6efd; font-weight: 600;">
                                            <i class="fas fa-calendar-alt"></i> Semestre: <span id="periodoSemestre"></span>
                                        </small>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                        <div>
                                            <label style="display: block; font-size: 0.85em; color: #6c757d; margin-bottom: 4px;">Fecha de inicio:</label>
                                            <input type="date" id="fechaInicio" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background: #e9ecef; color: #495057; font-size: 0.95em;">
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.85em; color: #6c757d; margin-bottom: 4px;">Fecha de término:</label>
                                            <input type="date" id="fechaTermino" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background: #e9ecef; color: #495057; font-size: 0.95em;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <h3 style="margin: 0 0 15px 0; color: #1a3461; font-size: 1.2em; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-tasks"></i>
                                (4) Nivel de desempeño del criterio
                            </h3>
                            <p style="color: #6c757d; font-size: 0.9em; margin: 0 0 20px 0; padding: 12px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 6px;">
                                <i class="fas fa-info-circle"></i> Seleccione el nivel de desempeño alcanzado por el estudiante en cada criterio
                            </p>
                            
                            <div style="overflow-x: auto;">
                                <table id="tablaCriterios" style="width: 100%; border-collapse: collapse; border: 1px solid #dee2e6; table-layout: auto;">
                                    <thead>
                                        <tr style="background: #1a3461; color: white;">
                                            <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; width: 50px; vertical-align: middle;">No.</th>
                                            <th style="padding: 12px; text-align: left; border: 1px solid #dee2e6; min-width: 300px; vertical-align: middle;">Criterios a evaluar</th>
                                            <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; width: 90px; vertical-align: middle;">Insuficiente<br><small>(0)</small></th>
                                            <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; width: 90px; vertical-align: middle;">Suficiente<br><small>(1)</small></th>
                                            <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; width: 90px; vertical-align: middle;">Bueno<br><small>(2)</small></th>
                                            <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; width: 90px; vertical-align: middle;">Notable<br><small>(3)</small></th>
                                            <th style="padding: 12px; text-align: center; border: 1px solid #dee2e6; width: 90px; vertical-align: middle;">Excelente<br><small>(4)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${generarFilasCriterios()}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #17a2b8;">
                            <h3 style="margin: 0 0 15px 0; color: #17a2b8; font-size: 1.2em; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user-tie"></i>
                                (5) Datos para la Constancia
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        <i class="fas fa-user-cog"></i> Nombre del Jefe(a) del Depto. de Servicios Escolares: <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" id="jefeServiciosEscolares" required placeholder="Ingrese el nombre completo del jefe de servicios escolares" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95em;">
                                    <p id="cargoDestinatario" class="constancia-firma-cargo" data-default="Jefe del Departamento de Servicios Escolares" contenteditable="false" title="Doble clic para editar el puesto">Jefe del Departamento de Servicios Escolares</p>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        <i class="fas fa-chalkboard-teacher"></i> Nombre del profesor(a) responsable: <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" id="nombreProfesor" required value="{{ Auth::user()->name ?? '' }}" placeholder="Ingrese el nombre completo del profesor responsable" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95em;">
                                    <p id="cargoProfesor" class="constancia-firma-cargo" data-default="Profesor responsable" contenteditable="false" title="Doble clic para editar el puesto">Profesor responsable</p>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        <i class="fas fa-user-shield"></i> Nombre del Jefe(a) del Depto. de Actividades Extraescolares: <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" id="jefeExtraescolares" required placeholder="Ingrese el nombre completo del jefe de departamento" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95em;">
                                    <p id="cargoVobo" class="constancia-firma-cargo" data-default="Jefe del Depto. de Actividades Extraescolares" contenteditable="false" title="Doble clic para editar el puesto">Jefe del Depto. de Actividades Extraescolares</p>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #1a3461; margin-bottom: 8px; font-size: 1.05em;">
                                <i class="fas fa-comment-alt"></i> (6) Observaciones:
                            </label>
                            <textarea id="observaciones" rows="4" placeholder="Anote todas las reflexiones que considere importantes para que el estudiante realice mejoras..." style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95em; font-family: inherit; resize: vertical;"></textarea>
                        </div>

                        <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; border-left: 4px solid #0d6efd;">
                            <h3 style="margin: 0 0 15px 0; color: #0d6efd; font-size: 1.2em; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calculator"></i>
                                Resultados de la Evaluación
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        (7) Valor numérico de la actividad:
                                    </label>
                                    <input type="text" id="valorNumerico" readonly value="0.00" style="width: 100%; padding: 10px 12px; border: 1px solid #0d6efd; border-radius: 6px; background: white; color: #0d6efd; font-weight: 700; font-size: 1.1em; text-align: center;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.95em;">
                                        (8) Nivel de desempeño alcanzado:
                                    </label>
                                    <input type="text" id="nivelDesempeno" readonly value="No evaluado" style="width: 100%; padding: 10px 12px; border: 1px solid #0d6efd; border-radius: 6px; background: white; color: #0d6efd; font-weight: 700; font-size: 1.1em; text-align: center;">
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                            <button type="button" onclick="cerrarModalEvaluacion()" style="padding: 12px 28px; border: 2px solid #6c757d; background: white; color: #6c757d; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.95em; transition: all 0.3s;" onmouseover="this.style.background='#6c757d'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='#6c757d'">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" style="padding: 12px 28px; border: none; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.95em; transition: all 0.3s; box-shadow: 0 4px 12px rgba(40,167,69,0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(40,167,69,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(40,167,69,0.3)'">
                                <i class="fas fa-save"></i> Guardar Evaluación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        if (typeof window.inicializarConstanciaExtraescolarCargos === 'function') {
            window.inicializarConstanciaExtraescolarCargos();
        }
        
        agregarCalculosAutomaticos();
        cargarFechasSemestre();
        
        document.getElementById('formEvaluacion').addEventListener('submit', function(e) {
            e.preventDefault();
            guardarEvaluacion(estudiante);
        });
    }

    function cargarFechasSemestre() {
        const periodoElement = document.getElementById('periodoSemestre');
        const fechaInicioInput = document.getElementById('fechaInicio');
        const fechaTerminoInput = document.getElementById('fechaTermino');
        
        if (semestreActivo.periodo) {
            periodoElement.textContent = semestreActivo.periodo;
        } else {
            periodoElement.textContent = 'No definido';
        }
        
        if (semestreActivo.fechaInicio) {
            fechaInicioInput.value = semestreActivo.fechaInicio;
        }
        
        if (semestreActivo.fechaFin) {
            fechaTerminoInput.value = semestreActivo.fechaFin;
        }
    }

    function generarFilasCriterios() {
        const criterios = [
            'Cumple en tiempo y forma con las actividades encomendadas alcanzando los objetivos.',
            'Trabaja en equipo y se adapta a nuevas situaciones.',
            'Muestra liderazgo en las actividades encomendadas.',
            'Organiza su tiempo y trabaja de manera proactiva.',
            'Interpreta la realidad y se sensibiliza aportando soluciones a la problemática con la actividad Cultural y/o Deportiva.',
            'Realiza sugerencias innovadoras para beneficio o mejora del programa en el que participa.',
            'Tiene iniciativa para ayudar en las actividades encomendadas y muestra espíritu de servicio.'
        ];
        
        let html = '';
        criterios.forEach((criterio, index) => {
            const num = index + 1;
            html += `
                <tr style="background: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'};">
                    <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6; font-weight: 700; color: #1a3461; vertical-align: middle;">${num}</td>
                    <td style="padding: 12px; border: 1px solid #dee2e6; color: #333; line-height: 1.5; white-space: normal; word-wrap: break-word; min-width: 300px; vertical-align: middle;">${criterio}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6; vertical-align: middle;">
                        <input type="radio" name="criterio${num}" value="0" class="criterio-radio" required style="width: 20px; height: 20px; cursor: pointer;">
                    </td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6; vertical-align: middle;">
                        <input type="radio" name="criterio${num}" value="1" class="criterio-radio" required style="width: 20px; height: 20px; cursor: pointer;">
                    </td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6; vertical-align: middle;">
                        <input type="radio" name="criterio${num}" value="2" class="criterio-radio" required style="width: 20px; height: 20px; cursor: pointer;">
                    </td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6; vertical-align: middle;">
                        <input type="radio" name="criterio${num}" value="3" class="criterio-radio" required style="width: 20px; height: 20px; cursor: pointer;">
                    </td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #dee2e6; vertical-align: middle;">
                        <input type="radio" name="criterio${num}" value="4" class="criterio-radio" required checked style="width: 20px; height: 20px; cursor: pointer;">
                    </td>
                </tr>
            `;
        });
        
        return html;
    }

    function agregarCalculosAutomaticos() {
        const radios = document.querySelectorAll('.criterio-radio');
        radios.forEach(radio => {
            radio.addEventListener('change', calcularResultados);
        });
    }

    function calcularResultados() {
        let suma = 0;
        let criteriosEvaluados = 0;
        
        for (let i = 1; i <= 7; i++) {
            const radioSeleccionado = document.querySelector(`input[name="criterio${i}"]:checked`);
            if (radioSeleccionado) {
                suma += parseFloat(radioSeleccionado.value);
                criteriosEvaluados++;
            }
        }
        
        if (criteriosEvaluados === 7) {
            const promedio = suma / 7;
            document.getElementById('valorNumerico').value = promedio.toFixed(2);
            
            let nivelDesempeno = '';
            if (promedio >= 3.50 && promedio <= 4.00) {
                nivelDesempeno = 'Excelente';
                document.getElementById('nivelDesempeno').style.color = '#28a745';
            } else if (promedio >= 2.50 && promedio < 3.50) {
                nivelDesempeno = 'Notable';
                document.getElementById('nivelDesempeno').style.color = '#17a2b8';
            } else if (promedio >= 1.50 && promedio < 2.50) {
                nivelDesempeno = 'Bueno';
                document.getElementById('nivelDesempeno').style.color = '#ffc107';
            } else if (promedio >= 1.00 && promedio < 1.50) {
                nivelDesempeno = 'Suficiente';
                document.getElementById('nivelDesempeno').style.color = '#fd7e14';
            } else if (promedio >= 0.00 && promedio < 1.00) {
                nivelDesempeno = 'Insuficiente';
                document.getElementById('nivelDesempeno').style.color = '#dc3545';
            }
            
            document.getElementById('nivelDesempeno').value = nivelDesempeno;
        } else {
            document.getElementById('valorNumerico').value = '0.00';
            document.getElementById('nivelDesempeno').value = 'No evaluado';
            document.getElementById('nivelDesempeno').style.color = '#0d6efd';
        }
    }

    function obtenerCriteriosDesempeno() {
        const valores = [];
        for (let i = 1; i <= 7; i++) {
            const radio = document.querySelector(`input[name="criterio${i}"]:checked`);
            valores.push(radio ? parseInt(radio.value, 10) : null);
        }
        return valores;
    }

    function cerrarModalEvaluacion() {
        const modal = document.getElementById('modalEvaluacion');
        if (modal) {
            modal.remove();
        }
    }

    function guardarEvaluacion(estudiante) {
        const documentoMembrete = document.getElementById('selectDocumentoMembrete').value;
        if (!documentoMembrete) {
            swalAlerta('Por favor, seleccione un documento membretado antes de evaluar.');
            return;
        }

        const valorNumerico = parseFloat(document.getElementById('valorNumerico').value);
        const nivelDesempeno = document.getElementById('nivelDesempeno').value;
        const criteriosDesempeno = obtenerCriteriosDesempeno();
        if (criteriosDesempeno.some(v => v === null) || nivelDesempeno === 'No evaluado' || !nivelDesempeno) {
            swalAlerta('Por favor, evalúe los 7 criterios antes de guardar.');
            return;
        }
        
        const nombreProfesor = document.getElementById('nombreProfesor').value.trim();
        const jefeExtraescolares = document.getElementById('jefeExtraescolares').value.trim();
        const jefeServiciosEscolares = document.getElementById('jefeServiciosEscolares').value.trim();
        
        if (!nombreProfesor) {
            swalAlerta('Por favor, ingrese el nombre del profesor responsable.');
            document.getElementById('nombreProfesor').focus();
            return;
        }
        
        if (!jefeExtraescolares) {
            swalAlerta('Por favor, ingrese el nombre del Jefe del Departamento de Actividades Extraescolares.');
            document.getElementById('jefeExtraescolares').focus();
            return;
        }
        
        if (!jefeServiciosEscolares) {
            swalAlerta('Por favor, ingrese el nombre del Jefe del Departamento de Servicios Escolares.');
            document.getElementById('jefeServiciosEscolares').focus();
            return;
        }
        
        const datosEvaluacion = {
            id_alumno: estudiante.id_alumno,
            nivel_desempeno: nivelDesempeno,
            criterios_desempeno: criteriosDesempeno,
            calificacion_numerica: valorNumerico,
            creditos: 5,
            observaciones: document.getElementById('observaciones').value,
            ciudad: 'Oaxaca',
            nombre_profesor: nombreProfesor,
            jefe_extraescolares: jefeExtraescolares,
            jefe_servicios_escolares: jefeServiciosEscolares,
            cargo_profesor: (function () {
                const el = document.getElementById('cargoProfesor');
                const t = el ? (el.textContent || '').trim() : '';
                return t || 'Profesor responsable';
            })(),
            cargo_vobo: (function () {
                const el = document.getElementById('cargoVobo');
                const t = el ? (el.textContent || '').trim() : '';
                return t || 'Jefe del Depto. de Actividades Extraescolares';
            })(),
            cargo_destinatario: (function () {
                const el = document.getElementById('cargoDestinatario');
                const t = el ? (el.textContent || '').trim() : '';
                return t || 'Jefe del Departamento de Servicios Escolares';
            })(),
            _token: '{{ csrf_token() }}'
        };
        
        const btnGuardar = document.querySelector('#formEvaluacion button[type="submit"]');
        const textoOriginal = btnGuardar.innerHTML;
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        
        // RUTAS ADAPTADAS PARA VALLE DE ETLA
        fetch('{{ route("valleetla.evaluacion.guardar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(datosEvaluacion)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cerrarModalEvaluacion();
                
                // RUTA ADAPTADA PARA VALLE DE ETLA
                const urlPDF = `/coordinador/valle-de-etla/constancia/${data.id_evaluacion}/pdf?id_documento=${documentoMembrete}`;
                window.location.href = urlPDF;
                
                const estudianteIndex = todosEstudiantes.findIndex(e => e.id_alumno == estudiante.id_alumno);
                if (estudianteIndex !== -1) {
                    todosEstudiantes[estudianteIndex].status = 'completed';
                    todosEstudiantes[estudianteIndex].id_evaluacion = data.id_evaluacion;
                    buscar();
                }
                
                setTimeout(() => {
                    Swal.fire({
                        title: 'Evaluación guardada',
                        text: 'Descargando constancia...',
                        icon: 'success',
                        draggable: true,
                        showConfirmButton: false,
                        timer: 1800,
                        timerProgressBar: true
                    });
                }, 400);
            } else {
                Swal.fire({
                    title: 'Error al guardar',
                    text: (data.message || 'Ocurrió un problema al guardar la evaluación'),
                    icon: 'error',
                    draggable: true,
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = textoOriginal;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo conectar al servidor. Intente nuevamente.',
                icon: 'error',
                draggable: true,
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = textoOriginal;
        });
    }

    // RUTA ADAPTADA PARA VALLE DE ETLA
    function descargarConstancia(idEvaluacion) {
        const documentoMembrete = document.getElementById('selectDocumentoMembrete').value;
        if (!documentoMembrete) {
            Swal.fire({
                title: 'Documento requerido',
                text: 'Por favor, seleccione un documento membretado.',
                icon: 'warning',
                draggable: true,
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            });
            return;
        }
        const urlPDF = `/coordinador/valle-de-etla/constancia/${idEvaluacion}/pdf?id_documento=${documentoMembrete}`;
        window.location.href = urlPDF;
    }

    // Persistencia del documento membretado
    (function persistenciaDocumentoMembrete(){
        const select = document.getElementById('selectDocumentoMembrete');
        const estadoDoc = document.getElementById('estadoDocumento');
        const semestreId = '{{ $semestre->id_semestre ?? $semestre->id ?? $semestreActivo->id_semestre ?? $semestreActivo->id ?? "" }}';
        const storageKey = `membreteDocId_${semestreId || 'global'}`;

        const savedDoc = localStorage.getItem(storageKey);
        if (savedDoc && select) {
            select.value = savedDoc;
            if (select.value) {
                estadoDoc.style.display = 'block';
            }
        }

        select?.addEventListener('change', function() {
            if (this.value) {
                localStorage.setItem(storageKey, this.value);
                estadoDoc.style.display = 'block';
            } else {
                localStorage.removeItem(storageKey);
                estadoDoc.style.display = 'none';
            }
        });
    })();

    // Inicializar
    totalPaginas = Math.ceil(estudiantesFiltrados.length / estudiantesPorPagina);
    renderizarPagina(1);
</script>
@include('coordinador.partials.constancia_extraescolar_cargos_editable', ['constanciaCargosStorageKey' => 'constancia_cargos_extraescolar_valle'])
