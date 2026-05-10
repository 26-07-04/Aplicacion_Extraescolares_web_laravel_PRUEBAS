<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/Coordinador/informe_actividad.css') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Informe de Actividades - ITVE</title>
<style>
.boton-actividad {
  padding: 8px 16px;
  border: 1px solid #ccc;
  background: #fff;
  cursor: pointer;
  border-radius: 4px;
  font-size: 14px;
  transition: background 0.2s, color 0.2s;
}
.boton-actividad.seleccionado {
  background: #007bff;
  color: #fff;
  border-color: #007bff;
}
.informe-lugar-fecha-grupo {
  max-width: 40rem;
  margin-left: 0;
  margin-right: auto;
}
</style>
</head>

<body>

<!-- Título principal simplificado -->
<div class="titulo-principal">
  <h1>Informe de Actividades</h1>
  <p>Sistema de generación de informes</p>
</div>

<!-- Indicador de progreso -->
<div class="progreso-contenedor">
  <div class="barra-progreso">
    <div class="progreso" id="barraProgreso"></div>
  </div>
  
  <div class="etapas">
    <div class="etapa activa" id="etapa1">
      <div class="etapa-numero">1</div>
      <div class="etapa-texto">Documento</div>
    </div>
    
    <div class="etapa" id="etapa2">
      <div class="etapa-numero">2</div>
      <div class="etapa-texto">Información</div>
    </div>
    
    <div class="etapa" id="etapa3">
      <div class="etapa-numero">3</div>
      <div class="etapa-texto">Eventos</div>
    </div>
    
    <div class="etapa" id="etapa4">
      <div class="etapa-numero">4</div>
      <div class="etapa-texto">Resumen</div>
    </div>
  </div>
</div>

