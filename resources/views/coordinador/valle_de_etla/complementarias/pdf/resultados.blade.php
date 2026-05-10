<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Resultados – {{ $semestre->nombre ?? '' }}</title>
    <style>
        @page { size: letter; margin: 1.6cm 1.5cm 1.6cm 1.5cm; }

        body { font-family: Arial, Helvetica, sans-serif; color: #000; }

        /* Encabezado institucional */
        .print-header { width: 100%; border-collapse: collapse; border: 2px solid #000; }
        .print-header td { border: 1px solid #000; vertical-align: middle; padding: 2px 4px; }
        .print-header td.title { vertical-align: top; }
        .print-header .logo { width: 115px; text-align: center; padding: 0; }
        .print-header .logo img {
            width: 100%;
            height: 86px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            image-rendering: -webkit-optimize-contrast;
        }
        .print-header .title { padding: 0; font-size: 9.2pt; line-height: 1.18; height: 48px; }
        .print-header .title .title-top {
            display: block;
            height: 50%;
            padding: 0 6px;
            border-bottom: 1px solid #000;
        }
        .print-header .title .title-bottom { padding: 4px 6px; }
        .print-header .title .title-top { font-weight: normal; }
        .print-header .title .title-bottom strong { font-size: 9.4pt; }
        .print-header .code { width: 190px; padding: 0; vertical-align: top; }
        .code-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .code-table td { border: none; border-top: 1px solid #000; padding: 2px 4px; text-align: left; font-weight: bold; font-size: 9pt; }
        .code-table tr:nth-child(3) td { text-align: left; height: 24px; line-height: 24px; padding-top: 5px; }
        .code-table tr:first-child td { border-top: none; padding-top: 8px; }

        /* Título del documento */
        .document-title { text-align: center; margin: 32px 0 10px 0; }
        .document-title h1 { font-size: 11pt; margin: 2px 0; font-weight: 700; }

        /* Tabla resultados */
        .results-table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-top: 32px; table-layout: fixed; }
        .results-table colgroup col.numero { width: 4%; }
        .results-table colgroup col.nombre { width: 32%; }
        .results-table colgroup col.control { width: 11%; }
        .results-table colgroup col.carrera { width: 20%; }
        .results-table colgroup col.sem { width: 6%; }
        .results-table colgroup col.resultado { width: 13%; }
        .results-table colgroup col.firma { width: 14%; }
        .results-table thead th { border: 1px solid #000; padding: 5px 4px; text-align: center; font-weight: bold; }
        .results-table tbody td { border: 1px solid #000; padding: 5px 4px; }
        .results-table td.numero, .results-table td.sem { text-align: center; }
        .results-table td.control { text-align: center; }

        .results-table thead { display: table-header-group; }

        .badge-resultado { display: inline-block; padding: 3px 6px; font-weight: normal; border: none; }
        .firma { height: 24px; border: none; }

        /* Pie de página */
        .print-footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 8.5pt; padding-top: 6px; }
        .print-footer .row { display: flex; justify-content: space-between; align-items: center; }
        .print-footer .left, .print-footer .right { font-weight: normal; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @php
        $lista = ($evaluaciones ?? collect())->values();
        $rowsPerPage = 18; // filas por página
        $pages = $lista->chunk($rowsPerPage);
        $totalPages = max(1, $pages->count());
        $globalCounter = 0;
    @endphp

    @if($lista->count() > 0)
        @foreach($pages as $pIndex => $pageItems)
            <!-- Encabezado por página -->
            <table class="print-header">
                <tr>
                    <td class="logo">
                        <img src="{{ asset('Imagenes/sgc.jpg') }}" alt="SGC TecNM">
                    </td>
                    <td class="title">
                        <div class="title-top">Formato de Resultados de Actividades Culturales y/o<br>Deportivas.</div>
                        <div class="title-bottom"><strong>Referencia a la Norma ISO 9001:2015&nbsp;&nbsp;8.1 8.2.1 8.2.2</strong></div>
                    </td>
                    <td class="code">
                        <table class="code-table">
                            <tr><td>Código: TecNM-VI-PO-003-03</td></tr>
                            <tr><td>Revisión: 0</td></tr>
                            <tr><td>Página {{ $pIndex + 1 }} de {{ $totalPages }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>

            @if($pIndex == 0)
                <!-- Título del documento (solo primera página) -->
                <div class="document-title">
                    <h1>DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES</h1>
                    <h1>OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA</h1>
                    <h1>{{ (isset($tipo) && $tipo === 'deportiva') ? 'ACTIVIDAD DEPORTIVA' : 'ACTIVIDAD CULTURAL' }}</h1>
                </div>
            @endif

            <!-- Tabla de resultados (página actual) -->
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
                        <th>NOMBRE</th>
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
                            <td style="text-align:center;">
                                <span class="badge-resultado">{{ $ev->nivel_desempeno ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="firma"></div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($pIndex + 1 < $totalPages)
                <div class="page-break"></div>
            @else
                <div style="height: 24px;"></div>
                @php
                    \Carbon\Carbon::setLocale('es');
                    $fechaHoy = \Carbon\Carbon::now('America/Mexico_City')->translatedFormat('j \\de F \\de Y');
                @endphp
                <p style="font-size: 9.5pt;">
                    <strong>Lugar y fecha:</strong>
                    {{ $lugar ?? 'Valle de Etla, Oax' }}, a {{ $fechaHoy }}.
                </p>
                <!-- Espacio amplio para firma y sello -->
                <div style="height: 100px;"></div>
                <!-- Apartado de firmas en una sola fila, 3 columnas alineadas -->
                <table style="width:100%; border-collapse:collapse; font-size: 9pt;">
                    <tr>
                        <td style="width:33.33%; text-align:center; padding:6px;">
                            <div style="border-top:1px solid #000; width:85%; margin:0 auto 6px auto;"></div>
                            Promotor Cultural o<br>Deportivo.
                        </td>
                        <td style="width:33.33%; text-align:center; padding:6px;">
                            <div style="border-top:1px solid #000; width:85%; margin:0 auto 6px auto;"></div>
                            Jefe de Oficina de Promoción<br>Cultural o Deportiva.
                        </td>
                        <td style="width:33.33%; text-align:center; padding:6px;">
                            <div style="border-top:1px solid #000; width:85%; margin:0 auto 6px auto;"></div>
                            Jefe de Departamento de<br>Actividades Complementarias.
                        </td>
                    </tr>
                </table>
            @endif
        @endforeach
    @else
        <p style="text-align:center; margin-top:24px;">No hay evaluaciones registradas.</p>
    @endif

    <!-- Pie de página -->
    <div class="print-footer">
        <div class="row">
            <div class="left">TecNM-VI-PO-003-03</div>
            <div class="right">Rev. 0</div>
        </div>
    </div>

    <script>
        (function() {
            try {
                const pn = document.querySelector('.page-number');
                const pt = document.querySelector('.page-total');
                if (pn) pn.textContent = '1';
                if (pt) pt.textContent = '1';
            } catch (e) {}
        })();
    </script>
</body>
</html>