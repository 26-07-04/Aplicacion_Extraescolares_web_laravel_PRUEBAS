// Gestión de actividades 

class GestorActividades {
    constructor() {
        this.actividadEditando = null;
        this.contenedorActividadesActual = null;
        this.init();
    }

    // helper: detectar la configuración activa (ActSMT, ActDV, ActUH, etc.)
    getActConfig() {
        const keys = ['ActSMT','ActDV','ActUH','ActVE','ActUH'];
        for (const k of keys) if (window[k]) return window[k];
        // fallback: tomar cualquier window.Act* con rutas
        for (const p in window) {
            if (p.startsWith && p.startsWith('Act') && window[p] && window[p].rutas) return window[p];
        }
        return window.ActUH || window.ActVE || null;
    }

    // resolver URL completa de imagen según assetBase y si la ruta es relativa
    resolveImageSrc(imagenPath) {
        if (!imagenPath) return '/Imagenes/placeholder-actividad.jpg';
        // si ya es data: o http(s) usar tal cual
        if (/^data:|^https?:\/\//i.test(imagenPath)) return imagenPath;
        const cfg = this.getActConfig();
        const base = cfg && cfg.assetBase ? cfg.assetBase.replace(/\/+$/,'') + '/' : '/';
        // si path empieza con slash usar tal cual
        if (imagenPath.startsWith('/')) return imagenPath;
        return base + imagenPath;
    }

    init() {
        this.configurarEventos();
        // cargar actividades desde servidor al iniciar
        this.cargarActividadesDesdeServidor();
        // No cargar actividades estáticas aquí. Usar cargarDatosDesdeServidor() o insertar manualmente.
    }

    configurarEventos() {
        // Botones modal actividad
        const btnGuardarActividad = document.getElementById('btn-guardar-actividad');
        const btnCancelarActividad = document.getElementById('btn-cancelar-actividad');

        if (btnGuardarActividad) {
            btnGuardarActividad.addEventListener('click', () => this.guardarActividad());
        }

        if (btnCancelarActividad) {
            btnCancelarActividad.addEventListener('click', () => this.cerrarModalActividad());
        }

        // Configuración para arrastrar imágenes
        this.configurarDropArea();

        // Abrir modal desde el módulo "agregar" si existe en DOM estático
        document.addEventListener('click', (e) => {
            const target = e.target.closest && e.target.closest('.modulo.agregar');
            if (target) {
                this.abrirModalActividad(document.getElementById('actividades-container'));
            }
        });
    }

    configurarDropArea() {
        const dropArea = document.getElementById('drop-area');
        const imagenInput = document.getElementById('imagen-actividad');
        const vistaPrevia = document.getElementById('vista-previa');

        if (!dropArea || !imagenInput || !vistaPrevia) return;

        ['dragover','dragleave','drop'].forEach(ev => {
            dropArea.addEventListener(ev, (e) => e.preventDefault());
        });

        dropArea.addEventListener('dragover', () => {
            dropArea.style.borderColor = '#ff7f00';
            dropArea.style.backgroundColor = 'rgba(255,127,0,0.06)';
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.style.borderColor = '#002147';
            dropArea.style.backgroundColor = '';
        });

        dropArea.addEventListener('drop', (e) => {
            dropArea.style.borderColor = '#002147';
            dropArea.style.backgroundColor = '';
            const archivo = e.dataTransfer.files[0];
            if (archivo && archivo.type.startsWith('image/')) this.cargarImagen(archivo);
        });

        dropArea.addEventListener('click', () => imagenInput.click());

        imagenInput.addEventListener('change', (e) => {
            const archivo = e.target.files[0];
            if (archivo && archivo.type.startsWith('image/')) this.cargarImagen(archivo);
        });
    }

    cargarImagen(archivo) {
        const vistaPrevia = document.getElementById('vista-previa');
        const dropArea = document.getElementById('drop-area');
        if (!vistaPrevia || !dropArea) return;
        const reader = new FileReader();
        reader.onload = () => {
            vistaPrevia.src = reader.result;
            vistaPrevia.style.display = 'block';
            dropArea.style.display = 'none';
        };
        reader.readAsDataURL(archivo);
    }

    // MODAL ACTIVIDADES
    abrirModalActividad(contenedorActividades = null, modulo = null) {
        const modal = document.getElementById('modal-actividad');
        const tituloModal = document.getElementById('modal-titulo');
        const btnAccion = document.getElementById('btn-guardar-actividad');
        const dropArea = document.getElementById('drop-area');
        const vistaPrevia = document.getElementById('vista-previa');

        if (!modal || !tituloModal || !btnAccion) return;

        this.contenedorActividadesActual = contenedorActividades;

        if (modulo) {
            // edición
            this.actividadEditando = modulo;
            tituloModal.textContent = "Editar Actividad";
            btnAccion.textContent = "Guardar Cambios";
            const h3 = modulo.querySelector('h3');
            const p = modulo.querySelector('p');
            if (h3) document.getElementById('nombre-actividad').value = h3.textContent;
            if (p) document.getElementById('descripcion-actividad').value = p.textContent;
            if (vistaPrevia) {
                const img = modulo.querySelector('img');
                if (img) {
                    vistaPrevia.src = img.src;
                    vistaPrevia.style.display = 'block';
                }
            }
            if (dropArea) dropArea.style.display = 'none';
        } else {
            // agregar
            this.actividadEditando = null;
            tituloModal.textContent = "Agregar Actividad";
            btnAccion.textContent = "Agregar Actividad";
            const form = document.getElementById('formulario-actividad');
            if (form) form.reset();
            if (vistaPrevia) vistaPrevia.style.display = 'none';
            if (dropArea) dropArea.style.display = 'block';
        }

        modal.style.display = 'flex';
    }

    cerrarModalActividad() {
        const modal = document.getElementById('modal-actividad');
        if (modal) modal.style.display = 'none';
    }

    async guardarActividad() {
         const nombre = (document.getElementById('nombre-actividad') || {}).value;
         const descripcion = (document.getElementById('descripcion-actividad') || {}).value;

         if (!nombre || !descripcion) {
             this.mostrarAlerta('Error', 'Por favor completa todos los campos obligatorios', 'error');
             return;
         }

         // preparar FormData para enviar al servidor (si existe endpoint)
         const fd = new FormData();
         fd.append('nombre_actividad', nombre);
         fd.append('descripcion', descripcion);

         // id_unidad: preferir variable global si existe, si no no lo añadimos (asegúrate que form/JS de la vista lo añade)
         const unidadId = (window.ActUH && window.ActUH.unidadId) ? window.ActUH.unidadId : (document.getElementById('id_unidad')?.value || null);
         if (unidadId) fd.append('id_unidad', unidadId);

        // id_semestre: tomar de configuración global o de input hidden
        const semestreId = (window.ActUH && window.ActUH.semestreId) ? window.ActUH.semestreId : (document.getElementById('id_semestre')?.value || null);
        if (semestreId !== null && semestreId !== undefined && semestreId !== '') {
            fd.append('id_semestre', semestreId);
        }

         const inputFile = document.getElementById('imagen-actividad');
         if (inputFile && inputFile.files && inputFile.files[0]) {
             fd.append('imagen', inputFile.files[0]);
         }

         // determinar URL y método: crear o actualizar
         let url = (window.ActUH && window.ActUH.rutas && window.ActUH.rutas.store) ? window.ActUH.rutas.store : '/administrador/actividades';
         let method = 'POST';
         const editarId = this.actividadEditando ? (this.actividadEditando.dataset.idActividad || this.actividadEditando.dataset.id) : null;

         if (editarId) {
             // emular PUT con _method
             fd.append('_method', 'PUT');
             url = ((window.ActUH && window.ActUH.rutas && window.ActUH.rutas.showBase) ? window.ActUH.rutas.showBase : '/administrador/actividades') + '/' + editarId;
             method = 'POST';
         }

         // CSRF
         const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

         try {
             const res = await fetch(url, {
                 method: method,
                 headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {},
                 body: fd,
                 credentials: 'same-origin'
             });

             if (res.status === 422) {
                 const err = await res.json();
                 const msgs = err.errors ? Object.values(err.errors).flat().join('\n') : JSON.stringify(err);
                 this.mostrarAlerta('Validación', msgs, 'error');
                 return;
             }

             if (!res.ok) throw new Error('Error en servidor al guardar actividad');

             const saved = await res.json(); // esperar respuesta con { id_actividad, imagen_url, nombre_actividad, descripcion, ... }

            // asegurar que servidor devuelve id y imagen_url; normalizar campos
            const idGuardado = saved.id_actividad ?? saved.id ?? saved.idActividad;
            const nombreGuardado = saved.nombre_actividad ?? saved.nombre ?? nombre;
            const descripcionGuardada = saved.descripcion ?? saved.desc ?? descripcion;
            const imagenGuardada = saved.imagen_url ?? saved.imagen ?? saved.imagenUrl ?? null;
            const semestreGuardado = saved.id_semestre ?? saved.idSemestre ?? semestreId ?? null;

             // si edición: actualizar DOM del módulo editado
             if (this.actividadEditando) {
                 const mod = this.actividadEditando;
                 const h3 = mod.querySelector('h3');
                 const p = mod.querySelector('p');
                 if (h3) h3.textContent = nombreGuardado;
                 if (p) p.textContent = descripcionGuardada;
                 // actualizar imagen a la ruta devuelta
                 const img = mod.querySelector('img');
                 if (img && imagenGuardada) img.src = (window.ActUH && window.ActUH.assetBase ? window.ActUH.assetBase + imagenGuardada : imagenGuardada);
                 if (semestreGuardado) mod.dataset.idSemestre = semestreGuardado;
                 this.mostrarAlerta('Éxito', `Actividad "${nombreGuardado}" actualizada correctamente`, 'success');
             } else {
                 // crear nuevo módulo y añadir al contenedor
                 const imagenPath = imagenGuardada ? ((window.ActUH && window.ActUH.assetBase ? window.ActUH.assetBase + imagenGuardada : imagenGuardada)) : (document.getElementById('vista-previa')?.src || '/Imagenes/placeholder-actividad.jpg');
                 const nueva = this.crearModuloActividad({
                     id: idGuardado || Date.now(),
                     nombre: nombreGuardado,
                     descripcion: descripcionGuardada,
                     imagen: imagenPath,
                     id_semestre: semestreGuardado
                 });
                 const cont = document.getElementById('actividades-container');
                 const moduloAgregar = cont ? cont.querySelector('.modulo.agregar') : null;
                 if (cont) {
                     if (moduloAgregar) cont.insertBefore(nueva, moduloAgregar);
                     else cont.appendChild(nueva);
                 }
                 this.mostrarAlerta('Éxito', `Actividad "${nombreGuardado}" creada correctamente`, 'success');
             }

             // limpiar modal y estado
             this.cerrarModalActividad();

         } catch (err) {
             console.error(err);
             this.mostrarAlerta('Error', 'No se pudo guardar la actividad', 'error');
         }
     }

    // nueva función: carga actividades desde servidor para la unidad actual
    async cargarActividadesDesdeServidor() {
        const cont = document.getElementById('actividades-container') || document.querySelector('.actividades-container');
        if (!cont) return;
        cont.innerHTML = '<div style="color:#666">Cargando actividades...</div>';
        const rutas = (window.ActUH && window.ActUH.rutas) ? window.ActUH.rutas : null;
        const rutaList = rutas?.list ?? '/administrador/actividades';
        const unidadId = (window.ActUH && window.ActUH.unidadId) ? window.ActUH.unidadId : (document.getElementById('id_unidad')?.value || null);
        try {
            const res = await fetch(rutaList + '?id_unidad=' + encodeURIComponent(unidadId), { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Error listando actividades');
            const items = await res.json();
            // limpiar e insertar en el contenedor respetando módulo "agregar" si existe
            const addModule = cont.querySelector('.modulo.agregar');
            cont.innerHTML = '';
            items.forEach(item => {
                const imagenUrl = item.imagen_url ?? item.imagen ?? item.imagenUrl ?? null;
                const mdl = this.crearModuloActividad({
                    id: item.id_actividad ?? item.id ?? item.idActividad,
                    nombre: item.nombre_actividad ?? item.nombre,
                    descripcion: item.descripcion ?? item.desc,
                    imagen: imagenUrl ? ((window.ActUH && window.ActUH.assetBase ? window.ActUH.assetBase + imagenUrl : imagenUrl)) : '/Imagenes/placeholder-actividad.jpg',
                    id_semestre: item.id_semestre ?? item.idSemestre ?? null
                });
                cont.appendChild(mdl);
            });
            // volver a insertar módulo agregar al final si existía
            if (addModule) cont.appendChild(addModule);
        } catch (e) {
            console.error(e);
            cont.innerHTML = '<div style="color:#c33">Error cargando actividades</div>';
        }
    }

    editarActividad(modulo) {
        this.abrirModalActividad(modulo.closest('.actividades-container'), modulo);
    }

    async eliminarActividad(modulo) {
        const nombreActividad = modulo.querySelector('h3') ? modulo.querySelector('h3').textContent : '';
        const confirmed = await Swal.fire({
            title: '¿Eliminar actividad?',
            text: `¿Eliminar "${nombreActividad}"? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#fff',
            backdrop: 'rgba(0, 33, 71, 0.4)'
        });

        if (!confirmed.isConfirmed) return;

        const id = modulo.dataset.idActividad || modulo.dataset.id || modulo.getAttribute('data-id-actividad') || modulo.getAttribute('data-id');
        if (!id) {
            this.mostrarAlerta('Error', 'ID de actividad no disponible', 'error');
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const baseShow = (window.ActUH && window.ActUH.rutas && window.ActUH.rutas.showBase) ? window.ActUH.rutas.showBase : '/administrador/actividades';
        const urlDel = baseShow.replace(/\/+$/,'') + '/' + encodeURIComponent(id);

        try {
            // intentar DELETE directo
            let res = await fetch(urlDel, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}) },
                credentials: 'same-origin'
            });

            // si DELETE directo no fue aceptado (p. ej. 405/403), intentar fallback POST + _method=DELETE
            if (!res.ok) {
                const fd = new FormData();
                fd.append('_method', 'DELETE');
                res = await fetch(urlDel, {
                    method: 'POST',
                    headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {},
                    body: fd,
                    credentials: 'same-origin'
                });
            }

            if (!res.ok) throw new Error('Error al eliminar en servidor');

            // intentar parsear JSON, no bloquear si no es JSON
            const json = await res.json().catch(()=>({ success: true }));

            if (json && json.success === false) throw new Error('Servidor rechazó la eliminación');

            // eliminar del DOM y mostrar confirmación
            modulo.remove();
            this.mostrarAlerta('Eliminado', 'La actividad ha sido eliminada correctamente', 'success');

        } catch (err) {
            console.error(err);
            this.mostrarAlerta('Error', 'No se pudo eliminar la actividad en el servidor', 'error');
        }
    }

    // DATOS DE EJEMPLO: (DESACTIVADO) antes insertaba módulos estáticos
    cargarDatosEjemplo() {
        // función desactivada: ya no inserta actividades estáticas.
        // Si necesitas cargar desde servidor, implementa aquí fetch('/api/actividades') y usa crearModuloActividad para renderizar.
    }

    crearModuloActividad(actividad /* objeto con id, nombre, descripcion, etc */) {
        const modulo = document.createElement('div');
        modulo.classList.add('modulo','actividad');
        // Añadir dataset necesario para la redirección (usar atributos consistentes)
        // dataset
        modulo.dataset.idActividad = actividad.id;
        modulo.dataset.nombre = actividad.nombre;
        modulo.dataset.descripcion = actividad.descripcion;
        if (actividad.id_semestre) modulo.dataset.idSemestre = actividad.id_semestre;
        if (actividad.unidadId) modulo.dataset.unidadId = actividad.unidadId;

         // La estructura visual se mantiene igual: usar la ruta de imagen tal cual (relativa a public/)
         modulo.innerHTML = `
             <img src="${actividad.imagen}" alt="${actividad.nombre}" onerror="this.src='/Imagenes/placeholder-actividad.jpg'">
             <h3>${actividad.nombre}</h3>
             <p>${actividad.descripcion}</p>
             <div class="acciones-modulo">
                 <button class="btn-accion btn-editar" aria-label="Editar actividad">
                     <img src="/Imagenes/editar.png" alt="Editar">
                 </button>
                 <button class="btn-accion btn-eliminar" aria-label="Eliminar actividad">
                     <img src="/Imagenes/eliminar.png" alt="Eliminar">
                 </button>
             </div>
         `;

         // eventos en botones (delegación alternativa)
         const btnEditar = modulo.querySelector('.btn-editar');
         const btnEliminar = modulo.querySelector('.btn-eliminar');

         if (btnEditar) btnEditar.addEventListener('click', (e) => {
             e.stopPropagation();
             this.editarActividad(modulo);
         });

         if (btnEliminar) btnEliminar.addEventListener('click', (e) => {
             e.stopPropagation();
             this.eliminarActividad(modulo);
         });

         // click en el módulo abre detalle (redirige a D_actividades_DV o la ruta configurada)
         modulo.addEventListener('click', (e) => {
            // evitar que clicks en los botones internos también disparen (si necesario)
            const target = e.target;
            if (target.closest('.btn-accion')) return;

            // Prioridad rutas: ActDV.detail -> ActUH.rutas.detail -> gestorConfig.detalleBaseDV -> gestorConfig.detalleBase
            const rutasDV = (window.ActDV && window.ActDV.rutas) ? window.ActDV.rutas : null;
            const rutasUH = (window.ActUH && window.ActUH.rutas) ? window.ActUH.rutas : null;
            const base = (rutasDV && rutasDV.detail) || (rutasUH && rutasUH.detail) || (window.gestorConfig && (window.gestorConfig.detalleBaseDV || window.gestorConfig.detalleBase)) || null;
            if (!base) return;

            // obtener ids (prioridad: dataset del módulo -> configuración global -> inputs hidden)
            const idActividad = modulo.dataset.idActividad || modulo.getAttribute('data-id-actividad') || actividad.id || null;
            const unidadFromModule = modulo.dataset.unidadId || modulo.getAttribute('data-unidad') || null;
            const semestreFromModule = modulo.dataset.idSemestre || modulo.getAttribute('data-id-semestre') || null;

            const unidadGlobal = (window.ActDV && ('unidadId' in window.ActDV)) ? window.ActDV.unidadId : ((window.ActUH && ('unidadId' in window.ActUH)) ? window.ActUH.unidadId : null);
            const semestreGlobal = (window.ActDV && ('semestreId' in window.ActDV)) ? window.ActDV.semestreId : ((window.ActUH && ('semestreId' in window.ActUH)) ? window.ActUH.semestreId : null);

            // fallback a inputs hidden si existen
            const unidadInput = document.getElementById('id_unidad')?.value || null;
            const semestreInput = document.getElementById('id_semestre')?.value || null;

            const params = new URLSearchParams();
            if (idActividad) params.append('id_actividad', idActividad);
            const finalUnidad = unidadFromModule || unidadGlobal || unidadInput;
            if (finalUnidad) params.append('id_unidad', finalUnidad);
            const finalSemestre = semestreFromModule || semestreGlobal || semestreInput;
            if (finalSemestre) params.append('id_semestre', finalSemestre);

            const sep = base.includes('?') ? '&' : '?';
            window.location.href = base + sep + params.toString();
        });

        return modulo;
    }

    mostrarAlerta(titulo, texto, icono) {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: icono,
            confirmButtonText: 'Aceptar',
            background: '#fff',
            backdrop: 'rgba(0, 33, 71, 0.4)'
        });
    }
}

// Exportar instancia global mínima
document.addEventListener('DOMContentLoaded', function() {
    window.gestorActividades = new GestorActividades();
});

// Cerrar modal actividad al hacer clic fuera
window.addEventListener('click', function(event) {
    const modalActividad = document.getElementById('modal-actividad');
    if (modalActividad && event.target === modalActividad) {
        if (window.gestorActividades) window.gestorActividades.cerrarModalActividad();
    }
});

// --- inicio: redirección a detalle de actividad (condicional) ---
(function () {
  function getDetalleBaseForModulo(modulo) {
    // prioridad: data-unidad en el módulo -> window.gestorConfig.detalleBase{Unidad} -> window.gestorConfig.detalleBase
    const unidad = (modulo && (modulo.dataset.unidad || modulo.getAttribute('data-unidad'))) || null;
    if (unidad) {
      const key = 'detalleBase' + unidad.toUpperCase(); // e.g. detalleBaseDV or detalleBaseUH
      if (window.gestorConfig && window.gestorConfig[key]) return window.gestorConfig[key];
    }
    if (window.gestorConfig && window.gestorConfig.detalleBase) return window.gestorConfig.detalleBase;
    return null;
  }

  document.addEventListener('click', function (e) {
    // buscar módulo más cercano (ajusta selectores si tu HTML cambia)
    const modulo = e.target.closest && e.target.closest('.modulo.actividad, .modulo.actividad-item, .modulo');
    if (!modulo) return;

    const base = getDetalleBaseForModulo(modulo);
    if (!base) return;

    const id = modulo.dataset.idActividad || modulo.dataset.id || modulo.getAttribute('data-id-actividad') || modulo.getAttribute('data-id');
    if (!id) return;

    const nombre = modulo.dataset.nombre || modulo.getAttribute('data-nombre') || '';
    const descripcion = modulo.dataset.descripcion || modulo.getAttribute('data-descripcion') || '';
    const sep = base.indexOf('?') === -1 ? '?' : '&';
    let url = base + sep + 'id_actividad=' + encodeURIComponent(id);
    if (nombre) url += '&nombre=' + encodeURIComponent(nombre);
    if (descripcion) url += '&descripcion=' + encodeURIComponent(descripcion);
    window.location.href = url;
  }, false);

  // helper público
  window.gestorActividadesRedirect = {
    detalleUrlForModulo(modulo) {
      const base = getDetalleBaseForModulo(modulo);
      if (!base) return null;
      const id = modulo.dataset.idActividad || modulo.dataset.id || modulo.getAttribute('data-id-actividad') || modulo.getAttribute('data-id');
      if (!id) return null;
      const nombre = modulo.dataset.nombre || modulo.getAttribute('data-nombre') || '';
      const descripcion = modulo.dataset.descripcion || modulo.getAttribute('data-descripcion') || '';
      const sep = base.indexOf('?') === -1 ? '?' : '&';
      let url = base + sep + 'id_actividad=' + encodeURIComponent(id);
      if (nombre) url += '&nombre=' + encodeURIComponent(nombre);
      if (descripcion) url += '&descripcion=' + encodeURIComponent(descripcion);
      return url;
    },
    goToDetalleForModulo(modulo) {
      const u = this.detalleUrlForModulo(modulo);
      if (u) window.location.href = u;
    }
  };
})();
// --- fin: redirección ---