<!-- Contenedor de secciones -->
<div class="seccion-contenedor">
  
  <!-- Sección 1: Configuración del documento -->
  <div class="seccion activa" id="seccion1">
    <h2 class="seccion-titulo">Documento Base Membretado</h2>
    <input type="hidden"
      id="id_semestre"
      value="{{ $id_semestre ?? ($semestreActual->id_semestre ?? '') }}">
    <input type="hidden" id="id_unidad" value="{{ $id_unidad ?? 3 }}">
    <div class="documentos-container" id="documentosLista">
      @if(isset($documentos) && $documentos->isEmpty())
        <div class="empty-state" id="emptyState">
          <i class="fas fa-folder-open" style="font-size:40px;color:#bdc3c7;margin-bottom:8px;"></i>
          <h3>No hay PDFs membretados disponibles</h3>
          <p>Contacte al administrador para cargar documentos membretados.</p>
        </div>
      @else
        <div class="tarjetas-documentos">
        @foreach($documentos as $doc)
          <div class="documento-card tarjeta-documento" data-id="{{ $doc->id }}" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 16px; min-width: 260px; max-width: 300px; display: flex; flex-direction: column; align-items: flex-start; border: 1px solid #e1e5eb;">
            <div class="documento-main" style="display: flex; align-items: center; width: 100%;">
              <div class="documento-icon" style="margin-right: 12px; color: #e74c3c; font-size: 32px;"><i class="fas fa-file-pdf"></i></div>
              <div style="flex:1;">
                <div class="documento-title" style="font-weight: 600; color: #1a365d; font-size: 1rem;">{{ $doc->nombre }}</div>
                <div class="documento-desc" style="color: #6c757d; font-size: 0.95rem; margin-bottom: 4px;">{{ $doc->descripcion }}</div>
                <div class="documento-info" style="font-size: 0.85rem; color: #888;">
                  <span>Subido: {{ $doc->created_at->format('d/m/Y') }}</span>
                </div>
              </div>
            </div>
            <div class="documento-actions" style="margin-top: 10px; display: flex; gap: 8px;">
              @if($doc->archivo)
                <a href="{{ asset($doc->archivo) }}" target="_blank" title="Ver PDF" style="background: #1a365d; border-radius: 5px; padding: 6px 10px; color: #fff; font-size: 1rem; border: none;"><i class="fas fa-eye"></i></a>
                <button class="btn-cargar-pdf" data-id="{{ $doc->id }}" title="Usar este PDF" style="background: #2ecc71; color: #fff; border: none; border-radius: 5px; padding: 6px 12px; font-weight: 600; cursor: pointer;"><i class="fas fa-check"></i> Usar PDF</button>
              @else
                <button disabled style="background: #eee; border-radius: 5px; padding: 6px 10px; color: #aaa;"><i class="fas fa-eye"></i></button>
              @endif
            </div>
          </div>
        @endforeach
        </div>
        <style>
        .tarjetas-documentos {
          display: flex;
          flex-wrap: nowrap;
          gap: 16px;
          overflow-x: auto;
          padding-bottom: 8px;
        }
        .tarjeta-documento {
          flex: 0 0 auto;
        }
        </style>
      @endif
    </div>
    <div id="pdfSeleccionadoInfo" style="margin-top:10px;"></div>
    <div class="controles-navegacion">
      <button class="boton boton-atras" disabled>
        <i>←</i> Atrás
      </button>
      <button class="boton boton-continuar" onclick="siguienteSeccion()">
        Continuar <i>→</i>
      </button>
    </div>
  </div>

  <!-- Contenedor principal de informes generados SOLO visible en el primer paso -->
  <div id="informesGeneradosContenedor" class="contenedor-informes-generados" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); padding: 32px; margin: 40px 0; border-left: 6px solid #1a365d; display: block;">
    <script>
    // Mostrar/ocultar informes generados según el paso
    function toggleInformesGenerados() {
      var seccion1 = document.getElementById('seccion1');
      var informesCont = document.getElementById('informesGeneradosContenedor');
      if (seccion1 && informesCont) {
        if (seccion1.classList.contains('activa')) {
          informesCont.style.display = 'block';
        } else {
          informesCont.style.display = 'none';
        }
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      toggleInformesGenerados();
      // Llama también al cambiar de sección
      var navBtns = document.querySelectorAll('.boton-continuar, .boton-atras');
      navBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
          setTimeout(toggleInformesGenerados, 300);
        });
      });
    });
    </script>
    <h3 style="color:#1a365d; font-size:1.2rem; font-weight:600; margin-bottom:18px;">Informes Generados</h3>
    @if($informes->count())
      <div class="tarjetas-documentos">
        @foreach($informes as $inf)
          <div class="documento-card tarjeta-documento" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 16px; min-width: 260px; max-width: 300px; display: flex; flex-direction: column; align-items: flex-start; border: 1px solid #e1e5eb;">
            <div class="documento-main" style="display: flex; align-items: center; width: 100%;">
              <div class="documento-icon" style="margin-right: 12px; color: #1a365d; font-size: 32px;"><i class="fas fa-file-alt"></i></div>
              <div style="flex:1;">
                <div class="documento-title" style="font-weight: 600; color: #1a365d; font-size: 1rem;">{{ $inf->titulo }}</div>
                <div class="documento-desc" style="color: #6c757d; font-size: 0.95rem; margin-bottom: 4px;">{{ $inf->descripcion }}</div>
                <div class="documento-info" style="font-size: 0.85rem; color: #888;">Generado el: {{ \Carbon\Carbon::parse($inf->fecha_generacion)->format('d/m/Y') }}</div>
              </div>
            </div>
            <div class="documento-actions" style="margin-top: 10px; display: flex; gap: 8px;">
              <a href="{{ asset($inf->archivo) }}" target="_blank" class="btn-ver-informe" style="background: #1a365d; border-radius: 5px; padding: 6px 10px; color: #fff; font-size: 1rem; border: none; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; height: 34px; min-width: 34px;">
                <i class="fas fa-eye"></i>
              </a>
              <form method="POST" action="{{ route('coordinador.tlahuitoltepec.informe.eliminar', $inf->id) }}" onsubmit="return confirmarEliminacionInforme(event)">
                @csrf
                @method('DELETE')
                <button class="btn-eliminar-informe" style="background: #e53e3e; border-radius: 5px; padding: 6px 10px; color: #fff; font-size: 1rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; height: 34px; min-width: 34px; transition: background 0.2s;">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
      <style>
        .tarjetas-documentos {
          display: flex;
          flex-wrap: nowrap;
          gap: 16px;
          overflow-x: auto;
          padding-bottom: 8px;
        }
        .tarjeta-documento {
          flex: 0 0 auto;
        }
        .btn-ver-informe:hover {
          background: #16305a;
        }
        .btn-eliminar-informe:hover {
          background: #c53030;
        }
      </style>
    @else
      <div style="color:#888;">No hay informes generados para esta unidad y semestre.</div>
    @endif

    <script>
    function confirmarEliminacionInforme(e) {
      if (!confirm('¿Estás seguro de que deseas eliminar este informe? Esta acción no se puede deshacer.')) {
        e.preventDefault();
        return false;
      }
      return true;
    }
    </script>
  </div>
  
  <!-- Sección 2: Información del informe -->
  <div class="seccion" id="seccion2">
    <div class="seccion-titulo">Información del Informe</div>
    
    <div class="grupo-formulario">
      <label for="periodo" class="requerido">Periodo Semestral:</label>
      <input type="text" id="periodo" class="input-estilo" value="{{ $semestreActual->nombre ?? '' }}" placeholder="Ej: Enero - Junio 2025">
      <div class="mensaje-error" id="errorPeriodo">Este campo es requerido</div>
    </div>
    
    <div class="grupo-formulario">
      <label>Actividades:</label>
      <div style="display: flex; gap: 16px; align-items: center;">
        <button type="button" id="btnCultural" class="boton-actividad">Cultural</button>
        <button type="button" id="btnDeportiva" class="boton-actividad">Deportiva</button>
      </div>
      <input type="hidden" id="actividadCultural" value="">
      <input type="hidden" id="actividadDeportiva" value="">
      <div class="nota">Seleccione el tipo de actividad</div>
    </div>
    
    <div class="controles-navegacion">
      <button class="boton boton-atras" onclick="anteriorSeccion()">
        <i>←</i> Atrás
      </button>
      
      <button class="boton boton-continuar" onclick="siguienteSeccion()">
        Continuar <i>→</i>
      </button>
    </div>
  </div>
  
  <!-- Sección 3: Registro de eventos -->
  <div class="seccion" id="seccion3">
    <div class="seccion-titulo">Registro de Eventos</div>
    
    <button class="boton boton-agregar" onclick="agregarEvento()">
      <i>+</i> Agregar evento
    </button>
    
    <div class="contenedor-tabla">
      <table class="tabla-eventos" id="tablaEventos">
        <thead>
          <tr>
            <th>NO.</th>
            <th>NOMBRE DEL EVENTO</th>
            <th>INSTITUCIÓN ORGANIZADORA</th>
            <th>FECHA DE REALIZACIÓN</th>
            <th>NO. DE PARTICIPANTES</th>
            <th>M</th>
            <th>H</th>
            <th>RESULTADOS</th>
          </tr>
        </thead>
        <tbody id="cuerpoTabla"></tbody>
      </table>
    </div>
    
    <div class="controles-navegacion">
      <button class="boton boton-atras" onclick="anteriorSeccion()">
        <i>←</i> Atrás
      </button>
      
      <button class="boton boton-continuar" onclick="siguienteSeccion()">
        Continuar <i>→</i>
      </button>
    </div>
  </div>
  
  <!-- Sección 4: Resumen y validación -->
  <div class="seccion" id="seccion4">
    <div class="seccion-titulo">Resumen y Validación</div>
    
    <div class="resumen-contenedor">
      <div class="resumen-item">
        <span class="resumen-etiqueta">Periodo:</span>
        <span class="resumen-valor" id="resumenPeriodo">No especificado</span>
      </div>
      
      <div class="resumen-item">
        <span class="resumen-etiqueta">Actividad Cultural:</span>
        <span class="resumen-valor" id="resumenCultural">No especificada</span>
      </div>
      
      <div class="resumen-item">
        <span class="resumen-etiqueta">Actividad Deportiva:</span>
        <span class="resumen-valor" id="resumenDeportiva">No especificada</span>
      </div>
      
      <div class="resumen-item">
        <span class="resumen-etiqueta">Total de Eventos:</span>
        <span class="resumen-valor" id="resumenEventos">0</span>
      </div>
      
      <div class="resumen-item">
        <span class="resumen-etiqueta">Lugar y Fecha:</span>
        <span class="resumen-valor" id="resumenFecha">No especificada</span>
      </div>
    </div>

    <!-- Firmas del informe (estilo similar a constancias; tres columnas) -->
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 24px 0 20px 0; border-left: 4px solid #17a2b8;">
      <h3 style="margin: 0 0 18px 0; color: #17a2b8; font-size: 1.15em; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-signature"></i>
        Firmas del informe
      </h3>
      <div class="informe-firmas-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        <div style="background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #dee2e6; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
          <div style="min-height: 76px; margin-bottom: 12px; border-bottom: 1px solid #333;" aria-hidden="true" title="Espacio para firma"></div>
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.92em;">
            <i class="fas fa-user" style="color: #17a2b8;"></i> Nombre completo: <span style="color: #dc3545;">*</span>
          </label>
          <input type="text" id="firmaNombrePromotor" class="input-estilo informe-firma-nombre" value="" autocomplete="name" placeholder="Nombre completo" style="width: 100%; box-sizing: border-box;">
          <p id="firmaCargoPromotor" class="informe-firma-cargo" data-default="Promotor Cultural o Deportivo" contenteditable="false" title="Doble clic para editar el cargo">Promotor Cultural o Deportivo</p>
        </div>
        <div style="background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #dee2e6; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
          <div style="min-height: 76px; margin-bottom: 12px; border-bottom: 1px solid #333;" aria-hidden="true" title="Espacio para firma"></div>
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.92em;">
            <i class="fas fa-user-tie" style="color: #17a2b8;"></i> Nombre completo: <span style="color: #dc3545;">*</span>
          </label>
          <input type="text" id="firmaNombreJefeOficina" class="input-estilo informe-firma-nombre" value="" autocomplete="name" placeholder="Nombre completo" style="width: 100%; box-sizing: border-box;">
          <p id="firmaCargoJefeOficina" class="informe-firma-cargo" data-default="Jefe de Oficina de Promoción Cultural o Deportivo" contenteditable="false" title="Doble clic para editar el cargo">Jefe de Oficina de Promoción Cultural o Deportivo</p>
        </div>
        <div style="background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #dee2e6; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
          <div style="min-height: 76px; margin-bottom: 12px; border-bottom: 1px solid #333;" aria-hidden="true" title="Espacio para firma"></div>
          <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: 0.92em;">
            <i class="fas fa-user-shield" style="color: #17a2b8;"></i> Nombre completo: <span style="color: #dc3545;">*</span>
          </label>
          <input type="text" id="firmaNombreJefeDepartamento" class="input-estilo informe-firma-nombre" data-default="M.C. Alejandro Loma Bolaños" value="M.C. Alejandro Loma Bolaños" autocomplete="name" style="width: 100%; box-sizing: border-box;">
          <p id="firmaCargoJefeDepartamento" class="informe-firma-cargo" data-default="Jefe de Departamento Actividades Extraescolares" contenteditable="false" title="Doble clic para editar el cargo">Jefe de Departamento Actividades Extraescolares</p>
        </div>
      </div>
    </div>
    <style>
      @media (max-width: 960px) {
        .informe-firmas-grid { grid-template-columns: 1fr !important; }
      }
      .informe-firma-nombre { font-size: 0.82rem !important; font-weight: 700; }
      .informe-firma-cargo {
        font-size: 0.82rem;
        color: #495057;
        line-height: 1.35;
        text-align: center;
        margin-top: 8px;
        min-height: 2.4em;
        cursor: default;
        user-select: none;
      }
      .informe-firma-cargo[contenteditable="true"] { user-select: text; cursor: text; background: #fffef5; }
    </style>

    <!-- Apartado para Título del informe -->
    <div class="grupo-formulario">
      <label for="tituloInforme" class="requerido">Título del informe (nombre del PDF):</label>
      <input type="text" id="tituloInforme" class="input-estilo" placeholder="Se rellena con el periodo; puede editarlo">
      <div class="nota">Este será el nombre del PDF y el título guardado en la base de datos.</div>
    </div>

    <!-- Apartado para Descripción del informe -->
    <div class="grupo-formulario">
      <label for="descripcionInforme" class="requerido">Descripción del informe:</label>
      <textarea id="descripcionInforme" class="input-estilo" rows="2" placeholder="Descripción detallada del informe"></textarea>
      <div class="nota">Este será el campo de descripción guardado en la base de datos.</div>
    </div>

    <div class="grupo-formulario informe-lugar-fecha-grupo">
      <label for="lugarFecha" class="requerido">Lugar y Fecha:</label>
      <input type="text" id="lugarFecha" class="input-estilo" placeholder="Ej: Santa María Tlahuitoltepec, a los 27 días del mes de mayo de 2025">
      <div class="nota fecha-actual" id="notaFecha">Se rellena con el lugar de la unidad y la fecha de hoy; puede editarlo.</div>
    </div>
    
    <div class="loading" id="loadingGeneracion">
      <div class="spinner"></div>
      <p>Generando PDF, por favor espere...</p>
    </div>
    
    <div class="controles-navegacion">
      <button class="boton boton-atras" onclick="anteriorSeccion()">
        <i>←</i> Atrás
      </button>
      
      <button class="boton boton-finalizar" onclick="generarPDF()">
        <i>📄</i> Generar PDF
      </button>
    </div>
  </div>
</div>

<!-- Elementos solo para PDF (siempre ocultos) -->
<div class="paginacion-pdf solo-pdf" style="display: none !important;">
  Página 1 de 1
</div>

<!-- JS LIBRARIES -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

@php
  $actividadesReporte = \App\Models\Actividad::where('id_semestre', $id_semestre ?? ($semestreActual->id_semestre ?? null))
      ->where('id_unidad', $id_unidad ?? 3)
      ->get();

  $actividadIds = $actividadesReporte->pluck('id_actividad')->toArray();
  $cuentasActividades = \App\Models\Estudiante::selectRaw(
          'id_actividad, count(*) as total, '
          . 'sum(case when lower(sexo) in (?, ?, ?) then 1 else 0 end) as hombres, '
          . 'sum(case when lower(sexo) in (?, ?, ?, ?) then 1 else 0 end) as mujeres',
          ['h', 'masculino', 'hombre', 'f', 'femenino', 'mujer', 'm']
      )
      ->whereIn('id_actividad', $actividadIds)
      ->groupBy('id_actividad')
      ->get()
      ->keyBy('id_actividad');

  $actividadesPorCategoria = [
      'cultural' => [],
      'deportiva' => [],
  ];

  foreach ($actividadesReporte as $actividad) {
      $categoria = \App\Models\Actividad::resolverCategoriaInforme($actividad->categorias ?? null);

      if ($categoria) {
          $cuenta = $cuentasActividades[$actividad->id_actividad] ?? null;
          $actividadesPorCategoria[$categoria][] = [
              'nombre' => $actividad->nombre_actividad,
              'participantes' => $cuenta->total ?? 0,
              'hombres' => $cuenta->hombres ?? 0,
              'mujeres' => $cuenta->mujeres ?? 0,
          ];
      }
  }
@endphp
<script>
// -------------------------------------------
// VARIABLES GLOBALES
// -------------------------------------------
let seccionActual = 1;
const totalSecciones = 4;

// -------------------------------------------
// INICIALIZACIÓN
// -------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
  // Agregar un evento inicial
  agregarEvento();
  // Configurar fecha automática
  const lugarFechaInput = document.getElementById('lugarFecha');
  if (lugarFechaInput && !lugarFechaInput.value) {
    lugarFechaInput.value = armarTextoLugarFechaActividad(lugarLiteralInformePorUnidad(), new Date());
  }
  // Actualizar resumen
  actualizarResumen();
  // Inicializar selección de PDF membretado
  inicializarSeleccionPDF();
  // Inicializar selección de actividad
  inicializarSeleccionActividad();
  inicializarTituloInformeAuto();
  inicializarFirmasEncargados();
});

