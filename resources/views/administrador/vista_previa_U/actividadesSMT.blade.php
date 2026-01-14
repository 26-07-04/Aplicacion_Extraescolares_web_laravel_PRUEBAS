<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actividades Extraescolares - Santa María Tlahuitoltepec</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Administrador/actividades.css') }}">
  
  <!-- IMPORTANTE: FontAwesome real -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- CSRF token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Estilos personalizados para select de categorías -->
  <style>
    /* Mejorar diseño del select de categorías */
    #categoria-actividad {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 18px;
      padding-right: 12px !important;
    }
  </style>

  <!-- Configuración única (como en UH y DV) -->
  <script>
    (function(){
      // CAMBIO 1: Función simplificada para obtener id_semestre
      function obtenerIdSemestre() {
        const params = new URLSearchParams(window.location.search);
        const qSemestre = params.get('id_semestre');
        
        // También intentar desde variables del servidor
        const serverSemestre = @json(isset($id_semestre) ? $id_semestre : null);
        
        return qSemestre || serverSemestre || 3; // 3 como default para SMT
      }

      // CAMBIO 2: Configuración única (nota: cambiamos de ActUH a ActSMT)
      window.ActSMT = {
        rutas: {
          list: "{{ route('administrador.actividades.index') }}",
          store: "{{ route('administrador.actividades.store') }}",
          showBase: "{{ url('administrador/actividades') }}",
          detail: "{{ url('administrador/vista_previa_U/D_actividades_SMT') }}",
        },
        unidadId: 3,  // Santa María Tlahuitoltepec = 3
        semestreId: obtenerIdSemestre(),
        assetBase: "{{ asset('') }}"
      };
      
      console.log('Configuración ActSMT:', window.ActSMT);
    })();
  </script>
</head>
<body>
  <!-- CAMBIO 3: Agregar info del semestre como en UH y DV -->
  <div class="wrapper">
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
      <a href="{{ route('admin.principal', ['id' => request()->input('id_semestre', 3)]) }}"
         class="btn-flecha-back" title="Regresar al Panel Administrador" aria-label="Regresar">
        <svg viewBox="0 0 24 24" class="icon-flecha" aria-hidden="true" focusable="false" role="img">
          <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
        </svg>
      </a>
      <span style="color: #1B396A; font-weight: bold; font-size: 14px; display:none;">
        Semestre ID: <span id="current-semestre-id">{{ request()->input('id_semestre', 'N/A') }}</span>
      </span>
    </div>
    
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
        Unidad Académica: Santa María Tlahuitoltepec
      </div>

      <!-- CAMBIO 4: Agregar sección de información del semestre -->
      <div class="semestre-info" style="background: #f0f8ff; padding: 10px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #1B396A;">
        <p style="margin: 0; color: #1B396A; font-weight: bold; display:none;">
          <i class="fas fa-calendar-alt"></i> Actividades del Semestre ID: 
          <span id="display-semestre-id">{{ request()->input('id_semestre', 'No especificado') }}</span>
        </p>
      </div>

      <div class="actividades-header">
        <h2>ACTIVIDADES EXTRAESCOLARES</h2>
        <hr class="linea-divisoria">
        <p>Bienvenido(a) a la plataforma de Actividades Extraescolares del TecNM Campus Valle de Etla, un espacio diseñado para impulsar tu formación integral a través de la participación en eventos culturales, deportivos, cívicos y de desarrollo personal.</p>
        <p>Aquí podrás consultar el calendario de actividades, registrarte en eventos, llevar el seguimiento de tus participaciones y obtener constancias de cumplimiento.</p>
        <p>¡Tu crecimiento va más allá del aula! Participa, aprende y transforma.</p>
      </div>

      <!-- Cuadro único para agregar / listar actividades -->
      <div class="contenedor" style="max-width:1100px; margin:20px auto; justify-content:center;">
        <!-- Módulo para agregar actividad -->
        <div class="modulo agregar" role="button" aria-label="Agregar actividad" onclick="document.getElementById('modal-actividad').style.display='flex'">
          <div style="text-align:center;">
            <div style="font-size:50px; line-height:1;">+</div>
            <div style="font-size:25px; font-weight:700; color:#002147; margin-top:8px;">Agregar actividad</div>
            <div style="color:#666; font-size:13px; margin-top:6px;">Haz clic para crear una nueva actividad extraescolar</div>
          </div>
        </div>

        <!-- Contenedor donde se listarán las actividades -->
        <div id="actividades-list" style="width:100%; max-width:900px; margin-top:18px;">
          <div class="contenedor-tabla" style="padding:12px;">
            <div id="actividades-container" class="actividades-container" style="display:flex; flex-wrap:wrap; gap:16px; justify-content:flex-start;">
              <!-- Los módulos de actividad se insertarán aquí vía JS -->
              <div style="width:100%; text-align:center; padding:20px; color:#666;" id="loading-actividades">
                <i class="fas fa-spinner fa-spin"></i> Cargando actividades del semestre...
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal para agregar/editar actividad -->
      <!-- CAMBIO 5: Agregar campo oculto para id_semestre -->
      <div id="modal-actividad" class="modal">
        <div class="modal-contenido">
          <h3 id="modal-titulo">Agregar Actividad Extraescolar</h3>
          <input type="hidden" id="id-semestre-actividad" value="{{ request()->input('id_semestre', 3) }}">
          
          <form id="formulario-actividad">
            <input type="text" id="nombre-actividad" placeholder="Nombre de la actividad" required>
            
            <!-- NUEVO: Campo de categoría mejorado -->
            <select id="categoria-actividad" required style="width:100%; padding:12px 12px; border:1px solid #ddd; border-radius:6px; font-size:14px; margin-bottom:15px; color:#6f6f6f; cursor:pointer;">
              <option value="" disabled selected hidden>Seleccionar categoría</option>
              <option value="Deportivo">Deportivo</option>
              <option value="Cultural">Cultural</option>
              <option value="Academico">Académico</option>
            </select>
            
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
  
  <!-- ELIMINA ESTA LÍNEA DUPLICADA -->
  <!-- <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script> -->

  <!-- SCRIPT ÚNICO: actividades.js (el mismo que usa UH y DV) -->
  <script src="{{ asset('js/actividades.js') }}"></script>
  
  <!-- CAMBIO 6: Script para manejar el semestre (igual que en UH y DV) -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Actualizar display del semestre ID
      const semestreId = window.ActSMT.semestreId;
      document.querySelectorAll('#current-semestre-id, #display-semestre-id').forEach(el => {
        el.textContent = semestreId;
      });
      
      console.log('Actividades SMT cargadas para Semestre ID:', semestreId, 'Unidad ID:', window.ActSMT.unidadId);
      
      // Verificar que el script actividades.js esté usando estos parámetros
      if (typeof cargarActividades === 'function') {
        setTimeout(() => {
          cargarActividades();
        }, 100);
      }
    });
  </script>
</body>
</html>