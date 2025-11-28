<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actividades Extraescolares - Unión Hidalgo</title>

  <!-- CSS (mantén sólo los que uses para evitar duplicados) -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Administrador/actividades.css') }}">

  <!-- CSRF token requerido por fetch -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Configuración usada por public/js/actividades.js (NO modifica estilos) -->
  <script>
    (function(){
      // intenta obtener semestre/unidad desde variables server o query params
      const unidadServer = @json(isset($unidad) ? $unidad : null);
      const semestreServer = @json(isset($semestre) ? $semestre : null);

      function resolveUnidadId(u){
        if (!u) return null;
        if (typeof u === 'object' && u !== null && ('id_unidad' in u)) return Number(u.id_unidad) || null;
        if (!isNaN(Number(u))) return Number(u);
        return null;
      }
      function resolveSemestreId(s){
        if (!s) return null;
        if (typeof s === 'object' && s !== null && ('id_semestre' in s)) return Number(s.id_semestre) || null;
        if (!isNaN(Number(s))) return Number(s);
        return null;
      }

      // fallback: leer query string si no hubo variable server
      const params = new URLSearchParams(window.location.search);
      const qUnidad = params.get('id_unidad') ?? params.get('unidad') ?? null;
      const qSemestre = params.get('id_semestre') ?? params.get('semestre') ?? null;

      window.ActUH = {
        rutas: {
          list: "{{ route('administrador.actividades.index') }}",
          store: "{{ route('administrador.actividades.store') }}",
          showBase: "{{ url('administrador/actividades') }}",
          detail: "{{ url('administrador/vista_previa_U/D_actividades_UH') }}",
        },
        unidadId: resolveUnidadId(unidadServer ?? qUnidad) ?? 1,        // si no hay unidad usa 1 por defecto
        semestreId: resolveSemestreId(semestreServer ?? qSemestre),     // puede ser null si no existe
        assetBase: "{{ asset('') }}"
      };
    })();
  </script>
