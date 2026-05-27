@php
    $categoriaRaw = trim((string) ($actividad->categorias ?? ''));
    $nombreActividad = trim((string) ($actividad->nombre_actividad ?? ($evaluacion->actividad->nombre_actividad ?? '')));
    if ($nombreActividad === '') {
        $nombreActividad = '________________';
    }
    if ($categoriaRaw !== '') {
        $lineaActividad = \App\Support\EvaluacionExtraescolarFormulario::etiquetaCategoriaCorta($categoriaRaw) . ' (' . $nombreActividad . ')';
    } else {
        $lineaActividad = $nombreActividad;
    }

    $cal = (float) ($evaluacion->calificacion_numerica ?? 0);
    $calStr = (abs($cal - round($cal)) < 0.001)
        ? (string) (int) round($cal)
        : number_format($cal, 1, '.', '');

    $nivelRaw = trim((string) ($evaluacion->nivel_desempeno ?? ''));
    $nivelTitulo = $nivelRaw === '' ? '________' : mb_convert_case(mb_strtolower($nivelRaw, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');

    $periodoEscolar = trim((string) ($semestre->nombre ?? ''));
    if ($periodoEscolar === '') {
        $periodoEscolar = 'N/A';
    }

    $nombreQuienSuscribe = trim((string) ($evaluacion->nombre_profesor ?? ''));
    if ($nombreQuienSuscribe === '') {
        $nombreQuienSuscribe = '______________________________________';
    }
@endphp
<p>
    El/la que suscribe <strong>{{ $nombreQuienSuscribe }}</strong> por este medio me permito hacer de su conocimiento que el estudiante
    {{ $estudiante->nombre }} con número de control {{ $estudiante->numero_control }}
    de la carrera de {{ $estudiante->carrera }}, ha cumplido su actividad cultural y/o deportiva,
    {{ $lineaActividad }}, con el nivel de desempeño <strong>{{ $nivelTitulo }}</strong>
    y un valor numérico de {{ $calStr }}, durante el periodo escolar {{ $periodoEscolar }},
    con un valor curricular de <strong>1 crédito</strong>.
</p>
