<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados — {{ $semestre->nombre ?? '' }}</title>
    <style>
        @page {
            size: letter;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

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
                box-sizing: border-box;
            }
        }

        /*
         * Una hoja por bloque: ocupa el área útil tras @page (no pegado al borde físico).
         */
        .print-page {
            position: relative;
            width: 8.5in;
            max-width: 100%;
            min-height: 11in;
            height: 11in;
            page-break-after: always;
            overflow: visible;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            box-sizing: border-box;
        }

        .print-page:last-child {
            page-break-after: auto;
        }

        /* Fondo del PDF como imagen (se imprime mejor que background-image en muchos navegadores) */
        .print-page-bg {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
            object-position: top center;
            z-index: 0;
            pointer-events: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-page-bg[src=""],
        .print-page-bg:not([src]) {
            display: none;
        }

        /* Informe: ~102 pt desde arriba, alineado a la derecha; aquí respecto al área ya con margen @page */
        .pagina-indicador {
            position: absolute;
            top: 0.72in;
            right: 0.28in;
            left: auto;
            font-size: 9pt;
            font-weight: bold;
            line-height: 1;
            color: #000;
            z-index: 3;
            white-space: nowrap;
            text-align: right;
            pointer-events: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .foreground {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            width: 100%;
            /* Margen interno extra para que título/tabla no queden pegados al borde útil */
            padding: 5.0cm 0.45in 3.1cm 0.45in;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        .tabla-resultados-wrap {
            flex: 1 1 auto;
            min-height: 0;
            overflow: visible;
            width: 100%;
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        .footer-resultados {
            margin-top: auto;
            flex-shrink: 0;
            page-break-inside: avoid;
            padding-bottom: 0.1cm;
            /* Sube un poco el bloque fecha + firmas (solo última hoja) */
            transform: translateY(-16pt);
        }

        .document-title { text-align: center; margin: 0 0 10px 0; }
        .document-title h1 { font-size: 11pt; margin: 3px 0; font-weight: 700; }

        .results-table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 4px;
            table-layout: fixed;
            border: 1px solid #000;
        }
        .results-table colgroup col.numero { width: 5%; }
        .results-table colgroup col.nombre { width: 26%; }
        .results-table colgroup col.control { width: 11%; }
        .results-table colgroup col.carrera { width: 17%; }
        .results-table colgroup col.sem { width: 6%; }
        .results-table colgroup col.resultado { width: 13%; }
        .results-table colgroup col.firma { width: 22%; }
        .results-table thead th {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            font-weight: bold;
            font-size: 7pt;
            line-height: 1.1;
            overflow: visible;
        }
        .results-table tbody td {
            border: 1px solid #000;
            padding: 3px 2px;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            overflow: visible;
        }
        .results-table td.numero, .results-table td.sem { text-align: center; }
        .results-table td.control { text-align: center; }
        .results-table thead { display: table-header-group; }

        .firma { height: 14px; }

        .firmas-wrap { margin-top: 4px; font-size: 7.5pt; }
        .firmas-wrap table { width: 100%; border-collapse: collapse; }
        .firmas-wrap td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 4px 3px;
        }
        .firmas-wrap .linea {
            border-top: 1px solid #000;
            width: 88%;
            margin: 0 auto 5px auto;
        }
        .firmas-wrap .nombre { font-weight: 700; margin-bottom: 3px; min-height: 1.05em; }
        .firmas-wrap .cargo { line-height: 1.2; }

        .fecha-linea { font-size: 9pt; margin-top: 0; }

        @media print {
            body {
                max-width: none;
                padding: 0;
            }
            html, body, .print-page, .foreground, .print-page-bg, .pagina-indicador {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-page {
                width: 8.5in;
                min-height: 11in;
                height: 11in;
            }
            .results-table {
                font-size: 7.5pt;
            }
            .results-table thead th,
            .results-table tbody td {
                padding: 2px 1px;
            }
            .firma { height: 12px; }
        }
    </style>
</head>
<body>
@php
    $lista = ($evaluaciones ?? collect())->values();
    /* Filas por hoja (altura de fila puede crecer con nombres largos) */
    $rowsPerPage = 12;
    if ($lista->count() > 0) {
        $pages = $lista->chunk($rowsPerPage);
    } else {
        $pages = collect([collect()]);
    }
    $totalPages = max(1, $pages->count());
    $globalCounter = 0;

    $tipoNorm = strtolower($tipo ?? 'cultural');
    $tituloActividad = match ($tipoNorm) {
        'deportiva' => 'ACTIVIDAD DEPORTIVA',
        'academica' => 'ACTIVIDAD ACADÉMICA',
        default => 'ACTIVIDAD CULTURAL',
    };

    \Carbon\Carbon::setLocale('es');
    $fechaMx = \Carbon\Carbon::now('America/Mexico_City');
    $lugarTexto = $lugar ?? 'Santiago Suchilquitongo, Oax';
    $fechaLarga = $lugarTexto . ', a los ' . $fechaMx->day . ' días del mes de ' . $fechaMx->translatedFormat('F') . ' de ' . $fechaMx->year;

    $firmas = $firmas ?? \App\Support\ResultadosExtraescolaresFirmas::forUnidad('valle_etla');
@endphp

@foreach($pages as $pIndex => $pageItems)
    <div class="print-page" data-page="{{ $pIndex + 1 }}">
        <img class="print-page-bg" src="" alt="" />
        <div class="pagina-indicador" aria-hidden="true">Página {{ $pIndex + 1 }} de {{ $totalPages }}</div>
        <div class="foreground">
            @if($pIndex === 0)
                <div class="document-title">
                    <h1>DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES</h1>
                    <h1>OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA</h1>
                    <h1>{{ $tituloActividad }}</h1>
                </div>
            @endif

            @if($lista->count() > 0)
                <div class="tabla-resultados-wrap">
                <table class="results-table">
                    <colgroup>
                        <col class="numero" />
                        <col class="nombre" />
                        <col class="control" />
                        <col class="carrera" />
                        <col class="sem" />
                        <col class="resultado" />
                        <col class="firma" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>NOMBRE DEL ESTUDIANTE</th>
                            <th>NO. CONTROL</th>
                            <th>CARRERA</th>
                            <th>SEM</th>
                            <th>RESULTADO</th>
                            <th>FIRMA DE ENTERADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pageItems as $ev)
                            @php $globalCounter++; @endphp
                            <tr>
                                <td class="numero">{{ $globalCounter }}</td>
                                <td>{{ $ev->estudiante->nombre ?? 'N/A' }}</td>
                                <td class="control">{{ $ev->estudiante->numero_control ?? 'N/A' }}</td>
                                <td>{{ $ev->estudiante->carrera ?? 'N/A' }}</td>
                                <td class="sem">{{ $ev->estudiante->semestre ?? 'N/A' }}</td>
                                <td style="text-align:center;">{{ $ev->nivel_desempeno ?? 'N/A' }}</td>
                                <td><div class="firma"></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @else
                @if($pIndex === 0)
                    <p style="text-align:center; margin-top:24px; font-size:10pt;">No hay evaluaciones registradas para este tipo y semestre.</p>
                @endif
            @endif

            @if($pIndex + 1 === $totalPages)
                <div class="footer-resultados">
                    <p class="fecha-linea"><strong>{{ $fechaLarga }}</strong></p>

                    <div class="firmas-wrap">
                        <table>
                            <tr>
                                <td>
                                    <div class="linea"></div>
                                    <div class="nombre">{{ $firmas['izquierda']['nombre'] ?? '' }}</div>
                                    <div class="cargo">{{ $firmas['izquierda']['cargo'] ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="linea"></div>
                                    <div class="nombre">{{ $firmas['centro']['nombre'] ?? '' }}</div>
                                    <div class="cargo">{{ $firmas['centro']['cargo'] ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="linea"></div>
                                    <div class="nombre">{{ $firmas['derecha']['nombre'] ?? '' }}</div>
                                    <div class="cargo">{{ $firmas['derecha']['cargo'] ?? '' }}</div>
                                </td>
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
                    /* ~96 dpi en ancho carta (8.5 in) para buena nitidez al imprimir */
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

    /**
     * Asigna el membretado a todas las hojas y espera a que las imágenes estén listas (necesario para impresión).
     */
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
                    if (listo) {
                        return;
                    }
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

    function finalizarImpresion() {
        requestAnimationFrame(function () {
            setTimeout(function () {
                try {
                    window.focus();
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
                if (!r.ok) {
                    throw new Error('HTTP ' + r.status);
                }
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
