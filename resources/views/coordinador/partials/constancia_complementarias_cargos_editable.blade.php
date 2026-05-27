@include('coordinador.partials.constancia_cargos_editable', [
    'constanciaCargosStorageKey' => $constanciaCargosStorageKey ?? 'constancia_cargos_complementarias',
    'initFunctionName' => 'inicializarConstanciaComplementariasCargos',
    'obtenerFunctionName' => 'obtenerCargosConstanciaComplementarias',
    'defaultCargoProfesor' => 'Docente encargado',
    'defaultCargoVobo' => 'Subdirector Académico',
    'defaultCargoDestinatario' => 'Jefe de Departamento de Servicios Escolares',
])
