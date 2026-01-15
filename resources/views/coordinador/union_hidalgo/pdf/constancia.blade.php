<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Constancia de Cumplimiento</title>
    <style>
        @page { size: letter; margin: 0; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size:10pt; color:#000; }
        .content { padding: 5.0cm 2.5cm 2.3cm 2.5cm; }
        .titulo-principal { text-align:center; font-weight:bold; font-size:12pt; margin-top:0.6cm; margin-bottom:0.9cm; text-transform:uppercase; }
        .lugar-fecha-top { text-align:right; font-size:10pt; margin-top:-0.4cm; margin-bottom:0.6cm; }
        .destinatario { margin-bottom:0.9cm; line-height:1.25; }
        .cuerpo { text-align:justify; margin-bottom:0.8cm; line-height:1.45; }
        .atentamente { text-align:center; font-weight:bold; margin-top:0.1cm; margin-bottom:0.5cm; }
        .firmas { width:100%; margin-top:1.4cm; margin-bottom:0.8cm; }
        .firmas-columns { display: table; width:100%; table-layout: fixed; }
        .firma-izq, .firma-der { display: table-cell; width:50%; text-align:center; vertical-align: top; padding:0 15px; }
        .firma-linea { border-top:1px solid #000; width:80%; margin:0.25rem auto; height:1px; }
        .firma-nombre { margin-top:14px; font-size:9pt; }
        .vobo { font-size:9pt; margin-bottom:20px; }
        .copia { font-size:9pt; margin-top:-0.6cm; }
    </style>
</head>
<body>
    @php
        $documentoMembrete = $documentoMembrete ?? null;
    @endphp
    @if($documentoMembrete)
        @php
            $membreteSrc = null;
            $archivoRel = ltrim($documentoMembrete->archivo, '/');
            $candidatePaths = [public_path($archivoRel), base_path('public/' . $archivoRel), public_path('Documentos/' . basename($archivoRel)), public_path('storage/Documentos/' . basename($archivoRel))];
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
            <img style="position:fixed;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:-1;" src="{{ $membreteSrc }}" alt="">
        @endif
    @endif
    <div class="content">
        <div class="titulo-principal">CONSTANCIA DE CUMPLIMIENTO DE ACTIVIDADES COMPLEMENTARIAS</div>

        <div class="destinatario">
            <p><strong>{{ $evaluacion->jefe_servicios_escolares ?? '_____________________________________' }}</strong></p>
            <p><strong>JEFE DEL DEPARTAMENTO DE SERVICIOS ESCOLARES</strong></p>
            <p><strong>PRESENTE</strong></p>
        </div>
        <div class="cuerpo">
            @php
                // Obtener la categoría directamente desde la actividad guardada en BD
                $categoria = strtoupper(trim($actividad->categorias ?? 'COMPLEMENTARIA'));
            @endphp
            <p>El que suscribe: <strong>{{ $evaluacion->nombre_profesor ?? '______________________________' }}</strong>, por este medio se permite hacer de su conocimiento que el (la) estudiante: <strong>{{ $estudiante->nombre }}</strong> con número de control: <strong>{{ $estudiante->numero_control }}</strong> de la carrera de <strong>{{ $estudiante->carrera }}</strong>, ha cumplido su actividad complementaria <strong>{{ $categoria }}</strong> "{{ $actividad->nombre_actividad ?? ($evaluacion->actividad->nombre_actividad ?? '________________') }}" con el nivel de desempeño <strong>{{ strtoupper($evaluacion->nivel_desempeno ?? '') }}</strong> y un valor numérico de <strong>{{ number_format($evaluacion->calificacion_numerica ?? 0, 1) }}</strong> durante el ciclo escolar <strong>{{ $semestre->nombre ?? 'N/A' }}</strong>, con un valor curricular de UN crédito.</p>
        </div>
        <div style="margin-bottom:1.2cm;">Se extiende la presente en Santiago Suchilquitongo, Oax, a los <strong>{{ $fecha->day }}</strong> días del mes de <strong>{{ strtolower($fecha->locale('es')->translatedFormat('F')) }}</strong> de <strong>{{ $fecha->year }}</strong>.</div>
        <div class="atentamente">A T E N T A M E N T E</div>
        <div style="text-align:center; font-size:9pt; margin-bottom:0.4cm;"><strong><em>Excelencia en Educación Tecnológica®</em></strong><br><strong><em>“Ciencia y Sustentbilidad al Servicio de la Humanidad”</em></strong></div>
        <div class="firmas">
            <div class="firmas-columns">
                <div class="firma-izq">
                    <div class="etiqueta-firma">&nbsp;</div>
                    <div class="firma-linea"></div>
                    <div class="firma-nombre"><strong>{{ strtoupper($evaluacion->nombre_profesor ?? '_______________________________') }}</strong><br><strong style="font-size: 8pt; color: #000;">DOCENTE RESPONSABLE</strong></div>
                </div>
                <div class="firma-der">
                    <div class="etiqueta-firma">&nbsp;</div>
                    <div class="firma-linea"></div>
                    <div class="firma-nombre"><div class="vobo" style="margin-top:6px; margin-bottom:6px;"><strong>Vo. Bo.</strong></div><strong>{{ $evaluacion->jefe_extraescolares ?? '_______________________________' }}</strong><br><strong style="font-size: 8pt; color: #000;">JEFE DEL DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES</strong></div>
                </div>
            </div>
        </div>
        <div class="copia">c.c.p. Jefe del departamento correspondiente.<br>c.c.p. Departamento de Servicios Escolares</div>
    </div>
</body>
</html>
