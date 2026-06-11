<style>
    .eval-imp-container {
        padding: 24px;
        background: #f8f9fa;
        min-height: calc(100vh - 120px);
    }
    .eval-imp-header {
        background: linear-gradient(135deg, #1B396A 0%, #2c5aa0 100%);
        color: white;
        padding: 24px 32px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(27, 57, 106, 0.15);
    }
    .eval-imp-header h2 { font-size: 1.25rem; font-weight: 600; margin: 0 0 6px 0; }
    .eval-imp-header p { margin: 0; opacity: 0.92; font-size: 0.95rem; }
    .eval-imp-membrete {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .eval-imp-membrete h3 { margin: 0 0 10px 0; font-size: 1rem; color: #1B396A; }
    .eval-imp-scroll {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x proximity;
        scrollbar-width: thin;
        scrollbar-color: #1B396A #e8ecf1;
    }
    .eval-imp-scroll::-webkit-scrollbar { height: 8px; }
    .eval-imp-scroll::-webkit-scrollbar-track { background: #eef1f5; border-radius: 4px; }
    .eval-imp-scroll::-webkit-scrollbar-thumb { background: #1B396A; border-radius: 4px; }
    .eval-imp-doc-card {
        flex: 0 0 auto;
        width: 168px;
        min-width: 168px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid #e1e5eb;
        padding: 10px;
        text-align: center;
        scroll-snap-align: start;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .eval-imp-doc-card:hover {
        border-color: #b8c4d6;
        box-shadow: 0 2px 8px rgba(27, 57, 106, 0.08);
    }
    .eval-imp-doc-card.is-selected {
        border-color: #2e7d32;
        background: #f1f8f2;
        box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.2);
    }
    .eval-imp-doc-card strong {
        display: block;
        font-size: 0.72rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .eval-imp-doc-card span {
        display: block;
        font-size: 0.65rem;
        color: #777;
        margin-top: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .btn-usar-pdf-eval {
        background: #1B396A;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.72rem;
        margin-top: 8px;
        width: 100%;
        font-weight: 600;
    }
    .btn-usar-pdf-eval:hover { background: #2c5aa0; }
    .btn-usar-pdf-eval.active { background: #2e7d32; }
    #evalImpMembreteInfo { margin-top: 10px; font-size: 0.9rem; color: #2e7d32; font-weight: 600; }
    .eval-imp-encabezado {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        border-left: 3px solid #17a2b8;
        max-width: 640px;
    }
    .eval-imp-encabezado h3 { margin: 0 0 4px 0; font-size: 1rem; color: #17a2b8; }
    .eval-imp-encabezado-desc {
        margin: 0 0 12px 0;
        color: #666;
        font-size: 0.85rem;
        line-height: 1.35;
    }
    .eval-imp-encabezado-card {
        background: #f9fafb;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px 14px;
    }
    .eval-imp-encabezado-card label {
        display: block;
        font-weight: 600;
        font-size: 0.78rem;
        margin-bottom: 4px;
        color: #333;
    }
    .eval-imp-encabezado-card input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        padding: 7px 9px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center;
    }
    .eval-imp-encabezado-card input[type="text"]:last-of-type {
        margin-bottom: 0;
    }
    .eval-imp-tabla-wrap {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    .eval-imp-tabla-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 18px;
        background: #1B396A;
        color: #fff;
    }
    .eval-imp-tabla-controls h3 { margin: 0; font-size: 1.05rem; }
    .eval-imp-search {
        padding: 8px 12px 8px 36px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        min-width: 220px;
    }
    .eval-imp-search-wrap { position: relative; }
    .eval-imp-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .eval-imp-tabla { width: 100%; border-collapse: collapse; }
    .eval-imp-tabla th, .eval-imp-tabla td {
        padding: 12px 15px;
        border-bottom: 1px solid #e8ecf1;
        text-align: left;
    }
    .eval-imp-tabla thead th { background: #f4f6f9; font-weight: 700; color: #333; }
    .eval-imp-tabla tbody tr:hover { background: #f8f9fb; }
    .badge-eval { padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; color: #fff; }
    .badge-eval.ok { background: #28a745; }
    .badge-eval.no { background: #ffc107; color: #333; }
    .btn-print-eval {
        background: #007a4d;
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-print-eval:hover { background: #00633e; }
    .btn-print-eval:disabled { background: #adb5bd; cursor: not-allowed; }
    .eval-imp-footer {
        padding: 20px;
        text-align: center;
        border-top: 1px solid #e8ecf1;
    }
    .btn-print-all-eval {
        background: #1B396A;
        color: #fff;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    .btn-print-all-eval:hover { background: #2c5aa0; }
    .btn-print-all-eval:disabled { background: #adb5bd; cursor: not-allowed; }
    .eval-imp-empty { padding: 40px; text-align: center; color: #6c757d; font-style: italic; }
    .eval-imp-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 16px;
        flex-wrap: wrap;
        border-top: 1px solid #e8ecf1;
    }
    .eval-imp-btn-pagina {
        padding: 8px 12px;
        border-radius: 6px;
        background: #e0e0e0;
        color: #333;
        border: none;
        cursor: pointer;
        font-weight: 400;
    }
    .eval-imp-btn-pagina.activo { background: #1B396A; color: #fff; font-weight: 700; }
    .eval-imp-btn-paginacion {
        padding: 8px 12px;
        border-radius: 6px;
        background: #1B396A;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    .eval-imp-btn-paginacion:disabled { background: #ccc; cursor: not-allowed; }
</style>

@php
    $semestreId = $semestre->id_semestre ?? $semestre->id ?? null;
    $tipoProgramaPanel = $tipo_programa_informes_panel ?? \App\Models\Actividad::TIPO_EXTRAESCOLAR;
    $idsActividades = ($actividades ?? collect())->pluck('id_actividad')->filter()->values()->all();
    $filasEvalImp = [];
    if ($idsActividades !== []) {
        $rows = \App\Support\EstudiantesPanelQuery::queryBase($idsActividades, $tipoProgramaPanel)
            ->leftJoin('evaluaciones', 'estudiantes.id_alumno', '=', 'evaluaciones.id_alumno')
            ->select(
                'estudiantes.id_alumno',
                'estudiantes.numero_control',
                'estudiantes.nombre',
                'estudiantes.carrera',
                'actividades.nombre_actividad',
                'evaluaciones.id_evaluacion',
                'evaluaciones.nivel_desempeno',
                'evaluaciones.calificacion_numerica'
            )
            ->orderBy('estudiantes.nombre', 'asc')
            ->get();
        foreach ($rows as $row) {
            $filasEvalImp[] = [
                'id_alumno' => $row->id_alumno,
                'id_evaluacion' => $row->id_evaluacion,
                'numero_control' => $row->numero_control ?? '',
                'nombre' => $row->nombre ?? '',
                'carrera' => $row->carrera ?? '',
                'actividad' => $row->nombre_actividad ?? '',
                'nivel' => $row->nivel_desempeno ?? '',
                'calificacion' => $row->calificacion_numerica ?? '',
                'evaluado' => ! empty($row->id_evaluacion),
            ];
        }
    }
    $totalEvaluados = collect($filasEvalImp)->where('evaluado', true)->count();
    $rutaEvalImpPrint = $rutaEvalImpPrint ?? 'coordinador.valle.evaluacion-formulario.print';
    $rutaEvalImpPrintAll = $rutaEvalImpPrintAll ?? 'coordinador.valle.evaluacion-formulario.print-all';
    $esComplementariasEvalImp = $tipoProgramaPanel === \App\Models\Actividad::TIPO_COMPLEMENTARIA;
    $encabezadoEvalImp = $esComplementariasEvalImp
        ? \App\Support\ComplementariasEvaluacionEncabezado::defaults()
        : [];
@endphp

<div class="eval-imp-container">
    <div class="eval-imp-header">
        <h2><i class="fas fa-clipboard-check"></i> Impresión de evaluación de desempeño</h2>
        <p>Selecciona el PDF membretado e imprime el formato de evaluación por estudiante o todos los evaluados.</p>
    </div>

    <div class="eval-imp-membrete">
        <h3><i class="fas fa-file-pdf"></i> PDF membretado</h3>
        @if(isset($documentos) && $documentos->count() > 0)
            <div class="eval-imp-scroll" role="list" aria-label="PDFs membretados disponibles">
                @foreach($documentos as $doc)
                    <div class="eval-imp-doc-card" role="listitem">
                        <i class="fas fa-file-pdf" style="color:#c62828;font-size:1.35rem;" aria-hidden="true"></i>
                        <strong title="{{ $doc->nombre ?? 'Documento' }}">{{ $doc->nombre ?? 'Documento' }}</strong>
                        <span title="{{ basename($doc->archivo ?? '') }}">{{ basename($doc->archivo ?? '') }}</span>
                        <button type="button" class="btn-usar-pdf-eval" data-id="{{ $doc->id }}" data-archivo="{{ asset($doc->archivo) }}">
                            Usar PDF
                        </button>
                    </div>
                @endforeach
            </div>
            <div id="evalImpMembreteInfo"></div>
        @else
            <p style="margin:0;color:#666;">No hay PDF membretado para este semestre. Puede imprimir sin fondo o cargar uno en documentos.</p>
        @endif
    </div>

    @if($esComplementariasEvalImp)
    <div class="eval-imp-encabezado">
        <h3><i class="fas fa-heading"></i> Encabezado del formato</h3>
        <p class="eval-imp-encabezado-desc">Texto institucional que aparece arriba del documento impreso.</p>
        <div class="eval-imp-encabezado-card">
            <label for="evalImpEnc0">Instituto</label>
            <input type="text" id="evalImpEnc0" value="{{ $encabezadoEvalImp[0] ?? '' }}" data-default="{{ $encabezadoEvalImp[0] ?? '' }}">
            <label for="evalImpEnc1">Subdirección</label>
            <input type="text" id="evalImpEnc1" value="{{ $encabezadoEvalImp[1] ?? '' }}" data-default="{{ $encabezadoEvalImp[1] ?? '' }}">
            <label for="evalImpEnc2">Departamento</label>
            <input type="text" id="evalImpEnc2" value="{{ $encabezadoEvalImp[2] ?? '' }}" data-default="{{ $encabezadoEvalImp[2] ?? '' }}">
            <label for="evalImpEnc3">Título del formato</label>
            <input type="text" id="evalImpEnc3" value="{{ $encabezadoEvalImp[3] ?? '' }}" data-default="{{ $encabezadoEvalImp[3] ?? '' }}">
        </div>
    </div>
    @endif

    <div class="eval-imp-tabla-wrap">
        <div class="eval-imp-tabla-controls">
            <h3>Estudiantes ({{ $totalEvaluados }} evaluados)</h3>
            <div class="eval-imp-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="eval-imp-search" id="evalImpBuscar" placeholder="Buscar alumno...">
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="eval-imp-tabla">
                <thead>
                    <tr>
                        <th>No. Control</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Actividad</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="evalImpTbody"></tbody>
            </table>
        </div>
        <div class="eval-imp-pagination">
            <button type="button" class="eval-imp-btn-paginacion" id="evalImpBtnAnterior" disabled>
                <i class="fas fa-chevron-left"></i> Anterior
            </button>
            <div id="evalImpPaginasContainer" style="display:flex;gap:4px;"></div>
            <button type="button" class="eval-imp-btn-paginacion" id="evalImpBtnSiguiente" disabled>
                Siguiente <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="eval-imp-footer">
            <button type="button" class="btn-print-all-eval" id="btnEvalImpTodos" @if($totalEvaluados === 0) disabled @endif>
                <i class="fas fa-print"></i> Imprimir todos ({{ $totalEvaluados }})
            </button>
        </div>
    </div>
</div>

<iframe id="evalImpPrintFrame" title="Impresión evaluación" aria-hidden="true" style="position:fixed;width:0;height:0;border:0;visibility:hidden;"></iframe>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('coordinador.partials.swal_alerta_helper')

<script>
(function () {
    var filas = @json($filasEvalImp);
    var semestreId = @json($semestreId);
    var documentosCount = {{ isset($documentos) ? $documentos->count() : 0 }};
    var rutaUno = @json(route($rutaEvalImpPrint, ['id_evaluacion' => '__ID__']));
    var rutaTodos = @json(route($rutaEvalImpPrintAll, ['id' => '__ID__']));
    window.pdfEvalImpSeleccionado = null;
    var estudiantesPorPagina = 10;
    var paginaActual = 1;
    var totalPaginas = 1;
    var filasFiltradas = [];
    var esComplementariasEvalImp = @json($esComplementariasEvalImp);

    function guardarEncabezadoEvalImpPayload() {
        if (!esComplementariasEvalImp) return;
        var lineas = [];
        for (var i = 0; i < 4; i++) {
            var el = document.getElementById('evalImpEnc' + i);
            lineas.push(el ? String(el.value || '').trim() : '');
        }
        try {
            sessionStorage.setItem('complementariasEvalImpEncabezadoPayload', JSON.stringify({ lineas: lineas }));
        } catch (e) {}
    }

    function requiereMembrete() {
        if (documentosCount > 0 && (!window.pdfEvalImpSeleccionado || !window.pdfEvalImpSeleccionado.id)) {
            swalAlerta('Selecciona el PDF membretado con el botón "Usar PDF".');
            return false;
        }
        return true;
    }

    function urlConMembrete(base) {
        var url = base;
        if (window.pdfEvalImpSeleccionado && window.pdfEvalImpSeleccionado.id) {
            url += (url.indexOf('?') >= 0 ? '&' : '?') + 'id_documento=' + encodeURIComponent(window.pdfEvalImpSeleccionado.id);
        }
        return url;
    }

    function abrirImpresion(url) {
        guardarEncabezadoEvalImpPayload();
        var frame = document.getElementById('evalImpPrintFrame');
        if (!frame) {
            frame = document.createElement('iframe');
            frame.id = 'evalImpPrintFrame';
            frame.title = 'Impresión evaluación';
            frame.setAttribute('aria-hidden', 'true');
            frame.style.cssText = 'position:fixed;width:0;height:0;border:0;visibility:hidden;';
            document.body.appendChild(frame);
        }
        frame.src = url;
    }

    function filtrarFilas(filtro) {
        var q = (filtro || '').toLowerCase().trim();
        return filas.filter(function (f) {
            if (!q) return true;
            return (f.nombre || '').toLowerCase().indexOf(q) >= 0
                || (f.numero_control || '').toLowerCase().indexOf(q) >= 0
                || (f.carrera || '').toLowerCase().indexOf(q) >= 0;
        });
    }

    function actualizarPaginacionUI() {
        var container = document.getElementById('evalImpPaginasContainer');
        var btnAnterior = document.getElementById('evalImpBtnAnterior');
        var btnSiguiente = document.getElementById('evalImpBtnSiguiente');
        if (!container) return;

        container.innerHTML = '';
        if (totalPaginas <= 1) {
            if (btnAnterior) btnAnterior.disabled = true;
            if (btnSiguiente) btnSiguiente.disabled = true;
            return;
        }

        var inicio = Math.max(1, paginaActual - 2);
        var fin = Math.min(totalPaginas, inicio + 4);
        if (fin - inicio < 4) inicio = Math.max(1, fin - 4);

        for (var i = inicio; i <= fin; i++) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'eval-imp-btn-pagina' + (i === paginaActual ? ' activo' : '');
            btn.textContent = String(i);
            btn.addEventListener('click', (function (p) {
                return function () { renderTablaPagina(p); };
            })(i));
            container.appendChild(btn);
        }

        if (btnAnterior) btnAnterior.disabled = paginaActual === 1;
        if (btnSiguiente) btnSiguiente.disabled = paginaActual === totalPaginas;
    }

    function renderTablaPagina(pagina) {
        var tbody = document.getElementById('evalImpTbody');
        if (!tbody) return;

        if (pagina < 1) pagina = 1;
        if (pagina > totalPaginas) pagina = totalPaginas;
        paginaActual = pagina;

        if (filasFiltradas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="eval-imp-empty">No hay estudiantes que coincidan con la búsqueda.</td></tr>';
            actualizarPaginacionUI();
            return;
        }

        var inicio = (paginaActual - 1) * estudiantesPorPagina;
        var fin = inicio + estudiantesPorPagina;
        var paginaFilas = filasFiltradas.slice(inicio, fin);

        tbody.innerHTML = paginaFilas.map(function (f) {
            var badge = f.evaluado
                ? '<span class="badge-eval ok">Evaluado</span>'
                : '<span class="badge-eval no">Pendiente</span>';
            var btn = f.evaluado
                ? '<button type="button" class="btn-print-eval" data-id-eval="' + f.id_evaluacion + '"><i class="fas fa-print"></i> Imprimir</button>'
                : '<button type="button" class="btn-print-eval" disabled title="Evalúe al estudiante en Constancia de Cumplimiento"><i class="fas fa-print"></i> Imprimir</button>';
            return '<tr>'
                + '<td>' + escapeHtml(f.numero_control) + '</td>'
                + '<td>' + escapeHtml(f.nombre) + '</td>'
                + '<td>' + escapeHtml(f.carrera) + '</td>'
                + '<td>' + escapeHtml(f.actividad) + '</td>'
                + '<td>' + badge + '</td>'
                + '<td>' + btn + '</td>'
                + '</tr>';
        }).join('');

        tbody.querySelectorAll('.btn-print-eval:not([disabled])').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!requiereMembrete()) return;
                var idEval = btn.getAttribute('data-id-eval');
                if (!idEval) return;
                abrirImpresion(urlConMembrete(rutaUno.replace('__ID__', idEval)));
            });
        });

        actualizarPaginacionUI();
    }

    function renderTabla(filtro) {
        filasFiltradas = filtrarFilas(filtro);
        totalPaginas = Math.max(1, Math.ceil(filasFiltradas.length / estudiantesPorPagina));
        paginaActual = 1;
        renderTablaPagina(1);
    }

    function escapeHtml(s) {
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    document.querySelectorAll('.btn-usar-pdf-eval').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.btn-usar-pdf-eval').forEach(function (b) { b.classList.remove('active'); });
            document.querySelectorAll('.eval-imp-doc-card').forEach(function (c) { c.classList.remove('is-selected'); });
            btn.classList.add('active');
            var card = btn.closest('.eval-imp-doc-card');
            if (card) card.classList.add('is-selected');
            window.pdfEvalImpSeleccionado = {
                id: btn.getAttribute('data-id'),
                archivo: btn.getAttribute('data-archivo')
            };
            var nombre = card ? (card.querySelector('strong')?.textContent || '') : '';
            var info = document.getElementById('evalImpMembreteInfo');
            if (info) info.textContent = nombre ? ('PDF seleccionado: ' + nombre) : 'PDF membretado seleccionado.';
            try {
                sessionStorage.setItem('membreteDocId_evalImp_' + (semestreId || 'global'), btn.getAttribute('data-id'));
            } catch (e) {}
        });
    });

    var buscar = document.getElementById('evalImpBuscar');
    if (buscar) {
        buscar.addEventListener('input', function () { renderTabla(buscar.value); });
    }

    var btnAnterior = document.getElementById('evalImpBtnAnterior');
    var btnSiguiente = document.getElementById('evalImpBtnSiguiente');
    if (btnAnterior) {
        btnAnterior.addEventListener('click', function () {
            if (paginaActual > 1) renderTablaPagina(paginaActual - 1);
        });
    }
    if (btnSiguiente) {
        btnSiguiente.addEventListener('click', function () {
            if (paginaActual < totalPaginas) renderTablaPagina(paginaActual + 1);
        });
    }

    var btnTodos = document.getElementById('btnEvalImpTodos');
    if (btnTodos) {
        btnTodos.addEventListener('click', function () {
            if (!semestreId) {
                swalAlerta('No se encontró el semestre.');
                return;
            }
            if (!requiereMembrete()) return;
            abrirImpresion(urlConMembrete(rutaTodos.replace('__ID__', semestreId)));
        });
    }

    try {
        var saved = sessionStorage.getItem('membreteDocId_evalImp_' + (semestreId || 'global'));
        if (saved) {
            var btnSaved = document.querySelector('.btn-usar-pdf-eval[data-id="' + saved + '"]');
            if (btnSaved) btnSaved.click();
        }
    } catch (e) {}

    renderTabla('');
})();
</script>
