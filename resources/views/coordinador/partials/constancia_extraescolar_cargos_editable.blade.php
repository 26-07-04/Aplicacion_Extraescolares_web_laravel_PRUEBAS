@include('coordinador.partials.constancia_cargos_editable', [
    'constanciaCargosStorageKey' => $constanciaCargosStorageKey ?? 'constancia_cargos_extraescolar',
    'initFunctionName' => 'inicializarConstanciaExtraescolarCargos',
    'obtenerFunctionName' => 'obtenerCargosConstanciaExtraescolar',
    'defaultCargoProfesor' => 'Profesor responsable',
    'defaultCargoVobo' => 'Jefe del Depto. de Actividades Extraescolares',
    'defaultCargoDestinatario' => 'Jefe del Departamento de Servicios Escolares',
])