const eventosPorCategoria = @json($actividadesPorCategoria ?? ['cultural' => [], 'deportiva' => []]);

let categoriaSeleccionada = null;

function lugarLiteralInformePorUnidad() {
  const el = document.getElementById('id_unidad');
  const idU = el ? parseInt(el.value, 10) : 0;
  const map = {
    1: 'Unión Hidalgo',
    2: 'Demetrio Vallejo',
    3: 'Santa María Tlahuitoltepec',
    4: 'Santiago Suchilquitongo'
  };
  return map[idU] || 'Oaxaca de Juárez';
}

function armarTextoLugarFechaActividad(lugar, fecha) {
  const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
  const d = fecha.getDate();
  const m = meses[fecha.getMonth()];
  const y = fecha.getFullYear();
  const cuerpoFecha = d === 1
    ? 'al primer día del mes de ' + m + ' de ' + y
    : 'a los ' + d + ' días del mes de ' + m + ' de ' + y;
  return lugar + ', ' + cuerpoFecha;
}

/**
 * id_unidad (BD): 1 Unión Hidalgo, 2 Demetrio Vallejo Martínez, 3 Santa María Tlahuitoltepec, 4 Valle de Etla.
 * Ids donde promotor y jefe de oficina ya vienen definidos en la vista (no exigir captura al generar PDF).
 */
