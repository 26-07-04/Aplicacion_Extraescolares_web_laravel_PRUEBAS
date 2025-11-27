<!-- Sidebar -->
<div class="sidebar">
  <div class="sidebar-header">
    <h3>Actividades Extraescolares</h3>
  </div>
  <div class="sidebar-menu">
    <ul>
      <li>
        <a href="{{ url('U_ValleEtla') }}" class="active">
          <i class="fa fa-home"></i> Inicio
        </a>
      </li>
      <li>
        <a href="{{ route('coordinador.verestudiantes', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">
          <i class="fa fa-users"></i> Ver Estudiantes
        </a>
      </li>
      <li>
        <a href="{{ route('coordinador.constancia', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">
          <i class="fa fa-file-text"></i> Constancia de Cumplimiento 
        </a>
      </li>
      <li>
        <a href="{{ url('informe') }}">
          <i class="fa fa-file-pdf-o"></i> Informe de Actividad
        </a>
      </li>
      <li>
        <a href="{{ url('resultados') }}">
          <i class="fa fa-bar-chart"></i> Resultados
        </a>
      </li>
      <li>
        <a href="{{ url('documentos') }}" class="false">
          <i class="fa fa-folder-open"></i> Gestión de Documentos
        </a>
      </li>
    </ul>
  </div>
</div>