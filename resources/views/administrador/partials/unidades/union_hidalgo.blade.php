@php
  $unidad = 'CIDERS unión Hidalgo';
  $actividades = [
    ['id'=>1,'nombre'=>'Fútbol'],
    ['id'=>2,'nombre'=>'Banda de Guerra'],
    ['id'=>3,'nombre'=>'Taller de Teatro']
  ];
  $estudiantes = [
    'Fútbol' => [
      ['nombre'=>'María López','control'=>'20201001','semestre'=>'4','carrera'=>'ISC'],
      ['nombre'=>'Luis Hernández','control'=>'20201003','semestre'=>'2','carrera'=>'IGE']
    ],
    'Banda de Guerra' => [
      ['nombre'=>'Ana Gómez','control'=>'20201005','semestre'=>'2','carrera'=>'IGE']
    ],
    'Taller de Teatro' => []
  ];
@endphp

@include('administrador.partials.unidad', ['unidad' => $unidad, 'actividades' => $actividades, 'estudiantes' => $estudiantes])
