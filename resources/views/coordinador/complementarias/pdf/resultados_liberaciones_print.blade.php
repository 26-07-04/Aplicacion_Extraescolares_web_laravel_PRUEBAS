<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberaciones — {{ $semestre->nombre ?? '' }}</title>
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

        .foreground {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            width: 100%;
            padding: 4.85cm 0.45in 1.2cm 0.45in;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        .print-page-intermedia .foreground {
            padding-top: 4.95cm;
        }

        .print-page-ultima .foreground {
            display: flex;
            flex-direction: column;
            padding-bottom: 0.2cm;
        }

        .print-page-ultima .tabla-resultados-wrap {
            flex: 0 0 auto;
            width: 100%;
            min-height: 0;
        }

        .print-page-primera .tabla-resultados-wrap {
            flex: 0 0 auto;
        }

        .footer-liberaciones {
            flex-shrink: 0;
            page-break-inside: avoid;
            max-width: 58%;
            margin-top: 0.35cm;
            margin-bottom: 0;
        }

        /* Justo debajo de la tabla (no al pie de la hoja) */
        .print-page-ultima .footer-liberaciones {
            margin-top: 0.1cm;
            transform: none;
            -webkit-transform: none;
        }

        .atentamente {
            font-weight: bold;
            font-size: 9pt;
            letter-spacing: 0.08em;
            margin: 0 0 0.28cm 0;
        }

        .lemas-block {
            font-size: 8pt;
            line-height: 1.35;
            margin: 0 0 0.22cm 0;
        }

        .lemas-block .lema-principal { font-weight: bold; }
        .lemas-block .lema-secundario {
            font-style: italic;
            margin-top: 0.12cm;
        }

        /* Espacio para firma autógrafa entre lemas y la línea */
        .firma-espacio-pie {
            min-height: 0;
            margin: 0;
        }

        .firma-linea-pie {
            border-top: 1px solid #000;
            width: 82%;
            margin: 0 0 0.22cm 0;
        }

        .nombre-firmante {
            font-weight: bold;
            font-size: 8pt;
            line-height: 1.35;
            margin: 0 0 0.14cm 0;
            text-transform: uppercase;
        }

        .cargo-firmante {
            font-weight: bold;
            font-size: 7.5pt;
            line-height: 1.3;
            margin: 0;
            text-transform: uppercase;
        }

        .ccp-archivo {
            font-size: 7pt;
            margin-top: 0.28cm;
        }

        .oficio-meta-derecha {
            text-align: right;
            font-size: 9.5pt;
            line-height: 1.35;
            margin-bottom: 0.45cm;
        }

        .oficio-meta-derecha p { margin: 0 0 2px 0; }

        .oficio-destinatario {
            font-size: 9.5pt;
            line-height: 1.35;
            margin-bottom: 0.4cm;
        }

        .oficio-destinatario p { margin: 0; }

        .oficio-presente {
            letter-spacing: 0.32em;
            margin-top: 2px;
        }

        .oficio-intro {
            font-size: 9.5pt;
            line-height: 1.45;
            text-align: justify;
            margin: 0 0 0.35cm 0;
        }

        .oficio-cierre-tabla {
            font-size: 9.5pt;
            line-height: 1.45;
            text-align: justify;
            margin: 0.28cm 0 0.32cm 0;
            width: 100%;
        }

        .tabla-resultados-wrap {
            flex: 1 1 auto;
            min-height: 0;
            width: 100%;
            overflow: visible;
        }

        .results-table {
            width: 100%;
            max-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 7.5pt;
            table-layout: fixed;
            border: 0;
            background: #fff;
            margin-top: 2px;
        }

        .results-table colgroup col.num { width: 6%; }
        .results-table colgroup col.nombre { width: 38%; }
        .results-table colgroup col.control { width: 14%; }
        .results-table colgroup col.carrera { width: 42%; }

        /* Rejilla 1px: evita líneas dobles y cortes al imprimir */
        .results-table thead th,
        .results-table tbody td {
            border: 0;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            padding: 3px 2px;
            vertical-align: top;
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
            font-size: 6.5pt;
            line-height: 1.15;
            border-bottom: 1px solid #000;
            padding: 5px 2px;
            min-height: 20px;
        }

        .results-table tbody td {
            border-top: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
            padding: 4px 2px;
            line-height: 1.25;
            min-height: 18px;
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

        .results-table td.num,
        .results-table td.control { text-align: center; }

        .results-table thead { display: table-header-group; }

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
            .results-table {
                border: 0 !important;
                border-collapse: separate !important;
                border-spacing: 0 !important;
            }
            .results-table thead th,
            .results-table tbody td {
                border: 0 !important;
                border-top: 1px solid #000 !important;
                border-left: 1px solid #000 !important;
                background: #fff !important;
            }
            .results-table thead th {
                border-bottom: 1px solid #000 !important;
            }
            .results-table tbody td {
                border-top: 0 !important;
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
            .print-page-ultima .foreground {
                display: flex !important;
                flex-direction: column !important;
            }
            .print-page-ultima .tabla-resultados-wrap {
                flex: 0 0 auto !important;
            }
            .print-page-ultima .footer-liberaciones {
                margin-top: 0.1cm !important;
                transform: none !important;
                -webkit-transform: none !important;
            }
            .firma-espacio-pie {
                min-height: 0 !important;
            }
        }
    </style>
</head>
<body>
@php
    $lista = ($evaluaciones ?? collect())->values();
    $pages = \App\Support\FormatoActividadPaginacion::paginar($lista, 12, 12);
    $totalPages = max(1, $pages->count());
    $globalCounter = 0;

    $lugarOficio = $lugarOficio ?? 'Santiago Suchilquitongo, Oax.';
    $fechaOficio = $fechaOficio ?? '';
    $oficioNumero = $oficioNumero ?? '';
    $asuntoOficio = $asuntoOficio ?? 'Entrega de Constancias';
    $totalLiberaciones = $totalLiberaciones ?? $lista->count();
    $periodoSemestre = $periodoSemestre ?? ($semestre->nombre ?? '');
    $firmasLib = $firmasLiberaciones ?? \App\Support\ComplementariasLiberacionesFirmas::defaults();
@endphp

@foreach($pages as $pIndex => $pageItems)
    @php
        $esIntermedia = $pIndex > 0 && ($pIndex + 1) < $totalPages;
        $esUltima = ($pIndex + 1) === $totalPages;
        $clasePagina = '';
        if ($pIndex === 0) {
            $clasePagina .= ' print-page-primera';
        }
        if ($esUltima) {
            $clasePagina .= ' print-page-ultima';
        }
        if ($esIntermedia) {
            $clasePagina .= ' print-page-intermedia';
        }
    @endphp
    <div class="print-page{{ $clasePagina }}" data-page="{{ $pIndex + 1 }}">
        <img class="print-page-bg" src="" alt="" />
        <div class="foreground">
            @if($pIndex === 0)
                <div class="oficio-meta-derecha">
                    <p data-field="oficio-lugar-fecha">{{ $lugarOficio }} {{ $fechaOficio }}</p>
                    <p data-field="oficio-numero"><strong>OFICIO: {{ $oficioNumero }}</strong></p>
                    <p data-field="oficio-asunto"><strong>ASUNTO: {{ $asuntoOficio }}</strong></p>
                </div>
                <div class="oficio-destinatario">
                    <p class="destinatario-nombre" data-field="destinatario-nombre"><strong>{{ $firmasLib['destinatario']['nombre'] ?? '' }}</strong></p>
                    <p class="destinatario-cargo" data-field="destinatario-cargo"><strong>{{ $firmasLib['destinatario']['cargo'] ?? '' }}</strong></p>
                    <p class="oficio-presente"><strong>P R E S E N T E</strong></p>
                </div>
                <p class="oficio-intro">
                    Por medio del presente, adjunto y le hago entrega de {{ $totalLiberaciones }} Liberaciones de Actividades Complementarias de los estudiantes que se enlistan a continuación, correspondientes al periodo {{ $periodoSemestre }}:
                </p>
            @endif

            @if($lista->count() > 0)
                <div class="tabla-resultados-wrap">
                    <table class="results-table">
                        <colgroup>
                            <col class="num" />
                            <col class="nombre" />
                            <col class="control" />
                            <col class="carrera" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>NOMBRE</th>
                                <th>NO. CONTROL</th>
                                <th>CARRERA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pageItems as $ev)
                                @php $globalCounter++; @endphp
                                <tr>
                                    <td class="num">{{ $globalCounter }}</td>
                                    <td>{{ $ev->estudiante->nombre ?? 'N/A' }}</td>
                                    <td class="control">{{ $ev->estudiante->numero_control ?? 'N/A' }}</td>
                                    <td>{{ $ev->estudiante->carrera ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($pIndex === 0)
                <p style="text-align:center; margin-top:24px; font-size:10pt;">No hay evaluaciones registradas para este semestre.</p>
            @endif

            @if($esUltima && $lista->count() > 0)
                <p class="oficio-cierre-tabla">
                    Sin más por el momento, y sabiendo de su responsabilidad hacia los proyectos de la Institución, le envío un cordial saludo.
                </p>
            @endif

            @if($esUltima && $lista->count() > 0)
                <div class="footer-liberaciones">
                    <p class="atentamente">ATENTAMENTE</p>
                    <div class="lemas-block">
                        <div class="lema-principal">Excelencia en Educación Tecnológica®</div>
                        <div class="lema-secundario">&ldquo;Ciencia y Sustentabilidad al Servicio de la Humanidad&rdquo;</div>
                    </div>
                    <div class="firma-espacio-pie"></div>
                    <div class="firma-linea-pie"></div>
                    <p class="nombre-firmante" data-field="firmante-nombre">{{ $firmasLib['firmante']['nombre'] ?? '' }}</p>
                    <p class="cargo-firmante" data-field="firmante-cargo">{{ $firmasLib['firmante']['cargo'] ?? '' }}</p>
                    <p class="ccp-archivo">ccp. Archivo</p>
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
            if (typeof pdfjsLib === 'undefined') {
                reject(new Error('pdf.js no disponible'));
                return;
            }
            var reader = new FileReader();
            reader.onload = function () {
                var data = new Uint8Array(reader.result);
                pdfjsLib.getDocument({ data: data }).promise.then(function (pdf) {
                    return pdf.getPage(1);
                }).then(function (page) {
                    var baseVp = page.getViewport({ scale: 1 });
                    var targetPx = 8.5 * 96;
                    var scale = targetPx / baseVp.width;
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
            if (!dataUrl || imgs.length === 0) {
                resolve();
                return;
            }
            var restantes = imgs.length;
            function unoListo() {
                restantes--;
                if (restantes <= 0) {
                    resolve();
                }
            }
            imgs.forEach(function (img) {
                var listo = false;
                function marcarListo() {
                    if (listo) return;
                    listo = true;
                    unoListo();
                }
                img.onload = marcarListo;
                img.onerror = marcarListo;
                img.src = dataUrl;
                if (img.complete && img.naturalWidth > 0) {
                    marcarListo();
                }
            });
        });
    }

    function cargarDatosDesdePanel() {
        try {
            var raw = sessionStorage.getItem('complementariasLiberacionesPrintPayload');
            if (!raw) return;
            var data = JSON.parse(raw);
            function aplicarCampoTexto(selector, valor) {
                var t = (valor == null ? '' : String(valor)).trim();
                document.querySelectorAll(selector).forEach(function (el) {
                    if (!t) {
                        el.textContent = '';
                        el.innerHTML = '';
                        el.style.display = 'none';
                        return;
                    }
                    el.style.display = '';
                    var strong = el.querySelector('strong');
                    if (strong) {
                        strong.textContent = t;
                    } else {
                        el.textContent = t;
                    }
                });
            }
            if (data.oficio) {
                document.querySelectorAll('[data-field="oficio-lugar-fecha"]').forEach(function (el) {
                    var t = String(data.oficio.lugarFecha == null ? '' : data.oficio.lugarFecha).trim();
                    el.textContent = t;
                    el.style.display = t ? '' : 'none';
                });
                document.querySelectorAll('[data-field="oficio-numero"]').forEach(function (el) {
                    var t = String(data.oficio.numero == null ? '' : data.oficio.numero).trim();
                    if (!t) {
                        el.textContent = '';
                        el.innerHTML = '';
                        el.style.display = 'none';
                        return;
                    }
                    el.style.display = '';
                    var strong = el.querySelector('strong');
                    if (strong) {
                        strong.textContent = 'OFICIO: ' + t;
                    } else {
                        el.innerHTML = '<strong>OFICIO: ' + t + '</strong>';
                    }
                });
                document.querySelectorAll('[data-field="oficio-asunto"]').forEach(function (el) {
                    var t = String(data.oficio.asunto == null ? '' : data.oficio.asunto).trim();
                    if (!t) {
                        el.textContent = '';
                        el.innerHTML = '';
                        el.style.display = 'none';
                        return;
                    }
                    el.style.display = '';
                    var strong = el.querySelector('strong');
                    if (strong) {
                        strong.textContent = 'ASUNTO: ' + t;
                    } else {
                        el.innerHTML = '<strong>ASUNTO: ' + t + '</strong>';
                    }
                });
                document.querySelectorAll('.oficio-meta-derecha').forEach(function (bloque) {
                    var visible = ['lugarFecha', 'numero', 'asunto'].some(function (k) {
                        return String(data.oficio[k] == null ? '' : data.oficio[k]).trim();
                    });
                    bloque.style.display = visible ? '' : 'none';
                });
            }
            if (data.destinatario) {
                aplicarCampoTexto('[data-field="destinatario-nombre"]', data.destinatario.nombre);
                aplicarCampoTexto('[data-field="destinatario-cargo"]', data.destinatario.cargo);
            }
            if (data.firmante) {
                document.querySelectorAll('[data-field="firmante-nombre"]').forEach(function (el) {
                    var t = String(data.firmante.nombre == null ? '' : data.firmante.nombre).trim();
                    el.textContent = t;
                    el.style.display = t ? '' : 'none';
                });
                document.querySelectorAll('[data-field="firmante-cargo"]').forEach(function (el) {
                    var t = String(data.firmante.cargo == null ? '' : data.firmante.cargo).trim();
                    el.textContent = t;
                    el.style.display = t ? '' : 'none';
                });
            }
        } catch (e) {}
    }

    function finalizarImpresion() {
        cargarDatosDesdePanel();
        requestAnimationFrame(function () {
            setTimeout(function () {
                try {
                    if (window.frameElement) {
                        window.focus();
                    }
                    window.print();
                } catch (e) {}
            }, 400);
        });
    }

    function iniciar() {
        if (!membreteUrl) {
            finalizarImpresion();
            return;
        }
        fetch(membreteUrl, { credentials: 'same-origin' })
            .then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.blob();
            })
            .then(function (blob) {
                var t = (blob.type || '').toLowerCase();
                var urlLower = (membreteUrl || '').toLowerCase();
                var parecePdf = t.indexOf('pdf') !== -1 || urlLower.indexOf('.pdf') !== -1;
                if (parecePdf) {
                    return convertirPDFaDataUrl(blob);
                }
                return new Promise(function (resolve, reject) {
                    var fr = new FileReader();
                    fr.onload = function () { resolve(fr.result); };
                    fr.onerror = reject;
                    fr.readAsDataURL(blob);
                });
            })
            .then(function (dataUrl) {
                if (dataUrl) {
                    return aplicarMembreteYEsperar(dataUrl);
                }
            })
            .catch(function () {
                console.warn('No se pudo cargar el membretado; se imprime sin fondo.');
            })
            .finally(finalizarImpresion);
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(iniciar, 0);
    } else {
        window.addEventListener('DOMContentLoaded', function () { setTimeout(iniciar, 0); });
    }
})();
</script>
</body>
</html>
