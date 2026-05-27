@php
    $cargoProfesor = mb_strtoupper(trim((string) ($evaluacion->cargo_profesor ?? 'Profesor responsable')), 'UTF-8');
    $cargoVobo = mb_strtoupper(trim((string) ($evaluacion->cargo_vobo ?? 'Subdirección de Planeación y Vinculación')), 'UTF-8');
    $nombreProfesorFirma = trim((string) ($evaluacion->nombre_profesor ?? ''));
    if ($nombreProfesorFirma === '') {
        $nombreProfesorFirma = '_______________________________';
    }
    $nombreVoboFirma = trim((string) ($evaluacion->jefe_extraescolares ?? ''));
    if ($nombreVoboFirma === '') {
        $nombreVoboFirma = '_______________________________';
    }
@endphp
<div class="firmas">
    <div class="firmas-columns">
        <div class="firma-izq">
            <div class="etiqueta-firma">&nbsp;</div>
            <div class="firma-linea"></div>
            <div class="firma-nombre">
                <strong>{{ $nombreProfesorFirma }}</strong><br>
                <strong style="font-size: 8pt; color: #000;">{{ $cargoProfesor }}</strong>
            </div>
        </div>
        <div class="firma-der">
            <div class="etiqueta-firma">&nbsp;</div>
            <div class="firma-linea"></div>
            <div class="firma-nombre">
                <strong>{{ $nombreVoboFirma }}</strong><br>
                <strong style="font-size: 8pt; color: #000; display: inline-block; max-width: 240px; line-height: 1.2;">{{ $cargoVobo }}</strong>
            </div>
        </div>
    </div>
</div>
