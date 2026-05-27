<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Constancia de cumplimiento de actividad complementaria</title>
    <style>
        @page { size: letter; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
            position: relative;
        }
        .bg-membrete {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .content { padding: 5.8cm 2.4cm 2cm 2.4cm; }
        .container { position: relative; width: 100%; }
        .titulo-principal {
            text-align: center;
            font-weight: bold;
            font-size: 11.5pt;
            margin-bottom: 0.85cm;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .destinatario { margin-bottom: 0.85cm; line-height: 1.35; }
        .destinatario p { margin: 0; }
        .cuerpo {
            text-align: justify;
            margin-bottom: 0.75cm;
            line-height: 1.5;
        }
        .cuerpo p { margin: 0; text-indent: 0; }
        .lugar-fecha { margin-bottom: 0.9cm; line-height: 1.45; }
        .atentamente {
            text-align: center;
            font-weight: bold;
            margin-top: 0.15cm;
            margin-bottom: 0.35cm;
            letter-spacing: 0.08em;
        }
        .lema-block {
            text-align: center;
            font-size: 9.5pt;
            margin-bottom: 0.75cm;
            line-height: 1.4;
        }
        .firmas { width: 100%; margin-top: 1.1cm; }
        table.firmas-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.firmas-table td.firma-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 10px;
        }
        .firma-vobo-spacer {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 0.15cm;
            min-height: 1.15em;
            color: transparent;
        }
        .firma-espacio { min-height: 0.85cm; }
        .firma-linea {
            border-top: 1px solid #000;
            width: 78%;
            margin: 0.2rem auto 0.35rem;
        }
        .firma-nombre { font-size: 9pt; margin-top: 0.15cm; line-height: 1.35; }
        .vobo {
            font-size: 9pt;
            margin-bottom: 0.15cm;
            font-weight: bold;
            min-height: 1.15em;
        }
        strong { font-weight: bold; }
        .ccp-footer { margin-top: 0.55cm; text-align: left; font-size: 9pt; line-height: 1.4; }
        .ccp-footer p { margin: 0; }
    </style>
</head>
<body>
    @if($documentoMembrete)
        @php
            $membreteSrc = null;
            $archivoRel = ltrim($documentoMembrete->archivo, '/');
            $candidatePaths = [
                public_path($archivoRel),
                base_path('public/' . $archivoRel),
                public_path('Documentos/' . basename($archivoRel)),
                public_path('storage/Documentos/' . basename($archivoRel)),
            ];
            foreach ($candidatePaths as $membretePath) {
                if (file_exists($membretePath)) {
                    $ext = strtolower(pathinfo($membretePath, PATHINFO_EXTENSION));
                    $mime = in_array($ext, ['jpg','jpeg']) ? 'image/jpeg' : ($ext === 'png' ? 'image/png' : 'image/*');
                    $membreteSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($membretePath));
                    break;
                }
            }
        @endphp
        @if($membreteSrc)
            <img class="bg-membrete" src="{{ $membreteSrc }}" alt="">
        @endif
    @endif

    @php
        $lugarExtiende = 'El Espinal, Oax';

        $nombreJefeServicios = mb_strtoupper(trim((string) ($evaluacion->jefe_servicios_escolares ?? '')), 'UTF-8');
        if ($nombreJefeServicios === '') {
            $nombreJefeServicios = '_____________________________________';
        }

        $nombreQuienSuscribe = trim((string) ($evaluacion->nombre_profesor ?? ''));
        if ($nombreQuienSuscribe === '') {
            $nombreQuienSuscribe = '______________________________________';
        }

        $cal = (float) ($evaluacion->calificacion_numerica ?? 0);
        $calStr = (abs($cal - round($cal)) < 0.001)
            ? (string) (int) round($cal)
            : number_format($cal, 1, '.', '');

        $cred = max(1, (int) ($evaluacion->creditos ?? 1));
        $textoCreditos = $cred === 1 ? '1 crédito' : $cred . ' créditos';

        $mesNombre = mb_strtolower($fecha->copy()->locale('es')->translatedFormat('F'), 'UTF-8');

        $anioNum = (int) $fecha->year;
        $anioLetras = (string) $anioNum;
        if (extension_loaded('intl') && class_exists(\NumberFormatter::class)) {
            $nf = new \NumberFormatter('es_MX', \NumberFormatter::SPELLOUT);
            $tmp = $nf->format($anioNum);
            if ($tmp !== false && $tmp !== '') {
                $anioLetras = $tmp;
            }
        }
        if (preg_match('/^\d+$/', $anioLetras)) {
            $mapAnios = [
                2020 => 'dos mil veinte', 2021 => 'dos mil veintiuno', 2022 => 'dos mil veintidós', 2023 => 'dos mil veintitrés',
                2024 => 'dos mil veinticuatro', 2025 => 'dos mil veinticinco', 2026 => 'dos mil veintiséis', 2027 => 'dos mil veintisiete',
                2028 => 'dos mil veintiocho', 2029 => 'dos mil veintinueve', 2030 => 'dos mil treinta', 2031 => 'dos mil treinta y uno',
                2032 => 'dos mil treinta y dos', 2033 => 'dos mil treinta y tres', 2034 => 'dos mil treinta y cuatro', 2035 => 'dos mil treinta y cinco',
                2036 => 'dos mil treinta y seis', 2037 => 'dos mil treinta y siete', 2038 => 'dos mil treinta y ocho', 2039 => 'dos mil treinta y nueve',
                2040 => 'dos mil cuarenta',
            ];
            $anioLetras = $mapAnios[$anioNum] ?? $anioLetras;
        }

        $nombreActividad = trim((string) ($actividad->nombre_actividad ?? ''));
        if ($nombreActividad === '') {
            $nombreActividad = '________________';
        }

        $periodoEscolar = trim((string) ($semestre->nombre ?? ''));
        if ($periodoEscolar === '') {
            $periodoEscolar = 'N/A';
        }

        $nivelRaw = trim((string) ($evaluacion->nivel_desempeno ?? ''));
        $nivelTitulo = $nivelRaw === ''
            ? null
            : mb_convert_case(mb_strtolower($nivelRaw, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');

        $nombreDocenteFirma = mb_strtoupper(trim((string) ($evaluacion->nombre_profesor ?? '')), 'UTF-8');
        if ($nombreDocenteFirma === '') {
            $nombreDocenteFirma = '_______________________________________';
        }

        $nombreVoBo = mb_strtoupper(trim((string) ($evaluacion->jefe_extraescolares ?? '')), 'UTF-8');
        if ($nombreVoBo === '') {
            $nombreVoBo = '_______________________________________';
        }

        $presenteEspaciado = implode(' ', preg_split('//u', 'PRESENTE', -1, PREG_SPLIT_NO_EMPTY));

        $cargoProfesorPdf = mb_strtoupper(trim((string) ($evaluacion->cargo_profesor ?? 'Docente encargado')), 'UTF-8');
        $cargoVoboPdf = mb_strtoupper(trim((string) ($evaluacion->cargo_vobo ?? 'Subdirector Académico')), 'UTF-8');
        $cargoDestinatarioPdf = mb_strtoupper(
            \App\Support\EvaluacionExtraescolarFormulario::cargoDestinatarioEvaluacion($evaluacion, 'Jefe de Departamento de Servicios Escolares'),
            'UTF-8'
        );
    @endphp

    <div class="content">
        <div class="container">
            <div class="titulo-principal">
                Constancia de cumplimiento de actividad complementaria
            </div>

            <div class="destinatario">
                <p><strong>{{ $nombreJefeServicios }}</strong></p>
                <p><strong>{{ $cargoDestinatarioPdf }}</strong></p>
                <p><strong>{{ $presenteEspaciado }}</strong></p>
            </div>

            <div class="cuerpo">
                <p>
                    El/la que suscribe <strong>{{ $nombreQuienSuscribe }}</strong>, por este medio me permito hacer de su conocimiento que el estudiante
                    <strong>{{ $estudiante->nombre }}</strong> con número de control <strong>{{ $estudiante->numero_control }}</strong>
                    de la carrera de <strong>{{ $estudiante->carrera }}</strong> ha cumplido su actividad complementaria académica
                    <strong>{{ $nombreActividad }}</strong>, con el nivel de desempeño <strong>{{ $nivelTitulo !== null ? $nivelTitulo : '________' }}</strong>
                    y un valor numérico de <strong>{{ $calStr }}</strong>, durante el periodo escolar <strong>{{ $periodoEscolar }}</strong>,
                    con un valor curricular de <strong>{{ $textoCreditos }}</strong>.
                </p>
            </div>

            <div class="lugar-fecha">
                <p>
                    Se extiende la presente en {{ $lugarExtiende }} a los {{ $fecha->day }} días del mes de
                    {{ $mesNombre }} del año {{ $anioLetras }}.
                </p>
            </div>

            <div class="atentamente">ATENTAMENTE</div>

            <div class="lema-block">
                <div><strong>Excelencia en Educación Tecnológica®</strong></div>
                <div style="margin-top: 0.2cm;"><em>&ldquo;Ciencia y Sustentabilidad al Servicio de la Humanidad&rdquo;</em></div>
            </div>

            <div class="firmas">
                <table class="firmas-table" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="firma-cell">
                            <div class="firma-vobo-spacer" aria-hidden="true">Vo. Bo.</div>
                            <div class="firma-espacio"></div>
                            <div class="firma-linea"></div>
                            <div class="firma-nombre">
                                <strong>{{ $nombreDocenteFirma }}</strong><br>
                                <strong>{{ $cargoProfesorPdf }}</strong>
                            </div>
                        </td>
                        <td class="firma-cell">
                            <div class="vobo">Vo. Bo.</div>
                            <div class="firma-espacio"></div>
                            <div class="firma-linea"></div>
                            <div class="firma-nombre">
                                <strong>{{ $nombreVoBo }}</strong><br>
                                <strong>{{ $cargoVoboPdf }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            @include('coordinador.partials.constancia_complementarias_ccp_footer')
        </div>
    </div>
</body>
</html>
