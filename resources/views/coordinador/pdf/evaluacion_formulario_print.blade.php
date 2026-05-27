<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación de desempeño — {{ $semestre->nombre ?? '' }}</title>
    <style>
        @page { size: letter; margin: 0; }
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
            body { max-width: 8.5in; margin: 0 auto; padding: 12px; }
        }
        .print-page {
            position: relative;
            width: calc(8.5in - 0.84in);
            max-width: calc(8.5in - 0.84in);
            min-height: calc(11in - 0.8in);
            margin: 0.38in 0.42in 0.42in 0.42in;
            page-break-after: always;
            overflow: visible !important;
        }
        .print-page:last-child { page-break-after: auto; }
        .print-page-bg {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            min-height: calc(11in - 0.8in);
            object-fit: contain;
            object-position: top center;
            z-index: 0;
            pointer-events: none;
        }
        .print-page-bg[src=""], .print-page-bg:not([src]) { display: none; }
        /* Celda inferior derecha del membretado (código / revisión / página) */
        .pagina-indicador {
            position: absolute;
            top: 1.22in;
            right: 1.72in;
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
            position: relative;
            z-index: 1;
            padding: 4.25cm 0.38in 1.15cm 0.38in;
            overflow: visible !important;
        }
        .encabezado-institucional {
            text-align: center;
            margin-bottom: 0.22cm;
            line-height: 1.2;
        }
        .encabezado-institucional p {
            margin: 0;
            font-size: 10.5pt;
            font-weight: bold;
        }
        .encabezado-institucional .titulo-principal {
            font-size: 11.5pt;
            margin-top: 0.1cm;
        }
        .datos-estudiante {
            font-size: 9pt;
            line-height: 1.3;
            margin-bottom: 0.18cm;
        }
        .datos-estudiante p { margin: 0 0 0.1cm 0; }
        :root {
            /* Usar pt para impresión (más consistente que px) */
            --grid-line: 1pt;
        }
        .tabla-criterios-wrap {
            overflow: visible !important;
            margin-bottom: 0.2cm;
            margin-top: 0.12cm;
        }
        /* Caja con un solo borde exterior (evita líneas dobles/gruesas) */
        .formulario-caja {
            width: 100%;
            border: var(--grid-line) solid #000;
            background: #fff;
            position: relative;
            z-index: 2;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .formulario-caja-pie {
            margin-top: 0.2cm;
            padding-bottom: 0.85cm;
        }
        .tabla-criterios {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin: 0;
            font-size: 7.5pt;
            table-layout: fixed;
            page-break-inside: avoid;
            background: #fff;
        }
        .tabla-criterios th,
        .tabla-criterios td {
            padding: 5pt 4pt;
            vertical-align: middle;
            overflow: visible;
            border: none;
            border-right: var(--grid-line) solid #000;
            border-bottom: var(--grid-line) solid #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .tabla-criterios th:last-child,
        .tabla-criterios td:last-child {
            border-right: none;
        }
        .tabla-criterios tbody tr:last-child td {
            border-bottom: none !important;
        }
        .formulario-pie {
            font-size: 8.5pt;
            line-height: 1.35;
            background: #fff;
        }
        .observaciones-block {
            padding: 5pt 8pt 4pt;
        }
        .observaciones-titulo-row {
            display: flex;
            align-items: flex-end;
            gap: 5pt;
            margin-bottom: 0;
        }
        .obs-etiqueta {
            flex: 0 0 auto;
            font-weight: bold;
            white-space: nowrap;
        }
        .obs-linea-primera {
            flex: 1 1 auto;
            border-bottom: var(--grid-line) solid #000;
            min-height: 1.05em;
            margin-bottom: 2pt;
        }
        .obs-linea-primera.obs-con-texto {
            white-space: pre-wrap;
            line-height: 1.3;
            padding-bottom: 2pt;
        }
        .lineas-obs {
            border-bottom: var(--grid-line) solid #000;
            min-height: 0.52cm;
            margin-top: 0.14cm;
        }
        .obs-texto-completo {
            margin-top: 0.14cm;
            white-space: pre-wrap;
            line-height: 1.35;
            padding-bottom: 2pt;
            border-bottom: var(--grid-line) solid #000;
        }
        .resumen-fila {
            display: flex;
            align-items: flex-end;
            flex-wrap: nowrap;
            gap: 4pt;
            margin: 0;
            padding: 5pt 8pt 6pt;
        }
        .resumen-fila + .resumen-fila {
            padding-top: 2pt;
        }
        .resumen-etiq {
            flex: 0 0 auto;
            font-weight: bold;
        }
        .resumen-relleno {
            flex: 1 1 auto;
            border-bottom: var(--grid-line) solid #000;
            min-height: 1.05em;
            padding: 0 4pt 2pt;
            text-align: left;
        }
        .tabla-criterios thead th {
            text-align: center;
            font-weight: bold;
            font-size: 7.5pt;
            line-height: 1.2;
            padding: 4pt 3pt;
            background: #fff;
        }
        .tabla-criterios .col-num { width: 5.5%; text-align: center; }
        .tabla-criterios .col-criterio { width: 46%; text-align: left; }
        .tabla-criterios .col-nivel { width: 9.7%; text-align: center; }
        .tabla-criterios .col-nivel-grupo {
            text-align: center;
            font-size: 7.5pt;
        }
        .tabla-criterios tbody td {
            min-height: 0.62cm;
            padding-top: 6pt;
            padding-bottom: 6pt;
        }
        .tabla-criterios tbody td.col-num {
            text-align: center;
            vertical-align: middle;
        }
        .tabla-criterios tbody td.col-criterio {
            line-height: 1.28;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            hyphens: auto;
            text-align: left;
            padding-left: 6pt;
            padding-right: 5pt;
            vertical-align: middle;
        }
        .tabla-criterios tbody td.col-nivel {
            text-align: center;
            vertical-align: middle;
        }
        .marca-x {
            font-weight: bold;
            font-size: 10pt;
            line-height: 1;
        }
        @media print {
            html, body {
                overflow: visible !important;
            }
            body { max-width: none; padding: 0; }
            .print-page {
                width: calc(8.5in - 0.84in);
                max-width: calc(8.5in - 0.84in);
                min-height: calc(11in - 0.8in);
                height: auto !important;
                margin: 0.38in 0.42in 0.42in 0.42in;
                overflow: visible !important;
            }
            .foreground {
                overflow: visible !important;
            }
            .pagina-indicador {
                top: 1.22in !important;
                right: 1.72in !important;
                z-index: 3 !important;
            }
            .formulario-caja {
                border: var(--grid-line) solid #000 !important;
                background: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .formulario-caja-pie {
                padding-bottom: 0.85cm !important;
            }
            .tabla-criterios th,
            .tabla-criterios td {
                border: none !important;
                border-right: var(--grid-line) solid #000 !important;
                border-bottom: var(--grid-line) solid #000 !important;
                background: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .tabla-criterios th:last-child,
            .tabla-criterios td:last-child {
                border-right: none !important;
            }
            .tabla-criterios tbody tr:last-child td {
                border-bottom: none !important;
            }
            .obs-linea-primera,
            .lineas-obs,
            .obs-texto-completo,
            .resumen-relleno {
                border-bottom: var(--grid-line) solid #000 !important;
            }
            .tabla-criterios,
            .tabla-criterios tr,
            .tabla-criterios td,
            .tabla-criterios th {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
@php
    $lista = collect($evaluaciones ?? [])->values();
    $criterios = $criteriosEvaluacion ?? \App\Support\EvaluacionExtraescolarFormulario::CRITERIOS;
    $etiquetaActividad = $etiquetaCampoActividad ?? 'Actividad Cultural y/o Deportiva';
@endphp

@php $totalPaginasEval = $lista->count(); @endphp
@foreach($lista as $pIndex => $evaluacion)
    @php $d = \App\Support\EvaluacionExtraescolarFormulario::datosImpresion($evaluacion); @endphp
    <div class="print-page">
        <img class="print-page-bg" src="" alt="" />
        <div class="pagina-indicador" aria-hidden="true">Página {{ $pIndex + 1 }} de {{ $totalPaginasEval }}</div>
        <div class="foreground">
            @php
                $lineasEnc = $encabezadoEvaluacion ?? [
                    'INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA',
                    'Subdirección de Planeación y Vinculación',
                    'DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES',
                    'OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA',
                ];
            @endphp
            <div class="encabezado-institucional">
                <p>{{ $lineasEnc[0] ?? '' }}</p>
                <p>{{ $lineasEnc[1] ?? '' }}</p>
                <p class="titulo-principal">{{ $lineasEnc[2] ?? '' }}</p>
                <p class="titulo-principal">{{ $lineasEnc[3] ?? '' }}</p>
            </div>

            <div class="datos-estudiante">
                <p><strong>Nombre del estudiante:</strong> {{ $d['nombreEstudiante'] }}</p>
                <p><strong>{{ $etiquetaActividad }}:</strong> {{ $d['lineaActividad'] }}</p>
                <p><strong>Periodo de realización:</strong> {{ $d['periodoRealizacion'] }}</p>
            </div>

            <div class="tabla-criterios-wrap">
                <div class="formulario-caja">
                <table class="tabla-criterios" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="col-num" rowspan="2">No.</th>
                            <th class="col-criterio" rowspan="2">Criterios a evaluar</th>
                            <th class="col-nivel-grupo" colspan="5">Nivel de desempeño del criterio</th>
                        </tr>
                        <tr>
                            <th class="col-nivel">Insuficiente</th>
                            <th class="col-nivel">Suficiente</th>
                            <th class="col-nivel">Bueno</th>
                            <th class="col-nivel">Notable</th>
                            <th class="col-nivel">Excelente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criterios as $i => $textoCriterio)
                            @php $marcasFila = $d['marcasFilas'][$i] ?? []; @endphp
                            <tr>
                                <td class="col-num">{{ $i + 1 }}</td>
                                <td class="col-criterio">{{ $textoCriterio }}</td>
                                @foreach(\App\Support\EvaluacionExtraescolarFormulario::COLUMNAS_NIVEL as $col)
                                    <td class="col-nivel">
                                        @if($marcasFila[$col] ?? false)
                                            <span class="marca-x">X</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <div class="formulario-caja formulario-caja-pie">
                <div class="formulario-pie">
                    <div class="observaciones-block">
                        <div class="observaciones-titulo-row">
                            <span class="obs-etiqueta">Observaciones:</span>
                            @if($d['observaciones'] === '')
                                <span class="obs-linea-primera"></span>
                            @else
                                <span class="obs-linea-primera obs-con-texto">{{ $d['observaciones'] }}</span>
                            @endif
                        </div>
                        @if($d['observaciones'] === '')
                            <div class="lineas-obs"></div>
                            <div class="lineas-obs"></div>
                            <div class="lineas-obs"></div>
                        @else
                            <div class="lineas-obs"></div>
                            <div class="lineas-obs"></div>
                            <div class="lineas-obs"></div>
                        @endif
                    </div>

                    <p class="resumen-fila">
                        <span class="resumen-etiq">Valor numérico de la actividad Cultural y/o Deportiva:</span>
                        <span class="resumen-relleno">{{ $d['valorNumerico'] }}</span>
                    </p>
                    <p class="resumen-fila">
                        <span class="resumen-etiq">Nivel de desempeño alcanzado de la actividad Cultural y/o Deportiva:</span>
                        <span class="resumen-relleno">{{ $d['nivelAlcanzado'] }}</span>
                    </p>
                </div>
                </div>
            </div>
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
    function finalizarImpresion() {
        requestAnimationFrame(function () {
            setTimeout(function () {
                try { window.focus(); window.print(); } catch (e) {}
            }, 400);
        });
    }
    function iniciar() {
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
            .catch(function () {})
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