const IDS_UNIDAD_SIN_VALIDACION_PROMOTOR_JEFE_FIRMA = [4];

function idUnidadInformeActual() {
  const el = document.getElementById('id_unidad');
  return el ? parseInt(el.value, 10) : 0;
}

function firmasPromotorJefeOmitenValidacionRequerida() {
  return IDS_UNIDAD_SIN_VALIDACION_PROMOTOR_JEFE_FIRMA.indexOf(idUnidadInformeActual()) !== -1;
}

function firmasNombresCoordinadorRequeridosOk() {
  if (firmasPromotorJefeOmitenValidacionRequerida()) return true;
  const p = document.getElementById('firmaNombrePromotor');
  const j = document.getElementById('firmaNombreJefeOficina');
  return !!(p && j && p.value.trim() !== '' && j.value.trim() !== '');
}

function textoCargoFirmaPdf(cargoId, cargoDefault) {
  const el = document.getElementById(cargoId);
  if (!el) return cargoDefault;
  const t = String(el.innerText || el.textContent || '').trim();
  return t || cargoDefault;
}

function inicializarFirmasEncargados() {
  ['firmaCargoPromotor', 'firmaCargoJefeOficina', 'firmaCargoJefeDepartamento'].forEach(function (id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('dblclick', function (e) {
      e.preventDefault();
      this.contentEditable = 'true';
      this.style.outline = '1px dashed #17a2b8';
      this.focus();
      try {
        const range = document.createRange();
        range.selectNodeContents(this);
        range.collapse(false);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
      } catch (ignore) {}
    });
    el.addEventListener('blur', function () {
      this.contentEditable = 'false';
      this.style.outline = '';
      const d = this.getAttribute('data-default') || '';
      if (!(this.textContent || '').trim()) this.textContent = d;
    });
    el.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        this.blur();
      }
    });
  });
}

