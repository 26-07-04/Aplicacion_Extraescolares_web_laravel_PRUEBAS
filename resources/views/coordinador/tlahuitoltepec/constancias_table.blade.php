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
    #searchInput{ padding-left: 40px; }
    #searchInput:focus, #searchInput:focus-visible {
        outline: none;
        border-color: var(--color-azul-principal);
        box-shadow: 0 0 0 4px rgba(26,52,97,0.12);
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
            <div class="search-wrapper"><i class="fas fa-search"></i><input type="text" id="searchInput" onkeyup="renderTable()" placeholder="Buscar alumno..."></div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th class="sortable" data-column="numero_control">No. Control<i class="fas fa-sort sort-icon"></i></th>
                <th class="sortable" data-column="nombre">Nombre<i class="fas fa-sort sort-icon"></i></th>
                <th class="sortable" data-column="carrera">Carrera<i class="fas fa-sort sort-icon"></i></th>
                <th class="sortable" data-column="semestre">Semestre<i class="fas fa-sort sort-icon"></i></th>
                <th class="sortable" data-column="status">Estado<i class="fas fa-sort sort-icon"></i></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-alumnos">
            <!-- Filas de ejemplo; reemplazar con @@foreach($estudiantes as $estudiante) cuando se integre -->
        </tbody>
        <tbody id="empty-state-tbody" style="display: none;"><tr class="empty-row"><td colspan="6"></td></tr></tbody>
    </table>
</div>

<script>
    // Datos de ejemplo (reemplazar con backend cuando esté listo)
    const estudiantes = [
        { numero_control: '20250001', nombre: 'María López', carrera: 'Ingeniería en Sistemas', semestre: 'Agosto-Diciembre 2026', status: 'completed' },
        { numero_control: '20250002', nombre: 'Juan Pérez', carrera: 'Contaduría', semestre: 'Agosto-Diciembre 2026', status: 'pending' },
        { numero_control: '20250003', nombre: 'Ana Gómez', carrera: 'Administración', semestre: 'Agosto-Diciembre 2026', status: 'completed' }
    ];

    // Estado de orden (no implementado por ahora) y elementos
    const tablaBody = document.getElementById('tabla-alumnos');
    const searchInput = document.getElementById('searchInput');

    function updateStats(list) {
        const total = list.length;
        const evaluated = list.filter(s => s.status === 'completed').length;
        const pending = list.filter(s => s.status !== 'completed').length;
        document.getElementById('stat-total').textContent = total;
        document.getElementById('stat-evaluated').textContent = evaluated;
        document.getElementById('stat-pending').textContent = pending;
    }

    function renderTable() {
        const q = (searchInput.value || '').toLowerCase();
        const filtered = estudiantes.filter(s => {
            return s.numero_control.toLowerCase().includes(q) || s.nombre.toLowerCase().includes(q) || s.carrera.toLowerCase().includes(q);
        });

        tablaBody.innerHTML = '';
        if (filtered.length === 0) {
            document.getElementById('empty-state-tbody').style.display = '';
        } else {
            document.getElementById('empty-state-tbody').style.display = 'none';
            filtered.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${s.numero_control}</td>
                    <td>${s.nombre}</td>
                    <td>${s.carrera}</td>
                    <td>${s.semestre}</td>
                    <td><span class="status-badge ${s.status === 'completed' ? 'status-completed' : 'status-pending'}">${s.status === 'completed' ? 'CUMPLE' : 'PENDIENTE'}</span></td>
                    <td class="actions-container"><button class="btn-generar-constancia">Generar</button></td>
                `;
                tablaBody.appendChild(tr);
            });
        }

        updateStats(filtered);
    }

    function exportToCSV() {
        const rows = [
            ['No. Control','Nombre','Carrera','Semestre','Estado']
        ];
        const q = (searchInput.value || '').toLowerCase();
        const filtered = estudiantes.filter(s => s.numero_control.toLowerCase().includes(q) || s.nombre.toLowerCase().includes(q) || s.carrera.toLowerCase().includes(q));
        filtered.forEach(s => rows.push([s.numero_control, s.nombre, s.carrera, s.semestre, s.status]));
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

    // Inicializar
    renderTable();
</script>
