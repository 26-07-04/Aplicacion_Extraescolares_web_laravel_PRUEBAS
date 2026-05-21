<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Panel Administrativo</title>
  <link rel="stylesheet" href="{{ asset('css/Administrador/Principal_administrador.css') }}" />
  
  <!-- Carga de Font Awesome desde CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Alineación y espaciado sólo para la sección ADMINISTRACIÓN (no afectar UNIDADES) */
    .sidebar-section.admin-section ul { display: flex; flex-direction: column; gap: 8px; padding-left: 12px; }
    .sidebar-section.admin-section ul li a { display: flex; align-items: center; gap: 8px; color: inherit; text-decoration: none; }
    .sidebar-section.admin-section ul li.admin-documentos { margin-bottom: 10px; }
    .sidebar-section.admin-section ul li.admin-usuarios { margin-top: 6px; }
    
    /* Estilo para el enlace INICIO */
    .inicio-dashboard {
      display: block;
      padding: 10px 12px;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }
    
    .inicio-dashboard:hover {
      background: rgba(255, 255, 255, 0.2);
      color: white !important;
      transform: translateX(5px);
    }
  </style>
</head>
<body>

  <!-- Encabezado superior con logos y usuario -->
  <div class="header">
    <div class="header-logos">
      <img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="Logo Educación">
      <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Logo TecNM">
      <img src="{{ asset('Imagenes/Logo IT Valle de etla.png') }}" alt="Logo ITVE">
    </div>
    <div class="header-user" style="position: relative; display:flex; align-items:center; gap:20px;">
      <!-- Botón regresar con id_semestre -->
      <a href="{{ route('admin.semestres') }}" class="btn-regresar-header" title="Regresar a Semestres cursados" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#1B396A; color:#fff; padding:6px 14px; min-width:30px; border-radius:6px; text-decoration:none; margin-right:24px;">
        <i class="fas fa-arrow-left" style="font-size:18px; color:#fff; line-height:1;"></i>
      </a>
      <i class="fas fa-user-circle" id="iconoPerfil" style="cursor: pointer; font-size:35px; color:#1B396A;"></i>

      <!-- Menú desplegable del perfil (oculto por defecto) -->
      <div id="perfilDropdown" style="display:none; position: absolute; right: 0; top: 48px; background: white; border: 1px solid #e5e5e5; box-shadow: 0 6px 18px rgba(0,0,0,0.08); border-radius: 6px; min-width:200px; z-index:2000;">
        <div style="padding:12px 14px; border-bottom:1px solid #f0f0f0;">
          <strong>{{ $user->nombre ?? 'Usuario' }}</strong>
          <div style="font-size:13px; color:#666;">{{ $user->contacto ?? $user->unidad_academica ?? '' }}</div>
        </div>
        <div style="padding:8px 10px;">
          <button id="btnCerrarSesion" style="width:100%; background:#dc3545; color:#fff; border:none; padding:8px 10px; border-radius:4px; cursor:pointer;">Cerrar sesión</button>
        </div>
      </div>

      <!-- Formulario de logout oculto -->
      <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>
  </div>

  <!-- Barra institucional azul -->
  <div class="barra-institucional">
    <div class="barra-izquierda">
      
    </div>

    <div class="titulo-barra">
      Panel Administrativo - Actividades Extraescolares
    </div>

    <div class="barra-derecha">
      <!-- espacio reservado -->
    </div>
  </div>

  <!-- Sidebar Dashboard -->
  <div class="sidebar" style="position: absolute; top: 129px; width: 180px; height: calc(140% - 150px); font-family: 'Open Sans', sans-serif;">
    <div class="dashboard-title" style="text-align: center; font-size: 16px; font-weight: bold; color: white; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
      <img src="{{ asset('Imagenes/ITVE.png') }}" alt="ITVE Logo" style="width: 60px; height: 60px; margin-bottom: 10px; margin-left: -10px;">
      <span>Extraescolares</span>
    </div>
    
    <!-- PANEL PRINCIPAL -->
    <div class="sidebar-section">
      <h3 style="font-family: 'Open Sans', sans-serif;">PANEL PRINCIPAL</h3>
      <ul style="font-family: 'Segoe UI', sans-serif; font-size: 16px; color: rgba(255, 255, 255, 0.7); background-color: #1B396A; border-radius: 6px; padding: 8px;">
        <li class="inicio-dashboard" style="transition: all 0.3s; text-decoration: none; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
          <!-- INICIO: Sin id_semestre para mostrar vista de bienvenida -->
          <a href="{{ route('admin.principal') }}" style="color:inherit; text-decoration:none; display:block;">
            <i class="fas fa-home" style="margin-right: 8px;"></i>Inicio
          </a>
        </li>
      </ul>
    </div>
    
    <!-- UNIDADES -->
    <div class="sidebar-section">
      <h3 style="font-family: 'Open Sans', sans-serif;">UNIDADES</h3>
      <ul style="font-family: 'Segoe UI', sans-serif; font-size: 16px; color: rgba(255, 255, 255, 0.7); background-color: #1B396A; border-radius: 6px; padding: 8px;">
        <li style="margin-bottom: 10px; transition: all 0.3s; text-decoration: none; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
          <!-- UNIDADES: Con id_semestre para mantener el contexto -->
          @php
            // Determinar si hay un id_semestre en sesión o en la variable actual
            $idSemestreParaUnidades = session('id_semestre_actual') ?? $id_semestre ?? 0;
          @endphp
          <a href="{{ route('admin.principal', ['id' => $idSemestreParaUnidades]) }}?unidad=Union+Hidalgo" style="color:inherit; text-decoration:none; display:block;">
            <i class="fas fa-building" style="margin-right: 8px;"></i>Unión Hidalgo
          </a>
        </li>
        <li style="margin-bottom: 10px; transition: all 0.3s; text-decoration: none; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
          <a href="{{ route('admin.principal', ['id' => $idSemestreParaUnidades]) }}?unidad=Demetrio+Vallejo" style="color:inherit; text-decoration:none; display:block;">
            <i class="fas fa-building" style="margin-right: 8px;"></i>Demetrio Vallejo
          </a>
        </li>
        <li style="margin-bottom: 10px; transition: all 0.3s; text-decoration: none; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
          <a href="{{ route('admin.principal', ['id' => $idSemestreParaUnidades]) }}?unidad=Tlahuitoltepec" style="color:inherit; text-decoration:none; display:block;">
            <i class="fas fa-building" style="margin-right: 8px;"></i>Tlahuitoltepec
          </a>
        </li>
        <li style="transition: all 0.3s; text-decoration: none; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
          <a href="{{ route('admin.principal', ['id' => $idSemestreParaUnidades]) }}?unidad=Valle+de+Etla" style="color:inherit; text-decoration:none; display:block;">
            <i class="fas fa-building" style="margin-right: 8px;"></i>Valle de Etla
          </a>
        </li>
      </ul>
    </div>
    
    <!-- ADMINISTRACIÓN -->
    <div class="sidebar-section admin-section">
      <h3 style="font-family: 'Open Sans', sans-serif;">ADMINISTRACIÓN</h3>
      <ul style="font-family: 'Segoe UI', sans-serif; font-size: 16px; color: rgba(255, 255, 255, 0.7); background-color: #1B396A; border-radius: 6px; padding: 8px;">
        @php
          // Determinar la URL base según si hay id_semestre
          $idSemestreActual = session('id_semestre_actual') ?? $id_semestre ?? null;
          
          if ($idSemestreActual) {
            $baseUrl = route('admin.principal', ['id' => $idSemestreActual]);
          } else {
            $baseUrl = route('admin.principal');
          }
          
          $documentosUrl = $baseUrl . '?view=documentos';
          $usuariosUrl = $baseUrl . '?view=usuarios';
        @endphp

        <li class="admin-documentos" style="transition: all 0.3s; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)';">
          <a href="{{ $documentosUrl }}">
            <i class="fas fa-file-alt" style="margin-right: 8px;"></i>Documentos
          </a>
        </li>

        <li class="admin-usuarios" style="transition: all 0.3s; cursor: pointer;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)';">
          <a href="{{ $usuariosUrl }}">
            <i class="fas fa-users" style="margin-right: 8px;"></i>Gestión de Usuarios
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Main Content - Ahora renderizado server-side -->
  <div class="main-content">
    @hasSection('main-content')
      @yield('main-content')
    @else
      @if(!empty($view) && $view === 'usuarios')
        @include('administrador.partials.gestion_usuarios')
      @elseif(!empty($view) && $view === 'documentos')
        @include('administrador.partials.documentos')
      @elseif(!empty($unidad))
        @include('administrador.partials.unidad', [
          'unidad' => $unidad, 
          'actividades' => $actividades ?? [], 
          'estudiantes' => $estudiantes ?? [],
          'estudiantesPorActividad' => $estudiantesPorActividad ?? [],
          'id_semestre' => $id_semestre ?? 0
        ])
      @elseif(!empty($semestre))
        <div style="padding:18px;">
          <div class="semestre-card">
            <div class="left">
              <div class="icon"><i class="fas fa-calendar-alt"></i></div>
              <div>
                <h2 class="title">
                  {{ $semestre->nombre ?? $semestre->id ?? 'Semestre' }}
                  @if(!empty($semestre->estatus))
                    <span class="semestre-badge">{{ $semestre->estatus }}</span>
                  @endif
                </h2>
                <div class="subtitle">Panel del semestre — gestiona actividades y participantes</div>
                <p class="semestre-description {{ (isset($semestre->descripcion) && strlen($semestre->descripcion) > 160) ? 'truncated' : '' }}">
                  {{ $semestre->descripcion ?? 'Este semestre agrupa las actividades extraescolares programadas para la comunidad del tecnológico. Aquí verás un resumen de actividades y la participación estudiantil.' }}
                </p>
                @if(!empty($semestre->updated_at))
                  <div class="semestre-updated">Última actualización: {{ optional($semestre->updated_at)->format('d M Y H:i') }}</div>
                @endif
              </div>
            </div>

            <div class="right">
              <div class="stat activities">
                <div class="stat-head">
                  <span class="stat-icon"><i class="fas fa-calendar-check"></i></span>
                  <div class="label">Actividades</div>
                </div>
                <div class="value">{{ $actividades->count() ?? 0 }}</div>
              </div>

              <div class="stat students">
                <div class="stat-head">
                  <span class="stat-icon"><i class="fas fa-user-graduate"></i></span>
                  <div class="label">Estudiantes</div>
                </div>
                <div class="value">{{ $estudiantes->count() ?? 0 }}</div>
              </div>

              <div class="stat status">
                <div class="stat-head">
                  <span class="stat-icon"><i class="fas fa-info-circle"></i></span>
                  <div class="label">Estado</div>
                </div>
                <div class="value">{{ $semestre->estatus ?? 'Activo' }}</div>
              </div>
            </div>
          </div>
        </div>
      @else
        <!-- VISTA DE BIENVENIDA (sin parámetros) -->
        <div class="bienvenida-panel">
          <h1>Bienvenido al Panel Administrador</h1>
          <p>Aquí podrás gestionar las actividades extraescolares, administrar usuarios y mucho más.</p>
          <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Logo TecNM">
          <div class="accion-wrapper">
            <a href="{{ route('admin.unidades') }}" class="btn-coordinador">
              <i class="fas fa-user-cog"></i>Panel Coordinador
            </a>
          </div>
        </div>
      @endif
    @endif
  </div>

  <script>
    (function(){
      const icon = document.getElementById('iconoPerfil');
      const dropdown = document.getElementById('perfilDropdown');
      const btnCerrar = document.getElementById('btnCerrarSesion');
      const logoutForm = document.getElementById('logoutForm');

      function hideDropdown() { 
        if(dropdown) dropdown.style.display = 'none'; 
      }
      
      function showDropdown() { 
        if(dropdown) dropdown.style.display = 'block'; 
      }

      icon && icon.addEventListener('click', function (e) {
        e.stopPropagation();
        if (!dropdown) return;
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
      });

      // Cerrar al hacer clic fuera
      document.addEventListener('click', function (e) {
        if (!dropdown) return;
        const target = e.target;
        if (target === dropdown || dropdown.contains(target) || target === icon) return;
        hideDropdown();
      });

      // Enviar formulario de logout
      btnCerrar && btnCerrar.addEventListener('click', function (e) {
        e.preventDefault();
        if (!logoutForm) return window.location.href = '/';
        logoutForm.submit();
      });
      
      // Guardar id_semestre en localStorage para recuperarlo si se recarga
      document.addEventListener('DOMContentLoaded', function() {
        const idSemestre = {{ $id_semestre ?? 'null' }};
        if (idSemestre) {
          localStorage.setItem('last_semestre_id', idSemestre);
        }
      });
    })();
  </script>
</body>
</html>