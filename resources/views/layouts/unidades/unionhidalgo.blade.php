@extends('layouts.app')

@section('title', 'Unidad CIDERS Unión Hidalgo')

@section('content')
<div class="container">
    <h1>Unidad CIDERS Unión Hidalgo</h1>
    <p>Bienvenido, este es el panel de la Unidad CIDERS Unión Hidalgo.</p>
    
    @isset($selectedSemestre)
        <div class="selectedSemestreCard">
            <h3>{{ $selectedSemestre->nombre }}</h3>
            <p><strong>Inicio:</strong> {{ date('d/m/Y', strtotime($selectedSemestre->fecha_inicio)) }}</p>
            <p><strong>Fin:</strong> {{ date('d/m/Y', strtotime($selectedSemestre->fecha_fin)) }}</p>
            <a href="{{ route('coordinador.semestres.union') }}">Volver a Semestres - Unión Hidalgo</a>
        </div>
    @endisset

    {{-- Aquí agregas actividades, reportes, etc. --}}
</div>
@endsection

@auth
    @push('scripts')
    <!-- Formulario oculto para logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @endpush
@endauth