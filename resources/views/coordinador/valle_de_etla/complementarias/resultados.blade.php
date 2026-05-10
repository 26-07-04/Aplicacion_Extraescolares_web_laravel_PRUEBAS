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

</style>

<div class="resultados-container">
    <div class="resultados-header">
        <div class="resultados-header-content">
            <h2>
                <i class="fas fa-chart-line"></i>
                Resultados de evaluaciones (complementarias)
            </h2>
            <p>
                <i class="fas fa-building"></i> {{ $unidad ?? 'Unidad Académica Valle de Etla' }}
                | 
                <i class="fas fa-calendar-alt"></i> {{ $semestre->nombre ?? 'Semestre Actual' }}
            </p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:nowrap; margin-top:-30px;">
            <button class="btn-imprimir" onclick="abrirImpresion('cultural')">
                <i class="fas fa-print"></i>
                Lista Cultural
            </button>
            <button class="btn-imprimir" onclick="abrirImpresion('deportiva')">
                <i class="fas fa-print"></i>
                Lista Deportiva
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
                            <th>SEM</th>
                            <th>ACTIVIDAD</th>
                            <th>RESULTADO</th>
                            <th class="firma-column">FIRMA DE ENTERADO</th>
                        </tr>
                    </thead>
                    <tbody id="evaluacionesTableBody">
                        @foreach($evaluacionesPagina as $index => $evaluacion)
                            <tr>
                                <td class="numero">{{ $index + 1 }}</td>
                                <td class="nombre">{{ $evaluacion->estudiante->nombre ?? 'N/A' }}</td>
                                <td class="control">{{ $evaluacion->estudiante->numero_control ?? 'N/A' }}</td>
                                <td>{{ $evaluacion->estudiante->carrera ?? 'N/A' }}</td>
                                <td>{{ $evaluacion->estudiante->semestre ?? 'N/A' }}</td>
                                <td>{{ $evaluacion->actividad->nombre_actividad ?? 'N/A' }}</td>
                                <td>
                                    {{ $evaluacion->nivel_desempeno ?? 'N/A' }}
                                </td>
                                <td class="firma-column">
                                    <div class="firma-placeholder"></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-container" style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:20px; padding:20px; flex-wrap:wrap;">
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
                    
                    <span style="margin-left:16px; font-size:13px; color:#666; font-weight:500;">
                        Página <span id="numeroPagina">1</span> de {{ $totalPaginas }} ({{ $totalEvaluaciones }} evaluaciones)
                    </span>
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
    const allEvaluaciones = {!! json_encode($evaluaciones->values()->toArray()) !!};
    const evaluacionesPorPagina = 10;
    const totalPaginas = {{ $totalPaginas }};
    let paginaActual = 1;
    let filtroActual = '';

    function renderizarPagina(pagina, filtro = '') {
        const tableBody = document.getElementById('evaluacionesTableBody');
        const numeroPagina = document.getElementById('numeroPagina');
        
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
                    <td>${ev.estudiante?.semestre || 'N/A'}</td>
                    <td>${ev.actividad?.nombre_actividad || 'N/A'}</td>
                    <td>${ev.nivel_desempeno || 'N/A'}</td>
                    <td class="firma-column"><div class="firma-placeholder"></div></td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        numeroPagina.textContent = pagina;
        actualizarBotonesPaginacion(pagina, totalPaginasFiltradas);
        actualizarInfoPaginacion(totalFiltradas, totalPaginasFiltradas);
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

    function actualizarInfoPaginacion(total, totalPaginas) {
        const info = document.querySelector('.pagination-container span:last-child');
        if (info) {
            info.innerHTML = `Página <span id="numeroPagina">${paginaActual}</span> de ${totalPaginas} (${total} evaluaciones)`;
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

    function abrirImpresion(tipo) {
        const semestreId = {{ $semestre->id_semestre ?? 'null' }};
        if (!semestreId) {
            alert('No se encontró el semestre para imprimir.');
            return;
        }
        const baseUrl = `{{ route('coordinador.valle.resultados.print.complementarias', ['id' => '__ID__']) }}`.replace('__ID__', semestreId);
        const url = tipo ? `${baseUrl}?tipo=${encodeURIComponent(tipo)}` : baseUrl;
        const iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.right = '0';
        iframe.style.bottom = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        iframe.src = url;
        document.body.appendChild(iframe);

        iframe.onload = function() {
            try {
                const iframeWindow = iframe.contentWindow || iframe;
                if (iframeWindow && iframeWindow.print) {
                    iframeWindow.focus();
                    iframeWindow.print();
                }
            } catch (e) {
                console.error('Error al imprimir desde iframe:', e);
            } finally {
                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 1000);
            }
        };
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