</head>
<body>
  <div class="wrapper">
    <a href="{{ url()->previous() }}"
       onclick="event.preventDefault(); if (history.length > 1) history.back(); else window.location.href='{{ route('admin.semestres') }}';"
       class="btn-flecha-back" title="Regresar" aria-label="Regresar">
      <svg viewBox="0 0 24 24" class="icon-flecha" aria-hidden="true" focusable="false" role="img">
        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
      </svg>
    </a>
    <main>
      <!-- Logos -->
      <div class="header-main">
        <div class="logo-enlace">
          <a href="https://www.gob.mx/sep" target="_blank">
            <img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="SEP">
          </a>
          <a href="https://www.tecnm.mx" target="_blank">
            <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="TecNM">
          </a>
          <a href="http://www.vetla.tecnm.mx/" target="_blank">
            <img src="{{ asset('Imagenes/pleca-ITVE.png') }}" alt="ITVE">
          </a>
        </div>
      </div>

      <!-- Encabezado principal -->
      <header>
        <h1>Tecnológico Nacional de México - Campus Valle de Etla</h1>
      </header>

      <div class="unidad">
        Unidad Académica: Centro de Investigación y Desarrollo de Energías Renovables en Unión Hidalgo
      </div>

      <div class="actividades-header">
        <h2>ACTIVIDADES EXTRAESCOLARES</h2>
        <hr class="linea-divisoria">
        <p>Bienvenido(a) a la plataforma de Actividades Extraescolares del TecNM Campus Valle de Etla, un espacio diseñado para impulsar tu formación integral a través de la participación en eventos culturales, deportivos, cívicos y de desarrollo personal.</p>
        <p>Aquí podrás consultar el calendario de actividades, registrarte en eventos, llevar el seguimiento de tus participaciones y obtener constancias de cumplimiento.</p>
        <p>¡Tu crecimiento va más allá del aula! Participa, aprende y transforma.</p>
      </div>

      <!-- Cuadro único para agregar / listar actividades (sin categorías) -->
      <div class="contenedor" style="max-width:1100px; margin:20px auto; justify-content:center;">
        <!-- Módulo para agregar actividad (clic abre modal de actividad) -->
        <div class="modulo agregar" role="button" aria-label="Agregar actividad" onclick="document.getElementById('modal-actividad').style.display='flex'">
          <div style="text-align:center;">
            <div style="font-size:50px; line-height:1;">+</div>
            <div style="font-size:25px; font-weight:700; color:#002147; margin-top:8px;">Agregar actividad</div>
            <div style="color:#666; font-size:13px; margin-top:6px;">Haz clic para crear una nueva actividad extraescolar</div>
          </div>
        </div>

        <!-- Contenedor donde se listarán las actividades (llenado por JS) -->
        <div id="actividades-list" style="width:100%; max-width:900px; margin-top:18px;">
          <div class="contenedor-tabla" style="padding:12px;">
            <div id="actividades-container" class="actividades-container" style="display:flex; flex-wrap:wrap; gap:16px; justify-content:flex-start;">
              <!-- Los módulos de actividad se insertarán aquí vía JS -->
            </div>
          </div>
        </div>
      </div>

      <!-- Modal para agregar/editar actividad -->
      <div id="modal-actividad" class="modal">
        <div class="modal-contenido">
          <h3 id="modal-titulo">Agregar Actividad Extraescolar</h3>
          <form id="formulario-actividad">
            <input type="text" id="nombre-actividad" placeholder="Nombre de la actividad" required>
            <textarea id="descripcion-actividad" placeholder="Descripción de la actividad" rows="4" required></textarea>
            
            <label for="imagen-actividad" class="drop-area" id="drop-area">
              <i class="fas fa-cloud-upload-alt"></i>
              <span>Arrastra una imagen o haz clic para seleccionar</span>
              <input type="file" id="imagen-actividad" accept="image/*" style="display:none">
            </label>
            
            <img id="vista-previa" src="#" alt="Vista previa de imagen">
            
            <div class="modal-botones">
              <button type="button" id="btn-guardar-actividad" class="btn-primary">Agregar Actividad</button>
              <button type="button" id="btn-cancelar-actividad" class="btn-secondary">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-text">
          <h3>Dirección</h3>
          <p>Abasolo S/N, Barrio del Agua Buena<br>Santiago Suchilquitongo Oaxaca, C.P. 68230</p>
          
          <h3>Contacto</h3>
          <p>Email: info@vetla.tecnm.mx<br>Teléfono: 951 305 29 27</p>
          
          <h3>Preguntar por Whatsapp</h3>
          <a href="https://wa.me/9513052927?text=Buen%20d%C3%ADa%2C%20deseo%20informaci%C3%B3n%20" target="_blank" class="whatsapp-link">
            <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="Whatsapp">
          </a>
        </div>
        
        <div class="footer-map">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15241.367048623282!2d-96.8702191!3d17.2506929!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85c6e36d891967db%3A0x512f566ad941ae57!2sTecnol%C3%B3gico%20Nacional%20de%20M%C3%A9xico%20campus%20Instituto%20Tecnol%C3%B3gico%20del%20Valle%20de%20Etla!5e0!3m2!1ses!2smx!4v1621983062705!5m2!1ses!2smx" allowfullscreen></iframe>
        </div>
      </div>
      
      <div class="footer-copyright">
        <p>© {{ date('Y') }} Tecnológico Nacional de México - Campus Valle de Etla. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

  <!-- Configuración para actividadesUH.js (NO tocar diseño) -->
  <script>
    window.ActUH = {
      rutas: {
        list: "{{ route('administrador.actividades.index') }}",
        store: "{{ route('administrador.actividades.store') }}",
        showBase: "{{ url('administrador/actividades') }}",
        // ruta de detalle (vista que muestra estudiantes de la actividad)
        detail: "{{ url('administrador/D_actividades_UH') }}",
      },
      unidadId: 1,
      // asigna aquí el semestre actual (ej. pasarlo desde el controlador como $semestre->id_semestre)
      semestreId: {{ isset($semestre) ? (int)$semestre->id_semestre : 1 }},
      assetBase: "{{ asset('') }}"
    };
  </script>

  <script>
    // No sobrescribir si ya existe una configuración más precisa
    if (!window.ActUH) {
      window.ActUH = {
        rutas: {
          list: "{{ route('administrador.actividades.index') }}",
          store: "{{ route('administrador.actividades.store') }}",
          showBase: "{{ url('administrador/actividades') }}",
          detail: "{{ url('administrador/vista_previa_U/D_actividades_UH') }}",
        },
        // unidad/semestre: preferir variables servidorales, si no usar query string, si no fallback
        unidadId: @json($unidad->id_unidad ?? request()->query('id_unidad') ?? 1),
        semestreId: @json($semestre->id_semestre ?? request()->query('id_semestre') ?? null),
        assetBase: "{{ asset('') }}"
      };
    } else {
      // si existe, asegúrate que tenga assetBase y rutas mínimas
      window.ActUH.rutas = window.ActUH.rutas || {
        list: "{{ route('administrador.actividades.index') }}",
        store: "{{ route('administrador.actividades.store') }}",
        showBase: "{{ url('administrador/actividades') }}",
        detail: "{{ url('administrador/vista_previa_U/D_actividades_UH') }}"
      };
      window.ActUH.assetBase = window.ActUH.assetBase || "{{ asset('') }}";
      // preservar semestre y unidad ya calculados en head (no cambiarlos aquí)
    }
  </script>

  <script src="{{ asset('js/actividades.js') }}"></script>
 </body>
 </html>