function inicializarTituloInformeAuto() {
  const titulo = document.getElementById('tituloInforme');
  const periodo = document.getElementById('periodo');
  if (!titulo || !periodo) return;
  const semNombreDefault = @json($semestreActual->nombre ?? '');
  function autoTitulo() {
    const p = periodo.value.trim() || semNombreDefault;
    return 'Informe de Actividades (' + p + ')';
  }
  function sync() {
    if (titulo.dataset.userEdited === '1') return;
    titulo.value = autoTitulo();
  }
  titulo.addEventListener('input', function () {
    titulo.dataset.userEdited = '1';
  });
  periodo.addEventListener('input', sync);
  sync();
}

// -------------------------------------------
// SELECCIÓN DE ACTIVIDAD
// -------------------------------------------
function inicializarSeleccionActividad() {
  const btnCultural = document.getElementById('btnCultural');
  const btnDeportiva = document.getElementById('btnDeportiva');
  const actividadCultural = document.getElementById('actividadCultural');
  const actividadDeportiva = document.getElementById('actividadDeportiva');

  function seleccionarCultural() {
    actividadCultural.value = 'X';
    actividadDeportiva.value = '';
    categoriaSeleccionada = 'cultural';
    btnCultural.classList.add('seleccionado');
    btnDeportiva.classList.remove('seleccionado');
    llenarEventosPorCategoria();
    actualizarResumen();
  }

  function seleccionarDeportiva() {
    actividadCultural.value = '';
    actividadDeportiva.value = 'X';
    categoriaSeleccionada = 'deportiva';
    btnCultural.classList.remove('seleccionado');
    btnDeportiva.classList.add('seleccionado');
    llenarEventosPorCategoria();
    actualizarResumen();
  }

  btnCultural.addEventListener('click', seleccionarCultural);
  btnDeportiva.addEventListener('click', seleccionarDeportiva);
}

function llenarEventosPorCategoria() {
  const cuerpo = document.getElementById('cuerpoTabla');
  cuerpo.innerHTML = '';
  const actividades = eventosPorCategoria[categoriaSeleccionada] || [];
  const institucion = 'ITVE';
  const periodoTexto = document.getElementById('periodo')?.value.trim() || "{{ $semestreActual->nombre ?? '' }}";

  if (actividades.length === 0) {
    const fila = document.createElement('tr');
    fila.innerHTML = `
      <td><input type="text" value="1" disabled class="tabla-input"></td>
      <td><input type="text" class="tabla-input" placeholder="No se encontraron actividades" readonly></td>
      <td><input type="text" class="tabla-input" value="${institucion}" readonly></td>
      <td><input type="text" class="tabla-input" value="${periodoTexto}" readonly></td>
      <td><input type="number" class="tabla-input" value="0" readonly></td>
      <td><input type="number" class="tabla-input input-small" value="0" readonly></td>
      <td><input type="number" class="tabla-input input-small" value="0" readonly></td>
      <td><input type="text" class="tabla-input" value="Actividad concluida satisfactoriamente" readonly></td>
    `;
    cuerpo.appendChild(fila);
    return;
  }

  actividades.forEach((actividad, index) => {
    const categoriaLabel = categoriaSeleccionada.charAt(0).toUpperCase() + categoriaSeleccionada.slice(1);
    const valorEvento = `${categoriaLabel} (${actividad.nombre})`;
    const fila = document.createElement('tr');
    fila.innerHTML = `
      <td><input type="text" value="${index + 1}" disabled class="tabla-input"></td>
      <td><input type="text" class="tabla-input" value="${valorEvento}" readonly></td>
      <td><input type="text" class="tabla-input" value="${institucion}" readonly></td>
      <td><input type="text" class="tabla-input" value="${periodoTexto}" readonly></td>
      <td><input type="number" class="tabla-input" value="${actividad.participantes}" readonly></td>
      <td><input type="number" class="tabla-input input-small" value="${actividad.mujeres}" readonly></td>
      <td><input type="number" class="tabla-input input-small" value="${actividad.hombres}" readonly></td>
      <td><input type="text" class="tabla-input" value="Actividad concluida satisfactoriamente" readonly></td>
    `;
    cuerpo.appendChild(fila);
  });
}

function inicializarSeleccionPDF() {
  window.pdfSeleccionado = null;
  const cargarBtns = document.querySelectorAll('.btn-cargar-pdf');
  const infoDiv = document.getElementById('pdfSeleccionadoInfo');
  cargarBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      const id = btn.getAttribute('data-id');
      const card = btn.closest('.documento-card');
      const nombre = card.querySelector('.documento-title').innerText;
      const archivo = card.querySelector("a[target='_blank']").href;
      window.pdfSeleccionado = { id, nombre, archivo };
      infoDiv.innerHTML = `
        <div style="color:#2ecc71; font-weight:600;">
          PDF seleccionado:
          <a href="${archivo}" target="_blank">${nombre}</a>
        </div>
      `;
      cargarBtns.forEach(b => {
        b.style.background = '#2ecc71';
        b.style.color = '#fff';
      });
      btn.style.background = '#ff9800';
      btn.style.color = '#fff';
    });
  });
  // Función pública para obtener el PDF seleccionado
  window.getPDFSeleccionado = function () {
    return window.pdfSeleccionado;
  };
}

