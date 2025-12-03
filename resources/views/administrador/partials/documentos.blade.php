<div class="documentos-page">
  <style>
    /* Ajustes ligeros para SweetAlert2: reducir solo el tamaño de letra,
       mantener el popup y el icono en tamaño normal para evitar solapamientos. */
    .swal2-popup {
      font-size: 14px !important;
      padding: 1.2rem !important;
      max-width: 360px !important;
    }
    .swal2-title {
      font-size: 15px !important;
      font-weight: 600 !important;
      margin-bottom: 0.25rem !important;
    }
    .swal2-content {
      font-size: 13px !important;
      margin-top: 0.25rem !important;
    }
    /* Estilos para las tarjetas de documentos: separar acciones a la derecha */
    .documento-card {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 12px;
      padding: 12px;
      border: 1px solid #eee;
      border-radius: 6px;
      margin-bottom: 10px;
      background: #fff;
    }
    .documento-main { display:flex; gap:12px; align-items:flex-start; flex:1; }
    .documento-icon { width:42px; height:42px; display:flex;align-items:center;justify-content:center;background:#f8f9fa;border-radius:6px;font-size:18px;color:#d9534f; }
    .documento-title { font-weight:600; margin-bottom:4px; }
    .documento-desc { color:#666; margin-bottom:6px; }
    .documento-info { color:#444; font-size:13px; display:flex; gap:12px; flex-wrap:wrap; }
    .documento-actions { margin-left:auto; display:flex; gap:8px; align-items:center; }
  </style>
  <div class="actions-bar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
    <h2 class="section-title" style="margin:0;display:flex;align-items:center;gap:10px;"><i class="fas fa-folder"></i> Documentos Existentes</h2>
    <div>
      @php
        // Determinar el semestre en contexto: primero usar la variable pasada desde el controlador
        $semestreActual = isset($semestre) && $semestre ? $semestre : (\App\Models\Semestre::where('estatus',1)->first() ?? \App\Models\Semestre::orderBy('id_semestre','desc')->first());
      @endphp
      <button id="btnAbrirModalSubir" class="btn-submit" style="background:#1B396A;color:#fff;border-radius:6px;padding:8px 12px;border:none;cursor:pointer;"><i class="fas fa-upload"></i> Subir Documento</button>
    </div>
  </div>
  <div class="documentos-container" id="documentosLista" style="margin-top:8px;">
    @php
      // Cargar documentos; si hay semestre en contexto, filtrar por él
      $documentosQuery = \App\Models\Documento::with('semestre')->orderBy('created_at','desc');
      if(isset($semestreActual) && $semestreActual) {
        $documentosQuery->where('id_semestre', $semestreActual->id_semestre);
      }
      $documentos = $documentosQuery->get();
    @endphp
    @if($documentos->isEmpty())
      <div class="empty-state" id="emptyState">
        <i class="fas fa-folder-open" style="font-size:40px;color:#bdc3c7;margin-bottom:8px;"></i>
        <h3>No hay documentos disponibles</h3>
        <p>Sube el primer documento utilizando el botón "Subir Documento".</p>
      </div>
    @else
      @foreach($documentos as $doc)
        <div class="documento-card" data-id="{{ $doc->id }}">
          <div class="documento-main">
            @php
              $ext = strtolower(pathinfo($doc->archivo ?? '', PATHINFO_EXTENSION));
              // Usar un icono alternativo para PDF
              $iconClass = $ext === 'pdf' ? 'fas fa-file-lines' : (in_array($ext, ['png','jpg','jpeg','gif','webp']) ? 'fas fa-file-image' : 'fas fa-file');
            @endphp
            <div class="documento-icon"><i class="{{ $iconClass }}"></i></div>
            <div style="flex:1;">
              <div class="documento-title">{{ $doc->nombre }}</div>
              <div class="documento-desc">{{ $doc->descripcion }}</div>
              <div class="documento-info">
                <div><strong>Semestre:</strong> {{ $doc->semestre?->nombre ?? 'N/A' }}</div>
                <div><strong>Fecha:</strong> {{ $doc->created_at?->format('d/m/Y') }}</div>
              </div>
            </div>
          </div>
          <div class="documento-actions" style="display:flex;gap:8px;align-items:center;">
            @php
              // Estilos base y variantes por acción (ver, descargar, eliminar)
              $btnBase = 'text-decoration:none;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:4px;padding:0;font-size:14px;line-height:1;border:none;cursor:pointer;color:#ffffff;';
              $iconDisabledStyle = 'display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:4px;padding:0;font-size:14px;line-height:1;border:none;background:#f0f0f0;color:#9aa0a6;cursor:default;';

              $btnVerStyle = $btnBase . 'background:#3498db;';      // azul claro
              $btnDescargarStyle = $btnBase . 'background:#2ecc71;'; // verde
              $btnEliminarStyle = $btnBase . 'background:#e74c3c;';  // rojo
            @endphp
            @if($doc->archivo)
              <a class="btn-documento btn-ver" href="{{ asset($doc->archivo) }}" target="_blank" style="{{ $btnVerStyle }}" title="Ver" aria-label="Ver documento"><i class="fas fa-eye" style="font-size:14px;color:inherit;"></i></a>
            @else
              <button class="btn-documento btn-ver" disabled title="No hay archivo" style="{{ $iconDisabledStyle }}"><i class="fas fa-eye" style="font-size:14px;color:inherit;"></i></button>
            @endif

            @php
              $fileUrl = $doc->archivo ? asset($doc->archivo) : '#';
              $ext = $doc->archivo ? pathinfo($doc->archivo, PATHINFO_EXTENSION) : '';
              $downloadName = $doc->nombre . ($ext ? '.' . $ext : '');
            @endphp
            @if($doc->archivo)
              <a class="btn-documento btn-descargar" href="{{ $fileUrl }}" style="{{ $btnDescargarStyle }}" title="Descargar" aria-label="Descargar documento" download="{{ $downloadName }}"><i class="fas fa-download" style="font-size:14px;color:inherit;"></i></a>
            @else
              <a class="btn-documento btn-descargar" href="#" style="{{ $iconDisabledStyle }}" title="No hay archivo" aria-label="Descargar documento" tabindex="-1"><i class="fas fa-download" style="font-size:14px;color:inherit;"></i></a>
            @endif

            <form method="POST" action="{{ route('admin.documentos.destroy', $doc->id) }}" class="doc-delete-form" style="display:inline-block;margin:0;padding:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-documento btn-eliminar" style="{{ $btnEliminarStyle }}" title="Eliminar" aria-label="Eliminar documento"><i class="fas fa-trash" style="font-size:14px;color:inherit;"></i></button>
            </form>
          </div>
        </div>
      @endforeach
    @endif
  </div>
  <!-- Modal para subir documento -->
  <div id="modalSubirDocumento" class="modal" style="display:none;">
    <div class="modal-contenido">
      <h2 style="margin-top:0;"><i class="fas fa-cloud-upload-alt"></i> Subir Documento</h2>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        <script>
          // Mostrar SweetAlert auto-dismiss si hay mensaje de éxito en sesión
          (function(){
            const msg = {!! json_encode(session('success')) !!};
            if(msg){
              Swal.fire({
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
              });
              // Intentar ocultar también el alert HTML si existe
              setTimeout(()=>{
                const a = document.querySelector('.alert.alert-success'); if(a) a.remove();
              }, 1900);
            }
          })();
        </script>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="documentoForm" class="upload-form" action="{{ route('admin.documentos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="nombreDocumento">Nombre del Documento</label>
          <input id="nombreDocumento" name="nombre" class="form-control" type="text" placeholder="Ej: Reglamento de Actividades Extraescolares 2025" value="{{ old('nombre') }}" required>
        </div>
        <div class="form-group">
          <label for="descripcionDocumento">Descripción</label>
          <textarea id="descripcionDocumento" name="descripcion" class="form-control" rows="3" placeholder="Breve descripción...">{{ old('descripcion') }}</textarea>
        </div>
        <div class="form-group">
          {{-- Usar el semestre actual detectado; no mostrar select en el modal --}}
          <input type="hidden" id="id_semestre" name="id_semestre" value="{{ $semestreActual?->id_semestre }}">
        </div>
        <div class="form-group">
          <label for="archivoDocumento">Archivo (PDF o Imagen)</label>
          <div class="file-input-wrapper" style="position:relative;">
            <button type="button" id="btnSeleccionarArchivo" class="file-input-button"><i class="fas fa-file-image"></i> Seleccionar archivo</button>
            <input id="archivoDocumento" name="archivo" type="file" accept="application/pdf,image/png,image/jpeg" style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;">
          </div>
          <small id="nombreArchivo" style="display:block;margin-top:6px;color:#666;"></small>
        </div>
        <div style="display:flex;gap:8px;margin-top:12px;justify-content:flex-end;">
          <button type="button" id="btnCerrarModal" class="btn-documento" style="background:#ccc;color:#000;padding:8px 12px;border-radius:6px;border:none;">Cancelar</button>
          <button id="btnSubirDocumento" class="btn-submit" type="submit" style="background:#2ecc71;color:#fff;padding:8px 12px;border-radius:6px;border:none;"><i class="fas fa-upload"></i> Subir</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      const btnAbrir = document.getElementById('btnAbrirModalSubir');
      const modal = document.getElementById('modalSubirDocumento');
      const btnCerrarModal = document.getElementById('btnCerrarModal');
      const archivoInput = document.getElementById('archivoDocumento');
      const nombreArchivo = document.getElementById('nombreArchivo');

      btnAbrir && btnAbrir.addEventListener('click', function(){
        if(!modal) return;
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
      });

      btnCerrarModal && btnCerrarModal.addEventListener('click', function(){
        if(!modal) return;
        modal.style.display = 'none';
      });

      modal && modal.addEventListener('click', function(e){ if(e.target === modal) modal.style.display = 'none'; });

      archivoInput && archivoInput.addEventListener('change', function(e){
        const f = e.target.files[0];
        nombreArchivo.textContent = f ? f.name : '';
      });
    })();
  </script>
  <script>
    (function(){
      // Manejar eliminación por AJAX para que la tarjeta se remueva del DOM sin recargar
      const deleteForms = document.querySelectorAll('.doc-delete-form');
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      deleteForms.forEach(form => {
        form.addEventListener('submit', function(e){
          e.preventDefault();
          const action = form.getAttribute('action');
          const card = form.closest('.documento-card');

          Swal.fire({
            title: '¿Eliminar documento?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then(result => {
            if(!result.isConfirmed) return;

            // Asegurar URL absoluta (evita que el navegador la resuelva de forma relativa)
            const url = new URL(action, window.location.origin);
            fetch(url.href, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              },
              credentials: 'same-origin'
            })
            .then(async res => {
              const json = await res.json().catch(()=>({}));
              if(res.ok){
                if(card) card.remove();
                Swal.fire({icon:'success', title: json.message || 'Documento eliminado', showConfirmButton:false, timer:1400, timerProgressBar:true});
              } else {
                const msg = json.message || 'Error al eliminar';
                Swal.fire({icon:'error', title:'Error', text: msg, showConfirmButton:false, timer:2000});
              }
            })
            .catch(err => {
              console.error(err);
              Swal.fire({icon:'error', title:'Error', text: 'No se pudo conectar al servidor'});
            });
          });
        });
      });
    })();
  </script>
</div>