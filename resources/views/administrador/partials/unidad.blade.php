<div style="padding:18px;">
  <h2>Unidad: {{ $unidad }}</h2>

  @if(isset($actividades) && $actividades->isNotEmpty())
    <section style="margin-top:12px;">
      <h3>Actividades ({{ $actividades->count() }})</h3>
      <ul>
        @foreach($actividades as $actividad)
          <li>{{ $actividad->nombre ?? ('Actividad ' . $actividad->id) }}</li>
        @endforeach
      </ul>
    </section>
  @else
    <p style="color:#666;">No hay actividades registradas para esta unidad.</p>
  @endif

  @if(isset($estudiantes) && $estudiantes->isNotEmpty())
    <section style="margin-top:12px;">
      <h3>Estudiantes ({{ $estudiantes->count() }})</h3>
      <ul>
        @foreach($estudiantes as $estudiante)
          <li>{{ $estudiante->nombre ?? ('Estudiante ' . $estudiante->id) }}</li>
        @endforeach
      </ul>
    </section>
  @else
    <p style="color:#666;">No hay estudiantes registrados para esta unidad.</p>
  @endif
</div>