// -------------------------------------------
// NAVEGACIÓN ENTRE SECCIONES
// -------------------------------------------
function siguienteSeccion() {
  // Validar sección actual antes de continuar
  if (!validarSeccionActual()) {
    return;
  }
  
  // Marcar etapa como completada
  document.getElementById(`etapa${seccionActual}`).classList.add('completada');
  document.getElementById(`etapa${seccionActual}`).classList.remove('activa');
  
  // Ocultar sección actual
  document.getElementById(`seccion${seccionActual}`).classList.remove('activa');
  
  // Avanzar a siguiente sección
  seccionActual++;
  
  // Mostrar siguiente sección
  document.getElementById(`seccion${seccionActual}`).classList.add('activa');
  document.getElementById(`etapa${seccionActual}`).classList.add('activa');
  
  // Actualizar barra de progreso
  actualizarBarraProgreso();
  
  // Actualizar resumen si estamos en la última sección
  if (seccionActual === totalSecciones) {
    actualizarResumen();
  }
  
  // Desplazar hacia arriba
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function anteriorSeccion() {
  // Marcar etapa actual como no activa
  document.getElementById(`etapa${seccionActual}`).classList.remove('activa');
  
  // Ocultar sección actual
  document.getElementById(`seccion${seccionActual}`).classList.remove('activa');
  
  // Retroceder a sección anterior
  seccionActual--;
  
  // Mostrar sección anterior
  document.getElementById(`seccion${seccionActual}`).classList.add('activa');
  document.getElementById(`etapa${seccionActual}`).classList.add('activa');
  document.getElementById(`etapa${seccionActual}`).classList.remove('completada');
  
  // Actualizar barra de progreso
  actualizarBarraProgreso();
  
  // Desplazar hacia arriba
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// -------------------------------------------
// ACTUALIZAR INTERFAZ
// -------------------------------------------
function actualizarBarraProgreso() {
  const porcentaje = ((seccionActual - 1) / (totalSecciones - 1)) * 100;
  document.getElementById('barraProgreso').style.width = `${porcentaje}%`;
}

function actualizarResumen() {
  // Actualizar valores del resumen
  document.getElementById('resumenPeriodo').textContent = 
    document.getElementById('periodo').value || 'No especificado';
  
  document.getElementById('resumenCultural').textContent = 
    document.getElementById('actividadCultural').value === 'X' ? 'Seleccionada' : 'No seleccionada';
  
  document.getElementById('resumenDeportiva').textContent = 
    document.getElementById('actividadDeportiva').value === 'X' ? 'Seleccionada' : 'No seleccionada';
  
  document.getElementById('resumenEventos').textContent = 
    document.querySelectorAll('#cuerpoTabla tr').length;
  
  document.getElementById('resumenFecha').textContent = 
    document.getElementById('lugarFecha').value || 'No especificada';
}

// -------------------------------------------
// VALIDACIONES
// -------------------------------------------
function configurarValidaciones() {
  // Validación para periodo
  const periodoInput = document.getElementById('periodo');
  periodoInput.addEventListener('input', function() {
    if (this.value.trim()) {
      this.classList.remove('invalido');
      this.classList.add('valido');
      document.getElementById('errorPeriodo').style.display = 'none';
    } else {
      this.classList.remove('valido');
      this.classList.add('invalido');
      document.getElementById('errorPeriodo').style.display = 'block';
    }
  });
}

function validarSeccionActual() {
  switch(seccionActual) {
    case 1: // Configuración del documento
      // Validar que se haya seleccionado un PDF membretado
      if (!window.pdfSeleccionado || !window.pdfSeleccionado.archivo) {
        alert('Debe seleccionar un PDF membretado usando el botón "Usar PDF".');
        return false;
      }
      return true;
      
    case 2: // Información del informe
      const periodoInput = document.getElementById('periodo');
      if (!periodoInput.value.trim()) {
        periodoInput.classList.add('invalido');
        document.getElementById('errorPeriodo').style.display = 'block';
        periodoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
      }
      const actividadCultural = document.getElementById('actividadCultural').value;
      const actividadDeportiva = document.getElementById('actividadDeportiva').value;
      if (actividadCultural !== 'X' && actividadDeportiva !== 'X') {
        alert('Debe seleccionar al menos una actividad (Cultural o Deportiva).');
        return false;
      }
      return true;
      
    case 3: // Registro de eventos
      const filas = document.querySelectorAll('#cuerpoTabla tr');
      if (filas.length === 0) {
        alert('Debe agregar al menos un evento antes de continuar.');
        return false;
      }
      return true;
      
    default:
      return true;
  }
}

// -------------------------------------------
// AGREGAR FILA
// -------------------------------------------
function agregarEvento() {
  const cuerpo = document.getElementById("cuerpoTabla");
  const nuevoNumero = cuerpo.rows.length + 1;

  const fila = document.createElement("tr");
  fila.innerHTML = `
    <td><input type="text" value="${nuevoNumero}" disabled class="tabla-input"></td>
    <td><input type="text" class="tabla-input" placeholder="Nombre del evento"></td>
    <td><input type="text" class="tabla-input" placeholder="Institución organizadora"></td>
    <td><input type="date" class="tabla-input"></td>
    <td><input type="number" class="tabla-input" placeholder="0" min="0"></td>
    <td><input type="number" class="tabla-input input-small" placeholder="M" min="0"></td>
    <td><input type="number" class="tabla-input input-small" placeholder="H" min="0"></td>
    <td><input type="text" class="tabla-input" placeholder="Resultados"></td>
  `;

  cuerpo.appendChild(fila);
  
  // Añadir efecto visual
  fila.style.opacity = "0";
  setTimeout(() => {
    fila.style.transition = "opacity 0.3s";
    fila.style.opacity = "1";
  }, 10);
  
  // Agregar animación de pulso
  fila.classList.add('pulse');
  setTimeout(() => {
    fila.classList.remove('pulse');
  }, 500);
  
  // Actualizar resumen si estamos en la última sección
  if (seccionActual === totalSecciones) {
    actualizarResumen();
  }
}

// -------------------------------------------
// CONVERTIR PRIMERA PÁGINA DEL PDF A PNG
// -------------------------------------------
async function convertirPDFaPNG(file) {
  const pdfData = await file.arrayBuffer();
  const pdf = await pdfjsLib.getDocument({ data: pdfData }).promise;
  const page = await pdf.getPage(1);

  const viewport = page.getViewport({ scale: 2 });
  const canvas = document.createElement("canvas");
  const context = canvas.getContext("2d");

  canvas.width = viewport.width;
  canvas.height = viewport.height;

  await page.render({
    canvasContext: context,
    viewport: viewport
  }).promise;

  return canvas.toDataURL("image/png");
}

// -------------------------------------------
// GENERAR PDF FINAL
// -------------------------------------------
async function generarPDF() {
  // Mostrar loading
  document.getElementById('loadingGeneracion').style.display = 'block';

  // Validar todos los campos
  if (!validarSeccionActual()) {
    document.getElementById('loadingGeneracion').style.display = 'none';
    return;
  }
  if (!firmasNombresCoordinadorRequeridosOk()) {
    document.getElementById('loadingGeneracion').style.display = 'none';
    alert('Debe escribir el nombre completo del promotor cultural y del jefe de oficina en las firmas del informe.');
    return;
  }
  if (!validarSeccionesPrevias()) {
    document.getElementById('loadingGeneracion').style.display = 'none';
    return;
  }

  try {
    if (!window.pdfSeleccionado || !window.pdfSeleccionado.archivo) {
      alert("Selecciona primero el PDF membretado usando el botón 'Usar PDF'.");
      document.getElementById('loadingGeneracion').style.display = 'none';
      return;
    }
    const response = await fetch(window.pdfSeleccionado.archivo);
    if (!response.ok) {
      alert('No se pudo descargar el PDF membretado.');
      document.getElementById('loadingGeneracion').style.display = 'none';
      return;
    }
    const pdfBlob = await response.blob();
    const imgMembrete = await convertirPDFaPNG(pdfBlob);

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit: "pt", format: "letter" });

    let trs = [...document.querySelectorAll("#cuerpoTabla tr")];

    if (trs.length === 0) {
      alert("No hay eventos. Agregue al menos un evento antes de generar el PDF.");
      document.getElementById('loadingGeneracion').style.display = 'none';
      return;
    }

    // Convertir a texto tabla PDF
    const filas = trs.map((tr, idx) => {
      const nombreEventoElement = tr.cells[1].querySelector('input, select');
      return [
        String(idx + 1),
        nombreEventoElement ? nombreEventoElement.value || "" : "",
        tr.cells[2].querySelector('input')?.value || "",
        tr.cells[3].querySelector('input')?.value || "",
        tr.cells[4].querySelector('input')?.value || "",
        tr.cells[5].querySelector('input')?.value || "",
        tr.cells[6].querySelector('input')?.value || "",
        tr.cells[7].querySelector('input')?.value || "",
      ];
    });

    const BLOQUE = 16;
    const bloques = [];
    for (let i = 0; i < filas.length; i += BLOQUE) {
      bloques.push(filas.slice(i, i + BLOQUE));
    }

    bloques.forEach((bloque, index) => {
      if (index > 0) doc.addPage();
      doc.addImage(
        imgMembrete,
        "PNG",
        0,
        0,
        doc.internal.pageSize.width,
        doc.internal.pageSize.height
      );
      let y = 140;
      doc.setFontSize(13);
      doc.setFont(undefined, "bold");
      doc.text("INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA", doc.internal.pageSize.width/2, y, {align: "center"}); y += 18;
      doc.setFontSize(11);
      doc.text("Subdirección de Planeación y Vinculación", doc.internal.pageSize.width/2, y, {align: "center"}); y += 15;
      doc.text("DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES", doc.internal.pageSize.width/2, y, {align: "center"}); y += 15;
      doc.text("OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA", doc.internal.pageSize.width/2, y, {align: "center"}); y += 25;
      doc.setFontSize(10);
      doc.setFont(undefined, "normal");
      const periodo = document.getElementById("periodo").value || "";
      doc.text(`Informe Semestral del Periodo: ${periodo}`, doc.internal.pageSize.width/2, y, {align: "center"}); y += 18;
      const actividadCultural = document.getElementById("actividadCultural").value || "";
      const actividadDeportiva = document.getElementById("actividadDeportiva").value || "";
      doc.text(`ACTIVIDAD CULTURAL: ${actividadCultural}    DEPORTIVA: ${actividadDeportiva}`, doc.internal.pageSize.width/2, y, {align: "center"}); y += 18;
      doc.autoTable({
        startY: y + 10,
        head: [[
          "NO.",
          "NOMBRE DEL EVENTO",
          "INSTITUCIÓN ORGANIZADORA",
          "FECHA DE REALIZACIÓN",
          "NO. DE PARTICIPANTES",
          "M",
          "H",
          "RESULTADOS"
        ]],
        body: bloque,
        theme: "grid",
        styles: {
          fontSize: 7,
          cellPadding: 6,
          lineWidth: .65,
          lineColor: [0,0,0],
          halign: 'center',
          valign: 'middle',
          textColor: [20, 20, 20],
          fontStyle: 'normal'
        },
        headStyles: {
          fillColor: [255, 255, 255],
          textColor: [0, 0, 0],
          lineWidth: 0.7,
          halign: 'center',
          valign: 'middle',
          fontStyle: 'bold'
        },
        columnStyles: {
          0: { cellWidth: 25 },
          2: { cellWidth: 90 },
          3: { cellWidth: 70 },
          4: { cellWidth: 90 }
        },
        didDrawPage: function (data) {
          if (index === bloques.length - 1) {
            const pageWidth = doc.internal.pageSize.width;
            let yFirmas = data.cursor.y + 28;
            const lugarFecha = document.getElementById('lugarFecha').value || '';
            const xFecha = 52;
            const margenDerFecha = 56;
            const maxWFecha = pageWidth - xFecha - margenDerFecha;
            doc.setFontSize(9);
            doc.setFont(undefined, "normal");
            const lineasFecha = doc.splitTextToSize(lugarFecha || ' ', maxWFecha);
            let yFecha = yFirmas;
            lineasFecha.forEach(function (ln) {
              doc.text(ln, xFecha, yFecha, { align: 'left' });
              yFecha += 12;
            });
            yFirmas = yFecha + 10;
            function valorFirmaInforme(id) {
              const el = document.getElementById(id);
              if (!el) return '';
              const v = String(el.value || '').trim();
              if (v) return v;
              return String(el.getAttribute('data-default') || '').trim();
            }
            const margin = 32;
            const gap = 10;
            const colW = (pageWidth - 2 * margin - 2 * gap) / 3;
            const bloquesFirma = [
              { id: 'firmaNombrePromotor', cargoId: 'firmaCargoPromotor', cargoDefault: 'Promotor Cultural o Deportivo' },
              { id: 'firmaNombreJefeOficina', cargoId: 'firmaCargoJefeOficina', cargoDefault: 'Jefe de Oficina de Promoción Cultural o Deportivo' },
              { id: 'firmaNombreJefeDepartamento', cargoId: 'firmaCargoJefeDepartamento', cargoDefault: 'Jefe de Departamento Actividades Extraescolares' }
            ];
            bloquesFirma.forEach(function (bf, colIdx) {
              const xCenter = margin + colW / 2 + colIdx * (colW + gap);
              let yCol = yFirmas;
              const espacioFirma = 38;
              yCol += espacioFirma;
              doc.setDrawColor(0, 0, 0);
              doc.setLineWidth(0.45);
              doc.line(xCenter - colW / 2 + 6, yCol, xCenter + colW / 2 - 6, yCol);
              yCol += 14;
              doc.setFontSize(8.5);
              doc.setFont(undefined, 'bold');
              const nombreTxt = valorFirmaInforme(bf.id) || ' ';
              const nameLines = doc.splitTextToSize(nombreTxt, colW - 8);
              nameLines.forEach(function (ln) {
                doc.text(ln, xCenter, yCol, { align: 'center' });
                yCol += 10.5;
              });
              doc.setFont(undefined, 'normal');
              doc.setFontSize(8.5);
              const cargoTxt = textoCargoFirmaPdf(bf.cargoId, bf.cargoDefault);
              const cargoLines = doc.splitTextToSize(cargoTxt, colW - 8);
              cargoLines.forEach(function (ln) {
                doc.text(ln, xCenter, yCol, { align: 'center' });
                yCol += 10.5;
              });
            });
          }
        }
      });
    });

    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i);
      const pageWidth = doc.internal.pageSize.width;
      const paginacionText = `Página ${i} de ${pageCount}`;
      doc.setFontSize(9);
      doc.setFont(undefined, "bold");
      doc.text(paginacionText, pageWidth - 203, 102);
    }

    // Obtener el id_semestre de forma limpia y segura
    const idSemestreInput = document.getElementById('id_semestre');
    if (!idSemestreInput || !idSemestreInput.value) {
      alert('Error: no se pudo determinar el semestre actual.');
      document.getElementById('loadingGeneracion').style.display = 'none';
      return;
    }

    // Solo usar los campos de la sección de resumen para guardar
    const tituloInforme = document.getElementById('tituloInforme').value.trim();
    const descripcionInforme = document.getElementById('descripcionInforme').value.trim();
    const formData = new FormData();
    formData.append('titulo', tituloInforme ? tituloInforme : 'Informe Semestral');
    formData.append('descripcion', descripcionInforme ? descripcionInforme : '-');
    formData.append('fecha_generacion', new Date().toISOString().slice(0, 10));
    formData.append('id_semestre', idSemestreInput.value);
    let nombrePDF = tituloInforme ? tituloInforme : 'informe_actividades_itve';
    nombrePDF = nombrePDF.replace(/[^a-zA-Z0-9_\- ]/g, '').replace(/\s+/g, '_') + '.pdf';
    const pdfBlobFinal = doc.output('blob');
    formData.append('pdf', pdfBlobFinal, nombrePDF);

    let csrf = document.querySelector('meta[name="csrf-token"]');
    let headers = {};
    // Siempre incluir el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');

    fetch('/coordinador/tlahuitoltepec/informe/guardar', {
      method: 'POST',
      headers,
      body: formData
    })
    .then(async res => {
      const contentType = res.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        const text = await res.text();
        throw new Error('Respuesta no JSON del servidor:\n' + text);
      }
      const data = await res.json();
      if (data.status === 'ok') {
        alert("Informe guardado correctamente");
      } else {
        alert("Error al guardar el informe");
      }
    })
    .catch(async (err) => {
      let msg = 'Error de red al guardar el informe.';
      if (err && err.message) msg += '\n' + err.message;
      document.getElementById('loadingGeneracion').style.display = 'none';
      alert(msg);
    });

    doc.save("informe_actividades_itve.pdf");

  } catch (error) {
    console.error("Error al generar PDF:", error);
    document.getElementById('loadingGeneracion').style.display = 'none';
    alert("Hubo un error al generar el PDF. Por favor, intente nuevamente.");
  }
}

function validarSeccionesPrevias() {
  // Validar todas las secciones
  const seccionesValidas = [
    window.pdfSeleccionado && window.pdfSeleccionado.archivo,
    document.getElementById('periodo').value.trim() !== '',
    (document.getElementById('actividadCultural').value === 'X' || document.getElementById('actividadDeportiva').value === 'X'),
    document.querySelectorAll('#cuerpoTabla tr').length > 0,
    document.getElementById('lugarFecha').value.trim() !== ''
  ];
  return seccionesValidas.every(valido => !!valido);
}
</script>
</body>
</html>