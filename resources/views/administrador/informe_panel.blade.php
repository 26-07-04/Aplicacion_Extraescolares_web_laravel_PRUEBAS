@extends('administrador.Principal_administrador')

@section('main-content')
<div style="padding: 0 0 32px 0;">
    @include('administrador.informe_actividad', [
        'user' => $user,
        'id_semestre' => $id_semestre,
        'informes' => $informes,
        'documentos' => $documentos,
        'actividades' => $actividades,
    ])
</div>
@endsection
