<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Constancia de Cumplimiento</title>
    <style>
        @page {
            size: letter;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
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
        
        .content {
            padding: 5.0cm 2.5cm 2.3cm 2.5cm; /* bajar más el contenido y compactar fondo */
        }
        
        .container {
            position: relative;
            width: 100%;
            height: auto;
        }
        
        
        /* Título principal */
        .titulo-principal {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 0.6cm; /* reduce espacio superior del título */
            margin-bottom: 0.9cm; /* compacta espacio inferior del título */
            text-transform: uppercase;
        }

        /* Lugar y fecha bajo título */
        .lugar-fecha-top {
            text-align: right;
            font-size: 10pt;
            margin-top: -0.4cm; /* acercar al título */
            margin-bottom: 0.6cm; /* separación del destinatario */
        }
        
        /* Destinatario */
        .destinatario {
            margin-bottom: 0.9cm; /* compactar bloque destinatario */
            line-height: 1.25;
            word-wrap: break-word;
        }
        
        .destinatario p {
            margin: 0;
            word-wrap: break-word;
        }
        
        /* Cuerpo del texto */
        .cuerpo {
            text-align: justify;
            margin-bottom: 0.8cm; /* compactar cuerpo */
            line-height: 1.45;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Lugar y fecha */
        .lugar-fecha {
            margin-bottom: 1.2cm; /* reducir separación previa a firmas */
            line-height: 1.3;
        }
        
        /* Atentamente */
        .atentamente {
            text-align: center;
            font-weight: bold;
            margin-top: 0.1cm;
            margin-bottom: 0.5cm; /* compactar */
        }
        
        /* Firmas */
        .firmas {
            width: 100%;
            margin-top: 1.4cm; /* reducido para subir visualmente las firmas */
            margin-bottom: 0.8cm;
        }

        .firmas-columns {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        .firma-izq, .firma-der {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
        }
        
        .firma-linea {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0.25rem auto;
            height: 1px;
        }

        .etiqueta-firma {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 6px;
            min-height: 18px;
        }

        .sello-centered {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 6px;
            margin-top: -6px;
        }

        .firma-nombre {
            margin-top: 14px; /* separación adicional bajo la línea */
            font-size: 9pt;
        }
        
        .vobo {
            font-size: 9pt;
            margin-bottom: 20px; /* compactar ligeramente */
        }

        /* Ajuste local: mover solo el 'Vo. Bo.' del bloque derecho sin afectar el resto */
        .firma-der .vobo {
            margin-top: 0px;
            margin-bottom: -2px; /* aún más pegado a la línea */
        }

        /* Reducir espacio entre la línea y el bloque de la derecha sin mover la línea */
        .firma-der .firma-nombre {
            margin-top: -4px; /* acercar el bloque derecho a la línea */
        }

        /* Reducir espacio entre la línea y el bloque de la izquierda sin mover la línea */
        .firma-izq .firma-nombre {
            margin-top: -4px; /* acercar el bloque izquierdo a la línea para simetría */
        }
        
        /* Copia */
        .copia {
            font-size: 9pt;
            margin-top: -0.6cm; /* subir más el bloque de copias (valor negativo) */
        }
        
        /* Pie de página */
        .footer {
            position: relative;
            margin-top: 0.8cm;
            font-size: 7.5pt;
            padding-top: 2px;
        }
        .footer-inner {
            display: table;
            width: 100%;
        }
        .footer-left, .footer-right {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }
        .footer-left { text-align: left; }
        .footer-right { text-align: right; }
        
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    @if($documentoMembrete)
        @php
            $membreteSrc = null;
            $archivoRel = ltrim($documentoMembrete->archivo, '/');
            // Prioridad: usar ruta relativa almacenada tal cual bajo public/
            $candidatePaths = [
                public_path($archivoRel),
                base_path('public/' . $archivoRel),
                // Fallbacks comunes
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
    <div class="content">
    <div class="container">

        <!-- Título principal -->
        <div class="titulo-principal">
            CONSTANCIA DE CUMPLIMIENTO DE ACTIVIDADES COMPLEMENTARIAS
        </div>

        <!-- Destinatario -->
        <div class="destinatario">
            <p><strong>{{ $evaluacion->jefe_servicios_escolares ?? '_____________________________________' }}</strong></p>
            <p><strong>JEFE DEL DEPARTAMENTO DE SERVICIOS ESCOLARES</strong></p>
            <p><strong>PRESENTE</strong></p>
        </div>

        <!-- Cuerpo del documento -->
        <div class="cuerpo">
            @php
                // Obtener la categoría directamente desde la actividad guardada en BD
                $categoria = strtoupper(trim($actividad->categorias ?? 'COMPLEMENTARIA'));
            @endphp
            <p>
                El que suscribe: <strong>{{ $evaluacion->nombre_profesor ?? '______________________________' }}</strong>, por este medio se permite hacer de su conocimiento que el (la) estudiante: <strong>{{ $estudiante->nombre }}</strong> con número de control: <strong>{{ $estudiante->numero_control }}</strong> de la carrera de <strong>{{ $estudiante->carrera }}</strong>, ha cumplido su actividad complementaria <strong>{{ $categoria }}</strong> "{{ $actividad->nombre_actividad ?? ($evaluacion->actividad->nombre_actividad ?? '________________') }}" con el nivel de desempeño <strong>{{ strtoupper($evaluacion->nivel_desempeno ?? '') }}</strong> y un valor numérico de <strong>{{ number_format($evaluacion->calificacion_numerica ?? 0, 1) }}</strong> durante el ciclo escolar <strong>{{ $semestre->nombre ?? 'N/A' }}</strong>, con un valor curricular de UN crédito.
            </p>
        </div>

        <!-- Lugar y fecha -->
        <div class="lugar-fecha">
            <p style="margin-top: 0.2cm;">
                Se extiende la presente en Santiago Suchilquitongo, Oax, a los <strong>{{ $fecha->day }}</strong> días del mes de <strong>{{ $fecha->locale('es')->translatedFormat('F') }}</strong> de <strong>{{ $fecha->year }}</strong>.
            </p>
        </div>

        <!-- Atentamente -->
        <div class="atentamente">
            A T E N T A M E N T E
        </div>
        <div style="text-align:center; font-size:9pt; margin-bottom:0.4cm;">
            <strong><em>Excelencia en Educación Tecnológica®</em></strong><br>
            <strong><em>“Ciencia y Sustentbilidad al Servicio de la Humanidad”</em></strong>
        </div>

        <!-- Firmas -->
        <div class="firmas">
            <div class="sello-centered">Sello</div>
            <div class="firmas-columns">
                <div class="firma-izq">
                    <div class="etiqueta-firma">&nbsp;</div>
                    <div class="firma-linea"></div>
                    <div class="firma-nombre">
                        <strong>{{ strtoupper($evaluacion->nombre_profesor ?? '_______________________________') }}</strong><br>
                        <strong style="font-size: 8pt; color: #000;">DOCENTE RESPONSABLE</strong>
                    </div>
                </div>
                <div class="firma-der">
                    <div class="etiqueta-firma">&nbsp;</div>
                    <div class="firma-linea"></div>
                    <div class="firma-nombre">
                        <div class="vobo" style="margin-top:6px; margin-bottom:6px;"><strong>Vo. Bo.</strong></div>
                        <strong>{{ $evaluacion->jefe_extraescolares ?? '_______________________________' }}</strong><br>
                        <strong style="font-size: 8pt; color: #000;">JEFE DEL DEPARTAMENTO DE ACTIVIDADES COMPLEMENTARIAS</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copia -->
        <div class="copia">
            c.c.p. Jefe del departamento correspondiente.<br>
            c.c.p. Departamento de Servicios Escolares
        </div>

        
    </div>
    </div>
</body>
</html>
