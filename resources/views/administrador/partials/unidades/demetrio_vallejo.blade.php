@php
  $unidad = 'Unidad Demetrio Vallejo';
  $actividades = [
    ['id'=>1,'nombre'=>'Robótica'],
    ['id'=>2,'nombre'=>'Ajedrez']
  ];
  $estudiantes = [
    'Robótica' => [
      ['nombre'=>'Carlos Ruiz','control'=>'20191011','semestre'=>'8','carrera'=>'IME']
    ],
    'Ajedrez' => [
      ['nombre'=>'Sofía Ramírez','control'=>'20191012','semestre'=>'6','carrera'=>'ISC']
    ]
  ];
@endphp

@include('administrador.partials.unidad', ['unidad' => $unidad, 'actividades' => $actividades, 'estudiantes' => $estudiantes])
