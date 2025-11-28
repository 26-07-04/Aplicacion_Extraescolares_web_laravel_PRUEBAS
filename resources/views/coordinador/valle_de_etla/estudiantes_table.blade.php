<!-- Partial: Tabla de estudiantes (estática, sin JS) -->
<div class="tabla-container" style="margin:20px 0;">
  <div class="tabla-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
    <h2 style="margin:0;">Estudiantes Registrados</h2>
    <div class="search-container" style="display:flex; align-items:center; gap:8px;">
      <input type="text" id="buscador-nombre" placeholder="Buscar por nombre..." class="buscador" style="padding:8px; border-radius:6px; border:1px solid #ddd;">
      <button id="btn-buscar-nombre" class="btn-buscar" title="Buscar" style="padding:8px 10px; border-radius:6px; background:#1B396A; color:#fff; border:none; cursor:pointer;">
        <i class="fas fa-search"></i>
      </button>
      <button id="btn-recargar" class="btn-recargar" title="Recargar tabla" style="padding:8px 10px; border-radius:6px; background:#ff7f00; color:#fff; border:none; cursor:pointer;">
        <i class="fas fa-sync-alt"></i>
      </button>
    </div>
  </div>

  <div style="overflow:auto; background:#fff; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06);">
    <table id="dataTable" class="display nowrap" style="width:100%; border-collapse:collapse; min-width:700px;">
      <thead style="background:#f6f8fb;">
        <tr>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">No. Control</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Nombre</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Carrera</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Extraescolar</th>
          <th style="text-align:left; padding:12px 16px; border-bottom:1px solid #eee;">Semestre</th>
          <th style="text-align:center; padding:12px 16px; border-bottom:1px solid #eee;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí puede colocarse un loop de Blade para mostrar datos si se necesita en el futuro -->
        <tr>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">20250001</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">María López</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">Ingeniería en Sistemas</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">Fútbol</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2;">Agosto-Diciembre 2026</td>
          <td style="padding:12px 16px; border-bottom:1px solid #f2f2f2; text-align:center;">
            <a href="#" style="margin-right:8px; color:#1B396A; text-decoration:none;"><i class="fas fa-eye"></i></a>
            <a href="#" style="color:#ff7f00; text-decoration:none;"><i class="fas fa-download"></i></a>
          </td>
        </tr>
        <!-- Datos de ejemplo; reemplazar con @@foreach cuando se integre con backend -->
      </tbody>
    </table>
  </div>
</div>
