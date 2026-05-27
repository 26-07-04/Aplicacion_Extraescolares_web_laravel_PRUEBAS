@include('coordinador.partials.constancia_cargos_editable', [
    'constanciaCargosStorageKey' => $constanciaCargosStorageKey ?? 'constancia_cargos_extraescolar',
    'initFunctionName' => 'inicializarConstanciaExtraescolarCargos',
    'obtenerFunctionName' => 'obtenerCargosConstanciaExtraescolar',
    'defaultCargoProfesor' => 'Profesor responsable',
    'defaultCargoVobo' => 'Subdirección de Planeación y Vinculación',
    'defaultCargoDestinatario' => 'Jefe del Departamento de Servicios Escolares',
])
