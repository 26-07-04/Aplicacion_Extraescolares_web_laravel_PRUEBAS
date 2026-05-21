<style>
    .resultados-container {
        padding: 24px;
        background: #f8f9fa;
        min-height: calc(100vh - 120px);
    }

    .resultados-header {
        background: linear-gradient(135deg, #d87b15 0%, #f39c12 100%);
        color: white;
        padding: 24px 32px;
        border-radius: 12px;
        margin-bottom: 28px;
        box-shadow: 0 8px 24px rgba(216, 123, 21, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .resultados-header-content h2 {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0 0 6px 0;
        letter-spacing: -0.3px;
    }

    .resultados-header-content p {
        font-size: 0.95rem;
        margin: 0;
        opacity: 0.95;
    }

    .btn-imprimir {
        background: #1B396A;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        white-space: nowrap;
    }

    .btn-imprimir:hover {
        background: #2c5aa0;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .btn-imprimir i {
        font-size: 1rem;
    }

    .resultados-membrete-block {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        width: 100%;
    }
    .resultados-membrete-block h3 {
        margin: 0 0 10px 0;
        font-size: 1rem;
        color: #1B396A;
    }
    .membrete-scroll {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 4px 2px 12px;
        margin: 0 -2px;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x proximity;
        scrollbar-width: thin;
        scrollbar-color: #1B396A #e8ecf1;
    }
    .membrete-scroll::-webkit-scrollbar {
        height: 8px;
    }
    .membrete-scroll::-webkit-scrollbar-track {
        background: #eef1f5;
        border-radius: 4px;
    }
    .membrete-scroll::-webkit-scrollbar-thumb {
        background: #1B396A;
        border-radius: 4px;
    }
    .documento-card-resultados {
        flex: 0 0 auto;
        width: 168px;
        min-width: 168px;
        max-width: 168px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid #e1e5eb;
        padding: 10px 10px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 6px;
        scroll-snap-align: start;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .documento-card-resultados:hover {
        border-color: #b8c4d6;
        box-shadow: 0 2px 8px rgba(27, 57, 106, 0.08);
    }
    .documento-card-resultados.is-selected {
        border-color: #2e7d32;
        background: #f1f8f2;
        box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.2);
    }
    .documento-card-resultados .doc-icon {
        color: #c62828;
        font-size: 1.35rem;
        line-height: 1;
    }
    .documento-card-resultados .doc-info {
        width: 100%;
        min-width: 0;
    }
    .documento-card-resultados .doc-info strong {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: #222;
        line-height: 1.25;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .documento-card-resultados .doc-info span {
        display: block;
        font-size: 0.65rem;
        color: #777;
        margin-top: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .btn-usar-pdf-resultados {
        background: #1B396A;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        cursor: pointer;
        width: 100%;
        white-space: nowrap;
    }
    .btn-usar-pdf-resultados:hover {
        background: #2c5aa0;
    }
    .btn-usar-pdf-resultados.active {
        background: #2e7d32;
        outline: none;
    }
    .membrete-seleccion-info {
        margin-top: 6px;
        font-size: 0.82rem;
        color: #2e7d32;
        min-height: 1.2em;
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
        padding: 4px 12px 8px;
        flex-wrap: wrap;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #1B396A 0%, #2c5aa0 100%);
        color: white;
        padding: 18px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .table-header .search-box {
        position: relative;
        max-width: 320px;
    }

    .table-header .search-box input {
        padding: 10px 40px 10px 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        font-size: 0.95rem;
        width: 100%;
        transition: all 0.3s;
    }
    .table-header .search-box i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
        pointer-events: none;
    }
    .table-header .search-box input::placeholder {
        color: rgba(255, 255, 255, 0.85);
        opacity: 1;
    }
    .table-header .search-box input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: rgba(255, 255, 255, 0.85);
        opacity: 1;
    }
    .table-header .search-box input::-moz-placeholder { /* Firefox 19+ */
        color: rgba(255, 255, 255, 0.85);
        opacity: 1;
    }
    .table-header .search-box input:-ms-input-placeholder { /* IE 10+ */
        color: rgba(255, 255, 255, 0.85);
        opacity: 1;
    }
    .table-header .search-box input:focus {
        outline: none;
        border-color: rgba(255,255,255,0.85);
        box-shadow: 0 0 0 3px rgba(255,255,255,0.07);
        background: rgba(255,255,255,0.08);
        color: #fff;
        caret-color: #ffffff;
        -webkit-text-fill-color: #ffffff;
    }

    .table-wrapper {
        overflow-x: auto;
        padding: 0;
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .results-table thead {
        background: #1B396A;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .results-table th {
        padding: 14px 12px;
        text-align: left;
        font-weight: 600;
        color: #ffffff;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #2c5aa0;
        white-space: nowrap;
    }

    .results-table td {
        padding: 18px 14px;
        color: #495057;
        vertical-align: middle;
    }

    .firma-column {
        text-align: center;
        min-width: 140px;
    }

    .firma-placeholder {
        padding: 0;
        border: none;
        color: #6c757d;
        font-size: 0.85rem;
        background: transparent;
    }

    .no-resultados {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .liberaciones-oficio-section {
        max-width: 640px;
        margin-bottom: 20px;
    }
    .liberaciones-oficio-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        align-items: stretch;
    }
    @media (max-width: 700px) {
        .liberaciones-oficio-section {
            max-width: 100%;
        }
        .liberaciones-oficio-grid {
            grid-template-columns: 1fr;
        }
    }
    .liberaciones-oficio-section .liberaciones-firmas-block {
        background: #fff;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-left: 3px solid #17a2b8;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-width: 0;
    }
    .liberaciones-oficio-section .liberaciones-firmas-block h3 {
        margin: 0 0 4px 0;
        color: #17a2b8;
        font-size: 0.82rem;
    }
    .liberaciones-oficio-section .liberaciones-firmas-block h3 i {
        font-size: 0.78rem;
    }
    .liberaciones-oficio-section .liberaciones-firmas-desc {
        margin: 0 0 8px 0;
        color: #666;
        font-size: 0.72rem;
        line-height: 1.3;
    }
    .liberaciones-oficio-section .liberaciones-firma-card {
        background: #f9fafb;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 10px;
        flex: 1 1 auto;
        width: 100%;
        box-sizing: border-box;
    }
    .liberaciones-oficio-section .liberaciones-firma-recuadro {
        min-height: 36px;
        margin: 4px 0 6px;
        border-bottom: 1px solid #333;
        background: #fff;
    }
    .liberaciones-oficio-section .liberaciones-firma-card label {
        display: block;
        font-weight: 600;
        font-size: 0.72rem;
        margin-bottom: 3px;
        color: #333;
    }
    .liberaciones-oficio-section .liberaciones-firma-card input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        padding: 5px 7px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .liberaciones-oficio-section .liberaciones-firma-cargo,
    .liberaciones-oficio-section .liberaciones-editable-cargo {
        font-size: 0.72rem;
        color: #495057;
        margin: 0;
        min-height: 1.6em;
        line-height: 1.25;
    }
    .liberaciones-oficio-section .liberaciones-firma-cargo {
        text-align: center;
    }
    .liberaciones-oficio-section .liberaciones-editable-cargo {
        font-weight: 700;
    }
    .liberaciones-oficio-section .liberaciones-firma-cargo[contenteditable="true"],
    .liberaciones-oficio-section .liberaciones-editable-cargo[contenteditable="true"] {
        background: #fffef5;
        outline: 1px dashed #17a2b8;
    }
    .liberaciones-oficio-acciones {
        margin-top: 12px;
        display: flex;
        justify-content: flex-start;
    }

</style>

@php
    $firmasLib = $firmasLiberaciones ?? \App\Support\ComplementariasLiberacionesFirmas::defaults();
@endphp

<div class="resultados-container">
    <div class="resultados-header">
        <div class="resultados-header-content">
            <h2>
                <i class="fas fa-chart-line"></i>
                Resultados de evaluaciones (complementarias)
            </h2>
            <p>
                <i class="fas fa-building"></i> {{ $unidad ?? ($unidadEtiqueta ?? 'Unidad Académica') }}
                | 
                <i class="fas fa-calendar-alt"></i> {{ $semestre->nombre ?? 'Semestre Actual' }}
            </p>
        </div>
    </div>

    <div class="resultados-membrete-block">
        <h3><i class="fas fa-file-pdf"></i> PDF membretado</h3>
        @if(isset($documentos) && $documentos->isNotEmpty())
            <div class="membrete-scroll" role="list" aria-label="PDFs membretados disponibles">
                @foreach($documentos as $doc)
                    <div class="documento-card-resultados" role="listitem">
                        <i class="fas fa-file-pdf doc-icon" aria-hidden="true"></i>
                        <div class="doc-info">
                            <strong title="{{ $doc->nombre ?? 'Documento' }}">{{ $doc->nombre ?? 'Documento' }}</strong>
                            <span title="{{ basename($doc->archivo ?? '') }}">{{ basename($doc->archivo ?? '') }}</span>
                        </div>
                        <button type="button" class="btn-usar-pdf-resultados" data-id="{{ $doc->id }}" data-archivo="{{ asset($doc->archivo) }}">
                            Usar PDF
                        </button>
                    </div>
                @endforeach
            </div>
            <div id="pdfResultadosSeleccionadoInfo" class="membrete-seleccion-info"></div>
        @else
            <p style="margin:0; color:#666;">No hay PDF membretado cargado para este semestre. Puedes imprimir sin fondo o subir uno desde el panel de documentos.</p>
        @endif
    </div>

    <div class="liberaciones-oficio-section">
    <div class="liberaciones-oficio-grid">
    <div class="liberaciones-firmas-block">
        <h3><i class="fas fa-user-tie"></i> Destinatario del oficio</h3>
        <p class="liberaciones-firmas-desc">Nombre y cargo de quien recibe el oficio.</p>
        <div class="liberaciones-firma-card">
            <label for="libDestinatarioNombre">Nombre</label>
            <input type="text" id="libDestinatarioNombre" value="{{ $firmasLib['destinatario']['nombre'] ?? '' }}" data-default="{{ $firmasLib['destinatario']['nombre'] ?? '' }}">
            <label for="libDestinatarioCargo" style="margin-top:6px;">Cargo</label>
            <p id="libDestinatarioCargo" class="liberaciones-editable-cargo" data-default="{{ $firmasLib['destinatario']['cargo'] ?? '' }}" title="Doble clic para editar">{{ $firmasLib['destinatario']['cargo'] ?? '' }}</p>
        </div>
    </div>

    <div class="liberaciones-firmas-block">
        <h3><i class="fas fa-signature"></i> Firma del oficio</h3>
        <p class="liberaciones-firmas-desc">Firma del documento. Cargo: doble clic para editar.</p>
        <div class="liberaciones-firma-card">
            <label for="libFirmaNombre">Nombre del firmante</label>
            <div class="liberaciones-firma-recuadro" title="Espacio para firma autógrafa"></div>
            <input type="text" id="libFirmaNombre" value="{{ $firmasLib['firmante']['nombre'] ?? '' }}" data-default="{{ $firmasLib['firmante']['nombre'] ?? '' }}">
            <p id="libFirmaCargo" class="liberaciones-firma-cargo liberaciones-editable-cargo" data-default="{{ $firmasLib['firmante']['cargo'] ?? '' }}" title="Doble clic para editar">{{ $firmasLib['firmante']['cargo'] ?? '' }}</p>
        </div>
    </div>
    </div>
    <div class="liberaciones-oficio-acciones">
        <button type="button" class="btn-imprimir" onclick="abrirImpresion()">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3>
                <i class="fas fa-table"></i> 
                Listado de Estudiantes Evaluados
            </h3>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar estudiante...">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <div class="table-wrapper">
            @php
                $evaluacionesPorPagina = 10;
                $totalEvaluaciones = $evaluaciones->count();
                $totalPaginas = ceil($totalEvaluaciones / $evaluacionesPorPagina);
                $paginaActual = 1;
                $evaluacionesPagina = $evaluaciones->slice(0, $evaluacionesPorPagina);
            @endphp

            @if($totalEvaluaciones > 0)
                <table class="results-table" id="resultsTable">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>NOMBRE</th>
                            <th>NO. CONTROL</th>
                            <th>CARRERA</th>
                        </tr>
                    </thead>
                    <tbody id="evaluacionesTableBody">
                        @foreach($evaluacionesPagina as $index => $evaluacion)
                            <tr>
                                <td class="numero">{{ $index + 1 }}</td>
                                <td class="nombre">{{ $evaluacion->estudiante->nombre ?? 'N/A' }}</td>
                                <td class="control">{{ $evaluacion->estudiante->numero_control ?? 'N/A' }}</td>
                                <td>{{ $evaluacion->estudiante->carrera ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-container">
                    <button id="btnPaginaAnterior" class="btn-paginacion" style="padding:8px 16px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer; display:none; font-weight:600;">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </button>
                    <div id="paginasContainer" style="display:flex; gap:4px;">
                        @for($i = 1; $i <= min($totalPaginas, 5); $i++)
                            <button class="btn-pagina" data-pagina="{{ $i }}" style="padding:8px 14px; border-radius:6px; background:{{ $i === 1 ? '#1B396A' : '#e0e0e0' }}; color:{{ $i === 1 ? '#fff' : '#333' }}; border:none; cursor:pointer; font-weight:{{ $i === 1 ? '700' : '500' }};">
                                {{ $i }}
                            </button>
                        @endfor
                        @if($totalPaginas > 5)
                            <span style="padding:8px 12px; color:#999;">...</span>
                            <button class="btn-pagina" data-pagina="{{ $totalPaginas }}" style="padding:8px 14px; border-radius:6px; background:#e0e0e0; color:#333; border:none; cursor:pointer; font-weight:500;">
                                {{ $totalPaginas }}
                            </button>
                        @endif
                    </div>
                    <button id="btnPaginaSiguiente" class="btn-paginacion" style="padding:8px 16px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer; font-weight:600;">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            @else
                <div class="no-resultados">
                    <i class="fas fa-inbox"></i>
                    <h4>No hay resultados disponibles</h4>
                    <p>Aún no se han registrado evaluaciones para este semestre en tu unidad académica.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    window.pdfResultadosSeleccionado = window.pdfResultadosSeleccionado || null;

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-usar-pdf-resultados').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-usar-pdf-resultados').forEach(function(b) {
                    b.classList.remove('active');
                });
                document.querySelectorAll('.documento-card-resultados').forEach(function(card) {
                    card.classList.remove('is-selected');
                });
                btn.classList.add('active');
                var card = btn.closest('.documento-card-resultados');
                if (card) {
                    card.classList.add('is-selected');
                }
                window.pdfResultadosSeleccionado = {
                    id: btn.getAttribute('data-id'),
                    archivo: btn.getAttribute('data-archivo') || ''
                };
                var info = document.getElementById('pdfResultadosSeleccionadoInfo');
                if (info) {
                    var nombre = card ? (card.querySelector('.doc-info strong')?.textContent || '') : '';
                    info.textContent = nombre ? ('PDF seleccionado: ' + nombre.trim()) : 'PDF membretado seleccionado.';
                }
            });
        });
    });

    const allEvaluaciones = {!! json_encode($evaluaciones->values()->toArray()) !!};
    const evaluacionesPorPagina = 10;
    const totalPaginas = {{ $totalPaginas }};
    let paginaActual = 1;
    let filtroActual = '';

    function renderizarPagina(pagina, filtro = '') {
        const tableBody = document.getElementById('evaluacionesTableBody');

        if (!tableBody) return;
        
        let evaluacionesFiltradas = allEvaluaciones;
        if (filtro) {
            evaluacionesFiltradas = allEvaluaciones.filter(ev => {
                const nombre = (ev.estudiante?.nombre || '').toLowerCase();
                const control = (ev.estudiante?.numero_control || '').toLowerCase();
                const carrera = (ev.estudiante?.carrera || '').toLowerCase();
                const actividad = (ev.actividad?.nombre_actividad || '').toLowerCase();
                const resultado = (ev.nivel_desempeno || '').toLowerCase();
                
                return nombre.includes(filtro) || control.includes(filtro) || 
                       carrera.includes(filtro) || actividad.includes(filtro) || 
                       resultado.includes(filtro);
            });
        }

        const totalFiltradas = evaluacionesFiltradas.length;
        const totalPaginasFiltradas = Math.ceil(totalFiltradas / evaluacionesPorPagina);

        if (pagina > totalPaginasFiltradas) pagina = totalPaginasFiltradas;
        if (pagina < 1) pagina = 1;

        paginaActual = pagina;

        const inicio = (pagina - 1) * evaluacionesPorPagina;
        const fin = inicio + evaluacionesPorPagina;
        const evaluacionesPagina = evaluacionesFiltradas.slice(inicio, fin);

        tableBody.innerHTML = '';

        evaluacionesPagina.forEach((ev, index) => {
            const numeroGlobal = inicio + index + 1;
            const row = `
                <tr>
                    <td class="numero">${numeroGlobal}</td>
                    <td class="nombre">${ev.estudiante?.nombre || 'N/A'}</td>
                    <td class="control">${ev.estudiante?.numero_control || 'N/A'}</td>
                    <td>${ev.estudiante?.carrera || 'N/A'}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        actualizarBotonesPaginacion(pagina, totalPaginasFiltradas);
    }

    function actualizarBotonesPaginacion(pagina, totalPaginas) {
        const btnAnterior = document.getElementById('btnPaginaAnterior');
        const btnSiguiente = document.getElementById('btnPaginaSiguiente');
        const paginasContainer = document.getElementById('paginasContainer');
        
        btnAnterior.style.display = pagina > 1 ? 'block' : 'none';
        btnSiguiente.style.display = pagina < totalPaginas ? 'block' : 'none';
        paginasContainer.innerHTML = '';

        const maxBotones = 5;
        let inicio = Math.max(1, pagina - 2);
        let fin = Math.min(totalPaginas, inicio + maxBotones - 1);

        if (fin - inicio < maxBotones - 1) {
            inicio = Math.max(1, fin - maxBotones + 1);
        }

        for (let i = inicio; i <= fin; i++) {
            const isActive = i === pagina;
            const btn = document.createElement('button');
            btn.className = 'btn-pagina';
            btn.dataset.pagina = i;
            btn.textContent = i;
            btn.style.cssText = `padding:8px 14px; border-radius:6px; background:${isActive ? '#1B396A' : '#e0e0e0'}; color:${isActive ? '#fff' : '#333'}; border:none; cursor:pointer; font-weight:${isActive ? '700' : '500'};`;
            btn.addEventListener('click', () => renderizarPagina(i, filtroActual));
            paginasContainer.appendChild(btn);
        }

        if (fin < totalPaginas) {
            const dots = document.createElement('span');
            dots.textContent = '...';
            dots.style.cssText = 'padding:8px 12px; color:#999;';
            paginasContainer.appendChild(dots);

            const btnUltima = document.createElement('button');
            btnUltima.className = 'btn-pagina';
            btnUltima.dataset.pagina = totalPaginas;
            btnUltima.textContent = totalPaginas;
            btnUltima.style.cssText = 'padding:8px 14px; border-radius:6px; background:#e0e0e0; color:#333; border:none; cursor:pointer; font-weight:500;';
            btnUltima.addEventListener('click', () => renderizarPagina(totalPaginas, filtroActual));
            paginasContainer.appendChild(btnUltima);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const btnAnterior = document.getElementById('btnPaginaAnterior');
        const btnSiguiente = document.getElementById('btnPaginaSiguiente');

        if (searchInput) {
            let timeoutId;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    filtroActual = this.value.toLowerCase();
                    renderizarPagina(1, filtroActual);
                }, 300);
            });
        }

        if (btnAnterior) {
            btnAnterior.addEventListener('click', () => {
                if (paginaActual > 1) {
                    renderizarPagina(paginaActual - 1, filtroActual);
                }
            });
        }

        if (btnSiguiente) {
            btnSiguiente.addEventListener('click', () => {
                const totalPaginasFiltradas = Math.ceil(
                    (filtroActual ? allEvaluaciones.filter(ev => {
                        const nombre = (ev.estudiante?.nombre || '').toLowerCase();
                        const control = (ev.estudiante?.numero_control || '').toLowerCase();
                        const carrera = (ev.estudiante?.carrera || '').toLowerCase();
                        return nombre.includes(filtroActual) || control.includes(filtroActual) || carrera.includes(filtroActual);
                    }).length : allEvaluaciones.length) / evaluacionesPorPagina
                );

                if (paginaActual < totalPaginasFiltradas) {
                    renderizarPagina(paginaActual + 1, filtroActual);
                }
            });
        }

        renderizarPagina(1);
    });

    function textoCargoLiberaciones(el) {
        if (!el) return '';
        var t = String(el.innerText || el.textContent || '').trim();
        return t || String(el.getAttribute('data-default') || '').trim();
    }

    function inicializarFirmasLiberaciones() {
        document.querySelectorAll('.liberaciones-editable-cargo').forEach(function (el) {
            el.addEventListener('dblclick', function (e) {
                e.preventDefault();
                this.contentEditable = 'true';
                this.focus();
            });
            el.addEventListener('blur', function () {
                this.contentEditable = 'false';
                var d = this.getAttribute('data-default') || '';
                if (!(this.textContent || '').trim()) this.textContent = d;
            });
            el.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); this.blur(); }
            });
        });
    }

    function recolectarPayloadLiberaciones() {
        var destNombre = document.getElementById('libDestinatarioNombre');
        var destCargo = document.getElementById('libDestinatarioCargo');
        var nombre = document.getElementById('libFirmaNombre');
        var cargo = document.getElementById('libFirmaCargo');
        return {
            destinatario: {
                nombre: destNombre ? String(destNombre.value || destNombre.getAttribute('data-default') || '').trim() : '',
                cargo: textoCargoLiberaciones(destCargo)
            },
            firmante: {
                nombre: nombre ? String(nombre.value || nombre.getAttribute('data-default') || '').trim() : '',
                cargo: textoCargoLiberaciones(cargo)
            }
        };
    }

    inicializarFirmasLiberaciones();

    function abrirImpresion() {
        const semestreId = {{ $semestre->id_semestre ?? 'null' }};
        if (!semestreId) {
            alert('No se encontró el semestre para imprimir.');
            return;
        }
        const documentosCount = {{ isset($documentos) ? $documentos->count() : 0 }};
        if (documentosCount > 0 && (!window.pdfResultadosSeleccionado || !window.pdfResultadosSeleccionado.id)) {
            alert('Selecciona el PDF membretado con el botón "Usar PDF".');
            return;
        }
        try {
            sessionStorage.setItem('complementariasLiberacionesPrintPayload', JSON.stringify(recolectarPayloadLiberaciones()));
        } catch (e) {}

        const baseUrl = `{{ route($resultadosPrintRoute ?? 'coordinador.valle.resultados.print.complementarias', ['id' => '__ID__']) }}`.replace('__ID__', semestreId);
        let url = baseUrl;
        if (window.pdfResultadosSeleccionado && window.pdfResultadosSeleccionado.id) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + 'id_documento=' + encodeURIComponent(window.pdfResultadosSeleccionado.id);
        }
        const iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.right = '0';
        iframe.style.bottom = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        iframe.src = url;
        document.body.appendChild(iframe);
        iframe.addEventListener('load', function() {
            setTimeout(function() {
                try {
                    document.body.removeChild(iframe);
                } catch (e) {}
            }, 180000);
        });
    }
</script>

<style media="print">
    @media print {
        .resultados-header {
            background: #d87b15 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .btn-imprimir {
            display: none !important;
        }
        
        .table-header .search-box {
            display: none !important;
        }
        
        .results-table thead {
            background: #1B396A !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .badge-resultado {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>