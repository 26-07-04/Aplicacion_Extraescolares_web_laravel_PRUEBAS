<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($formato ?? 'resultados') === 'registro' ? 'Formato de registro' : 'Formato de resultados' }} — {{ $actividad->nombre_actividad ?? '' }}</title>
    <style>
        @page {
            size: letter;
            margin: 0;
        }
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @media screen {
            body {
                max-width: 8.5in;
                margin: 0 auto;
                padding: 12px;
            }
        }
        .print-page {
            position: relative;
            width: calc(8.5in - 0.84in);
            max-width: calc(8.5in - 0.84in);
            min-height: calc(11in - 0.8in);
            height: calc(11in - 0.8in);
            margin: 0.38in 0.42in 0.42in 0.42in;
            page-break-after: always;
            overflow: visible;
        }
        .print-page:last-child { page-break-after: auto; }
        .print-page-bg {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: top center;
            z-index: 0;
            pointer-events: none;
        }
        .print-page-bg[src=""], .print-page-bg:not([src]) { display: none; }
        .pagina-indicador {
            position: absolute;
            top: 1.18in;
            right: 1.78in;
            left: auto;
            font-size: 9pt;
            font-weight: bold;
            line-height: 1;
            z-index: 3;
            white-space: nowrap;
            text-align: right;
        }
        .foreground {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            width: 100%;
            padding: 4.55cm 0.45in 3.1cm 0.45in;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }
        /* Hojas intermedias: sin cabecera ni firmas → más espacio para hasta 25 filas */
        .print-page-intermedia .foreground {
            padding-top: 4.95cm;
            padding-bottom: 1.4cm;
        }
        .print-page-intermedia .tabla-resultados-wrap {
            margin-top: 0;
        }
        .print-page-intermedia .results-table tbody td {
            padding: 3px 2px;
            min-height: 16px;
        }
        .tabla-resultados-wrap {
            flex: 0 0 auto;
            width: 100%;
            max-width: 100%;
            overflow: visible;
            padding: 0;
            box-sizing: border-box;
            contain: none;
        }
        .footer-resultados {
            margin-top: auto;
            flex-shrink: 0;
            page-break-inside: avoid;
            transform: translateY(-16pt);
        }
        .document-title { text-align: center; margin: -0.1cm 0 8px 0; }
        .document-title h1 { font-size: 11pt; margin: 2px 0; font-weight: 700; }
        .document-title h1:first-child { margin-top: 0; }
        /* Formato de registro: espacio entre instituto, órganos y actividad */
        .document-title-registro .titulo-instituto {
            margin-bottom: 0.5cm;
        }
        .document-title-registro .titulo-organos h1 {
            margin: 1px 0;
            line-height: 1.15;
        }
        .document-title-registro .titulo-actividad {
            margin-top: 0.42cm;
            margin-bottom: 6px;
        }
        .results-table {
            width: 100%;
            max-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 8pt;
            margin-top: 4px;
            table-layout: fixed;
            border: 0;
            background: #fff;
        }
        .results-table colgroup col.numero { width: 5%; }
        .results-table colgroup col.nombre { width: 26%; }
        .results-table colgroup col.control { width: 11%; }
        .results-table colgroup col.carrera { width: 17%; }
        .results-table colgroup col.sem { width: 6%; }
        .results-table colgroup col.resultado { width: 13%; }
        .results-table colgroup col.firma { width: 22%; }
        .results-table.formato-registro colgroup col.numero { width: 6%; }
        .results-table.formato-registro colgroup col.nombre { width: 32%; }
        .results-table.formato-registro colgroup col.control { width: 14%; }
        .results-table.formato-registro colgroup col.carrera { width: 16%; }
        .results-table.formato-registro colgroup col.sem { width: 8%; }
        .results-table.formato-registro colgroup col.obs { width: 24%; }
        /* Rejilla 1px: evita líneas dobles en impresión y mantiene todas las columnas visibles */
        .results-table thead th,
        .results-table tbody td {
            border: 0;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            padding: 3px 2px;
            vertical-align: middle;
            overflow: visible;
            box-sizing: border-box;
            min-width: 0;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .results-table thead th {
            text-align: center;
            font-weight: bold;
            font-size: 7pt;
            line-height: 1.25;
            border-bottom: 1px solid #000;
            padding: 6px 3px;
            min-height: 22px;
        }
        .results-table tbody td {
            border-top: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
            padding: 6px 3px;
            line-height: 1.3;
            min-height: 24px;
        }
        .results-table tbody tr:not(:first-child) td {
            border-top: 1px solid #000;
        }
        .results-table thead th:last-child,
        .results-table tbody td:last-child {
            border-right: 1px solid #000;
        }
        .results-table tbody tr:last-child td {
            border-bottom: 1px solid #000;
        }
        .results-table td.numero,
        .results-table td.sem,
        .results-table td.control,
        .results-table td.carrera { text-align: center; }
        .results-table thead { display: table-header-group; }
        .firma { height: 18px; }
        .firmas-wrap { margin-top: 8px; font-size: 7.5pt; }
        .firmas-wrap table { width: 100%; border-collapse: collapse; }
        .firmas-wrap td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 10px 6px;
            min-height: 52px;
        }
        .firmas-wrap .linea {
            border-top: 1px solid #000;
            width: 88%;
            margin: 28px auto 8px auto;
            min-height: 0;
        }
        .firmas-wrap .nombre { font-weight: 700; margin-bottom: 5px; min-height: 1.2em; line-height: 1.3; }
        .firmas-wrap .cargo { line-height: 1.35; min-height: 2.4em; }
        .fecha-linea { font-size: 9pt; margin-top: 0; }
        @media print {
            body { max-width: none; padding: 0; }
            html, body, .print-page, .foreground, .tabla-resultados-wrap, .results-table,
            .results-table thead th, .results-table tbody td {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-page {
                width: calc(8.5in - 0.84in);
                max-width: calc(8.5in - 0.84in);
                min-height: calc(11in - 0.8in);
                height: calc(11in - 0.8in);
                margin: 0.38in 0.42in 0.42in 0.42in;
                overflow: visible;
            }
            .foreground, .tabla-resultados-wrap {
                overflow: visible !important;
            }
            .print-page-intermedia .foreground {
                padding-top: 4.95cm !important;
                padding-bottom: 1.4cm !important;
            }
            .print-page-intermedia .tabla-resultados-wrap {
                margin-top: 0 !important;
            }
            .print-page-intermedia .results-table tbody td {
                padding: 2px 2px !important;
                min-height: 15px;
            }
            .results-table {
                font-size: 7.5pt;
                border: 0 !important;
                border-collapse: separate !important;
                border-spacing: 0 !important;
            }
            .results-table thead th,
            .results-table tbody td {
                border: 0 !important;
                border-top: 1px solid #000 !important;
                border-left: 1px solid #000 !important;
                padding: 2px 1px;
            }
            .results-table thead th {
                border-bottom: 1px solid #000 !important;
                padding: 5px 2px !important;
                line-height: 1.25;
                min-height: 20px;
            }
            .results-table tbody td {
                border-top: 0 !important;
                padding: 5px 2px !important;
                line-height: 1.3;
                min-height: 22px;
            }
            .results-table tbody tr:not(:first-child) td {
                border-top: 1px solid #000 !important;
            }
            .results-table thead th:last-child,
            .results-table tbody td:last-child {
                border-right: 1px solid #000 !important;
            }
            .results-table tbody tr:last-child td {
                border-bottom: 1px solid #000 !important;
            }
            .firma { height: 16px; }
            .firmas-wrap td {
                padding: 8px 5px !important;
                min-height: 48px;
            }
            .firmas-wrap .linea {
                margin-top: 24px;
                margin-bottom: 7px;
            }
            .firmas-wrap .nombre { min-height: 1.15em; }
            .firmas-wrap .cargo { min-height: 2.2em; }
        }
    </style>
</head>
@php
    $firmasUnidadKeyEarly = $firmasUnidadKey ?? 'valle_etla';
    $formatoNorm = ($formato ?? 'resultados') === 'registro' ? 'registro' : 'resultados';
    $bodyClassFormato = trim(
        ($firmasUnidadKeyEarly === 'valle_etla' ? 'unidad-valle-etla' : '')
        . ' formato-print-' . $formatoNorm
    );
    $paginaTopFormato = $paginaIndicadorFormato['top'] ?? null;
    $paginaRightFormato = $paginaIndicadorFormato['right'] ?? null;
@endphp
<body class="{{ $bodyClassFormato }}">
@php
    $lista = collect($filas ?? [])->values();
    $pages = \App\Support\FormatoActividadPaginacion::paginar($lista);
    $totalPages = max(1, $pages->count());
    $globalCounter = 0;
    $tituloActividad = $tituloActividad ?? \App\Support\FormatoActividadTitulo::linea($actividad);
    \Carbon\Carbon::setLocale('es');
    $fechaMx = \Carbon\Carbon::now('America/Mexico_City');
    $lugarTexto = $lugar ?? 'Santiago Suchilquitongo';
    if ($formatoNorm === 'registro') {
        $fechaLarga = $lugarTexto . ' a los ' . $fechaMx->day . ' días del mes de ' . $fechaMx->translatedFormat('F') . ' de ' . $fechaMx->year . '.';
    } else {
        $fechaLarga = $lugarTexto . ', a los ' . $fechaMx->day . ' días del mes de ' . $fechaMx->translatedFormat('F') . ' de ' . $fechaMx->year . '.';
    }
    $firmasUnidadKey = $firmasUnidadKey ?? 'valle_etla';
    $firmas = $firmas ?? \App\Support\ResultadosExtraescolaresFirmas::forUnidad($firmasUnidadKey);
@endphp

@foreach($pages as $pIndex => $pageItems)
    @php
        $esPrimeraPagina = $pIndex === 0;
        $esUltimaPagina = ($pIndex + 1) === $totalPages;
        $clasePagina = $esPrimeraPagina
            ? 'print-page-primera'
            : ($esUltimaPagina ? 'print-page-ultima' : 'print-page-intermedia');
    @endphp
    <div class="print-page {{ $clasePagina }}" data-page="{{ $pIndex + 1 }}" data-filas="{{ $pageItems->count() }}">
        <img class="print-page-bg" src="" alt="" />
        <div class="pagina-indicador" aria-hidden="true"@if($paginaTopFormato) style="position: absolute !important; top: {{ $paginaTopFormato }} !important; right: {{ $paginaRightFormato ?? '1.78in' }} !important; left: auto !important; z-index: 3 !important;" @endif>Página {{ $pIndex + 1 }} de {{ $totalPages }}</div>
        <div class="foreground">
            @if($pIndex === 0)
                <div class="document-title {{ $formatoNorm === 'registro' ? 'document-title-registro' : 'document-title-resultados' }}">
                    @if($formatoNorm === 'registro')
                        <h1 class="titulo-instituto">INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA</h1>
                        <div class="titulo-organos">
                            <h1>SUBDIRECCIÓN DE PLANEACIÓN Y VINCULACIÓN</h1>
                            <h1>DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES</h1>
                        </div>
                    @else
                        <h1>DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES</h1>
                        <h1>OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA</h1>
                    @endif
                    <h1 class="titulo-actividad">{{ $tituloActividad }}</h1>
                </div>
            @endif

            @if($lista->count() > 0)
                <div class="tabla-resultados-wrap">
                    <table class="results-table {{ $formatoNorm === 'registro' ? 'formato-registro' : 'formato-resultados' }}">
                        @if($formatoNorm === 'registro')
                        <colgroup>
                            <col class="numero" />
                            <col class="nombre" />
                            <col class="control" />
                            <col class="carrera" />
                            <col class="sem" />
                            <col class="obs" />
                        </colgroup>
                        @else
                        <colgroup>
                            <col class="numero" />
                            <col class="nombre" />
                            <col class="control" />
                            <col class="carrera" />
                            <col class="sem" />
                            <col class="resultado" />
                            <col class="firma" />
                        </colgroup>
                        @endif
                        <thead>
                            <tr>
                                @if($formatoNorm === 'registro')
                                    <th>NO.</th>
                                    <th>NOMBRE</th>
                                    <th>CONTROL</th>
                                    <th>ESP.</th>
                                    <th>SEM</th>
                                    <th>OBSERVACIONES</th>
                                @else
                                    <th>NO.</th>
                                    <th>NOMBRE DEL ESTUDIANTE</th>
                                    <th>NO. CONTROL</th>
                                    <th>CARRERA</th>
                                    <th>SEM</th>
                                    <th>RESULTADO</th>
                                    <th>FIRMA DE ENTERADO</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pageItems as $fila)
                                @php $globalCounter++; @endphp
                                <tr>
                                    <td class="numero">{{ $globalCounter }}</td>
                                    <td>{{ $fila['nombre'] ?? 'N/A' }}</td>
                                    <td class="control">{{ $fila['control'] ?? 'N/A' }}</td>
                                    <td class="carrera">{{ $fila['carrera'] ?? 'N/A' }}</td>
                                    <td class="sem">{{ $fila['sem'] ?? 'N/A' }}</td>
                                    @if($formatoNorm === 'registro')
                                        <td>{{ $fila['observaciones'] ?? '' }}</td>
                                    @else
                                        <td style="text-align:center;">{{ $fila['resultado'] ?? '' }}</td>
                                        <td><div class="firma"></div></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($pIndex === 0)
                <p style="text-align:center; margin-top:24px; font-size:10pt;">No hay registros para esta actividad.</p>
            @endif

            @if($pIndex + 1 === $totalPages)
                <div class="footer-resultados">
                    <p class="fecha-linea"><strong id="formatoFechaImpresion">{{ $fechaLarga }}</strong></p>
                    <div class="firmas-wrap">
                        <table>
                            <tr>
                                @foreach(['izquierda', 'centro', 'derecha'] as $slotFirma)
                                <td class="firma-col" data-slot="{{ $slotFirma }}">
                                    <div class="linea"></div>
                                    <div class="nombre" data-slot="{{ $slotFirma }}">{{ $firmas[$slotFirma]['nombre'] ?? '' }}</div>
                                    <div class="cargo" data-slot="{{ $slotFirma }}">{{ $firmas[$slotFirma]['cargo'] ?? '' }}</div>
                                </td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endforeach

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
(function () {
    var membreteUrl = @json($membreteArchivoUrl ?? null);
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
    }
    function convertirPDFaDataUrl(blob) {
        return new Promise(function (resolve, reject) {
            if (typeof pdfjsLib === 'undefined') { reject(new Error('pdf.js no disponible')); return; }
            var reader = new FileReader();
            reader.onload = function () {
                var data = new Uint8Array(reader.result);
                pdfjsLib.getDocument({ data: data }).promise.then(function (pdf) {
                    return pdf.getPage(1);
                }).then(function (page) {
                    var baseVp = page.getViewport({ scale: 1 });
                    var scale = (8.5 * 96) / baseVp.width;
                    var viewport = page.getViewport({ scale: scale });
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');
                    canvas.width = Math.floor(viewport.width);
                    canvas.height = Math.floor(viewport.height);
                    return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
                        resolve(canvas.toDataURL('image/png'));
                    });
                }).catch(reject);
            };
            reader.onerror = reject;
            reader.readAsArrayBuffer(blob);
        });
    }
    function aplicarMembreteYEsperar(dataUrl) {
        return new Promise(function (resolve) {
            var imgs = document.querySelectorAll('.print-page-bg');
            if (!dataUrl || imgs.length === 0) { resolve(); return; }
            var restantes = imgs.length;
            function unoListo() { if (--restantes <= 0) resolve(); }
            imgs.forEach(function (img) {
                var listo = false;
                function marcarListo() { if (!listo) { listo = true; unoListo(); } }
                img.onload = marcarListo;
                img.onerror = marcarListo;
                img.src = dataUrl;
                if (img.complete && img.naturalWidth > 0) marcarListo();
            });
        });
    }
    function cargarDatosDesdePanel() {
        try {
            var raw = sessionStorage.getItem('formatosPrintPayload');
            if (!raw) return;
            var data = JSON.parse(raw);
            if (data.fecha) {
                var fechaOut = document.getElementById('formatoFechaImpresion');
                if (fechaOut) fechaOut.textContent = data.fecha;
            }
            if (data.firmas) {
                ['izquierda', 'centro', 'derecha'].forEach(function (slot) {
                    var f = data.firmas[slot];
                    if (!f) return;
                    document.querySelectorAll('.firmas-wrap .nombre[data-slot="' + slot + '"]').forEach(function (el) {
                        if (f.nombre) el.textContent = f.nombre;
                    });
                    document.querySelectorAll('.firmas-wrap .cargo[data-slot="' + slot + '"]').forEach(function (el) {
                        if (f.cargo) el.textContent = f.cargo;
                    });
                });
            }
        } catch (e) {}
    }

    function aplicarAjustePaginaIndicadorFormato() {
        var pagina = @json($paginaIndicadorFormato ?? null);
        if (!pagina || !pagina.top) {
            return;
        }
        document.querySelectorAll('.pagina-indicador').forEach(function (el) {
            el.style.setProperty('position', 'absolute', 'important');
            el.style.setProperty('top', pagina.top, 'important');
            el.style.setProperty('right', pagina.right || '1.78in', 'important');
            el.style.setProperty('left', 'auto', 'important');
            el.style.setProperty('z-index', '3', 'important');
        });
    }

    function finalizarImpresion() {
        aplicarAjustePaginaIndicadorFormato();
        requestAnimationFrame(function () {
            setTimeout(function () {
                aplicarAjustePaginaIndicadorFormato();
                try {
                    if (window.frameElement) {
                        window.focus();
                    }
                    window.print();
                } catch (e) {}
            }, 400);
        });
    }

    function cargarMembreteEImprimir() {
        if (!membreteUrl) { finalizarImpresion(); return; }
        fetch(membreteUrl, { credentials: 'same-origin' })
            .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.blob(); })
            .then(function (blob) {
                var t = (blob.type || '').toLowerCase();
                var urlLower = (membreteUrl || '').toLowerCase();
                if (t.indexOf('pdf') !== -1 || urlLower.indexOf('.pdf') !== -1) {
                    return convertirPDFaDataUrl(blob);
                }
                return new Promise(function (resolve, reject) {
                    var fr = new FileReader();
                    fr.onload = function () { resolve(fr.result); };
                    fr.onerror = reject;
                    fr.readAsDataURL(blob);
                });
            })
            .then(function (dataUrl) { if (dataUrl) return aplicarMembreteYEsperar(dataUrl); })
            .catch(function () { console.warn('No se pudo cargar el membretado.'); })
            .finally(finalizarImpresion);
    }

    function iniciar() {
        aplicarAjustePaginaIndicadorFormato();
        cargarDatosDesdePanel();
        cargarMembreteEImprimir();
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(iniciar, 0);
    } else {
        window.addEventListener('DOMContentLoaded', function () { setTimeout(iniciar, 0); });
    }
    window.addEventListener('beforeprint', aplicarAjustePaginaIndicadorFormato);
})();
</script>
</body>
</html>
