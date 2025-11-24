@php
  $unidad = 'Unidad académica Tlahuitoltepec';
  $actividades = [
    ['id'=>1,'nombre'=>'Danza Folklórica'],
    ['id'=>2,'nombre'=>'Volibol']
  ];
  $estudiantes = [
    'Danza Folklórica' => [
      ['nombre'=>'Irene Torres','control'=>'20211001','semestre'=>'3','carrera'=>'IGE']
    ],
    'Volibol' => []
  ];
@endphp

@include('administrador.partials.unidad', ['unidad' => $unidad, 'actividades' => $actividades, 'estudiantes' => $estudiantes])
