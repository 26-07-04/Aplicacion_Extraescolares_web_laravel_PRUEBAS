@php
  $unidad = 'Valle de Etla';
  $actividades = [
    ['id'=>1,'nombre'=>'Guitarra'],
    ['id'=>2,'nombre'=>'Programación']
  ];
  $estudiantes = [
    'Guitarra' => [
      ['nombre'=>'Diego Morales','control'=>'20181001','semestre'=>'9','carrera'=>'IME']
    ],
    'Programación' => [
      ['nombre'=>'Laura Fernández','control'=>'20191021','semestre'=>'5','carrera'=>'ISC'],
      ['nombre'=>'Pedro Castillo','control'=>'20191022','semestre'=>'3','carrera'=>'IME']
    ]
  ];
@endphp

@include('administrador.partials.unidad', ['unidad' => $unidad, 'actividades' => $actividades, 'estudiantes' => $estudiantes])
