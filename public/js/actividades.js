// Gestión de actividades - Versión Mejorada
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

        // Evitar abrir el selector dos veces: no llamar a input.click() si el click vino desde un <label> (el label ya abre el selector)
        let openingFileDialog = false;
        dropArea.addEventListener('click', (e) => {
            // si el click proviene de dentro de un label o del propio input, no disparamos el .click() manual
            if (e.target.closest && (e.target.closest('label') || e.target.closest('input[type="file"]'))) {
                return;
            }
            if (openingFileDialog) return;
            openingFileDialog = true;
            try { imagenInput.click(); }
            finally { setTimeout(() => { openingFileDialog = false; }, 600); }
        });

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

    // Funciones auxiliares para obtener configuración
    obtenerUnidadId() {
        // Múltiples formas de obtener el ID de unidad
        if (window.ActUH && window.ActUH.unidadId) return window.ActUH.unidadId;
        if (window.ActDV && window.ActDV.unidadId) return window.ActDV.unidadId;
        
        const unidadInput = document.getElementById('id_unidad');
        if (unidadInput && unidadInput.value) return unidadInput.value;
        
        // Buscar en cualquier configuración global
        for (const key in window) {
            if (key.startsWith('Act') && window[key] && window[key].unidadId) {
                return window[key].unidadId;
            }
        }
        
        return null;
    }

    obtenerSemestreId() {
        // Múltiples formas de obtener el ID de semestre
        if (window.ActUH && window.ActUH.semestreId) return window.ActUH.semestreId;
        if (window.ActDV && window.ActDV.semestreId) return window.ActDV.semestreId;
        
        const semestreInput = document.getElementById('id_semestre');
        if (semestreInput && semestreInput.value) return semestreInput.value;
        
        return null;
    }

    obtenerUrlGuardado() {
        // Buscar en todas las configuraciones posibles
        const configs = [window.ActUH, window.ActDV, window.ActSMT, window.ActVE];
        for (const config of configs) {
            if (config && config.rutas && config.rutas.store) {
                return config.rutas.store;
            }
        }
        // Fallback por tipo de actividad
        if (window.location.pathname.includes('unidad-horaria')) {
            return '/administrador/actividades-uh';
        } else if (window.location.pathname.includes('dia-viernes')) {
            return '/administrador/actividades-dv';
        }
        return '/administrador/actividades';
    }

    obtenerUrlEdicion(id) {
        const configs = [window.ActUH, window.ActDV, window.ActSMT, window.ActVE];
        for (const config of configs) {
            if (config && config.rutas && config.rutas.showBase) {
                return `${config.rutas.showBase}/${id}`;
            }
        }
        return `/administrador/actividades/${id}`;
    }

    obtenerCsrfToken() {
        // Múltiples formas de obtener el token CSRF
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.getAttribute('content');
        
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) return tokenInput.value;
        
        console.warn('CSRF token no encontrado');
        return '';
    }

    async procesarRespuestaExitosa(saved, nombre, descripcion, semestreId) {
        const idGuardado = saved.id_actividad ?? saved.id ?? saved.idActividad;
        const nombreGuardado = saved.nombre_actividad ?? saved.nombre ?? nombre;
        const descripcionGuardada = saved.descripcion ?? saved.desc ?? descripcion;
        const imagenGuardada = saved.imagen_url ?? saved.imagen ?? saved.imagenUrl ?? null;

        if (!idGuardado) {
            throw new Error('El servidor no devolvió un ID válido para la actividad');
        }

        // Si es edición: actualizar DOM
        if (this.actividadEditando) {
            const mod = this.actividadEditando;
            const h3 = mod.querySelector('h3');
            const p = mod.querySelector('p');
            if (h3) h3.textContent = nombreGuardado;
            if (p) p.textContent = descripcionGuardada;
            
            // Actualizar imagen
            const img = mod.querySelector('img');
            if (img && imagenGuardada) {
                img.src = this.resolveImageSrc(imagenGuardada);
            }
            
            if (semestreId) mod.dataset.idSemestre = semestreId;
            this.mostrarAlerta('Éxito', `Actividad "${nombreGuardado}" actualizada correctamente`, 'success');
        } else {
            // Crear nuevo módulo
            const imagenPath = imagenGuardada ? 
                this.resolveImageSrc(imagenGuardada) : 
                (document.getElementById('vista-previa')?.src || '/Imagenes/placeholder-actividad.jpg');
            
            const nueva = this.crearModuloActividad({
                id: idGuardado,
                nombre: nombreGuardado,
                descripcion: descripcionGuardada,
                imagen: imagenPath,
                id_semestre: semestreId
            });
            
            const cont = document.getElementById('actividades-container');
            const moduloAgregar = cont ? cont.querySelector('.modulo.agregar') : null;
            
            if (cont) {
                if (moduloAgregar) cont.insertBefore(nueva, moduloAgregar);
                else cont.appendChild(nueva);
            }
            this.mostrarAlerta('Éxito', `Actividad "${nombreGuardado}" creada correctamente`, 'success');
        }

        // Limpiar modal
        this.cerrarModalActividad();
    }

    async guardarActividad() {
        const nombre = (document.getElementById('nombre-actividad') || {}).value;
        const descripcion = (document.getElementById('descripcion-actividad') || {}).value;

        if (!nombre || !descripcion) {
            this.mostrarAlerta('Error', 'Por favor completa todos los campos obligatorios', 'error');
            return;
        }

        // Mostrar loading
        const btnGuardar = document.getElementById('btn-guardar-actividad');
        const originalText = btnGuardar.textContent;
        btnGuardar.textContent = 'Guardando...';
        btnGuardar.disabled = true;

        try {
            // preparar FormData para enviar al servidor
            const fd = new FormData();
            fd.append('nombre_actividad', nombre.trim());
            fd.append('descripcion', descripcion.trim());

            // Obtener IDs de manera más robusta
            const unidadId = this.obtenerUnidadId();
            const semestreId = this.obtenerSemestreId();

            if (unidadId) fd.append('id_unidad', unidadId);
            if (semestreId) fd.append('id_semestre', semestreId);

            // Manejar imagen
            const inputFile = document.getElementById('imagen-actividad');
            if (inputFile && inputFile.files && inputFile.files[0]) {
                // Validar tamaño de imagen (max 5MB)
                if (inputFile.files[0].size > 5 * 1024 * 1024) {
                    throw new Error('La imagen no debe superar los 5MB');
                }
                fd.append('imagen', inputFile.files[0]);
            }

            // Determinar URL y método
            let url = this.obtenerUrlGuardado();
            let method = 'POST';
            const editarId = this.actividadEditando ? 
                (this.actividadEditando.dataset.idActividad || this.actividadEditando.dataset.id) : null;

            if (editarId) {
                // Para edición, usar PUT
                fd.append('_method', 'PUT');
                url = this.obtenerUrlEdicion(editarId);
                method = 'POST';
            }

            // Obtener CSRF token de manera más robusta
            const csrf = this.obtenerCsrfToken();

            console.log('Enviando datos a:', url, 'Método:', method); // Debug

            const res = await fetch(url, {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                },
                body: fd,
                credentials: 'same-origin'
            });

            // Manejar diferentes códigos de estado
            if (res.status === 422) {
                const err = await res.json();
                const msgs = err.errors ? Object.values(err.errors).flat().join('\n') : 
                             (err.message || JSON.stringify(err));
                throw new Error(`Error de validación: ${msgs}`);
            }

            if (res.status === 413) {
                throw new Error('La imagen es demasiado grande');
            }

            if (res.status === 500) {
                throw new Error('Error interno del servidor');
            }

            if (!res.ok) {
                throw new Error(`Error HTTP: ${res.status} ${res.statusText}`);
            }

            const responseText = await res.text();
            let saved;
            
            try {
                saved = JSON.parse(responseText);
            } catch (e) {
                console.error('Respuesta no es JSON:', responseText);
                throw new Error('Respuesta inválida del servidor');
            }

            // Verificar que la respuesta tenga los datos esperados
            if (!saved) {
                throw new Error('Respuesta vacía del servidor');
            }

            // Procesar respuesta exitosa
            await this.procesarRespuestaExitosa(saved, nombre, descripcion, semestreId);

        } catch (err) {
            console.error('Error completo:', err);
            this.mostrarAlerta('Error', err.message || 'No se pudo guardar la actividad. Verifica la conexión.', 'error');
        } finally {
            // Restaurar botón
            if (btnGuardar) {
                btnGuardar.textContent = originalText;
                btnGuardar.disabled = false;
            }
        }
    }

    obtenerUrlListado() {
        const configs = [window.ActUH, window.ActDV, window.ActSMT, window.ActVE];
        for (const config of configs) {
            if (config && config.rutas && config.rutas.list) {
                return config.rutas.list;
            }
        }
        return '/administrador/actividades';
    }

    // nueva función: carga actividades desde servidor para la unidad actual
    async cargarActividadesDesdeServidor() {
        const cont = document.getElementById('actividades-container') || document.querySelector('.actividades-container');
        if (!cont) {
            console.warn('Contenedor de actividades no encontrado');
            return;
        }
        
        const loadingHtml = '<div class="loading-actividades" style="color:#666; text-align:center; padding:2rem;">Cargando actividades...</div>';
        cont.innerHTML = loadingHtml;
        
        try {
            const unidadId = this.obtenerUnidadId();
            const semestreId = this.obtenerSemestreId();
            
            let url = this.obtenerUrlListado();
            const params = new URLSearchParams();
            
            if (unidadId) params.append('id_unidad', unidadId);
            if (semestreId) params.append('id_semestre', semestreId);
            
            if (params.toString()) {
                url += (url.includes('?') ? '&' : '?') + params.toString();
            }

            console.log('Cargando actividades desde:', url); // Debug

            const res = await fetch(url, { 
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) {
                throw new Error(`Error ${res.status} al cargar actividades`);
            }

            const items = await res.json();
            
            if (!Array.isArray(items)) {
                throw new Error('Formato de respuesta inválido');
            }

            // Limpiar y reconstruir contenedor
            const addModule = cont.querySelector('.modulo.agregar');
            cont.innerHTML = '';
            
            if (items.length === 0) {
                cont.innerHTML = '<div class="no-actividades" style="color:#999; text-align:center; padding:2rem; grid-column:1/-1;">No hay actividades registradas</div>';
            } else {
                items.forEach(item => {
                    const imagenUrl = item.imagen_url ?? item.imagen ?? item.imagenUrl ?? null;
                    const mdl = this.crearModuloActividad({
                        id: item.id_actividad ?? item.id ?? item.idActividad,
                        nombre: item.nombre_actividad ?? item.nombre,
                        descripcion: item.descripcion ?? item.desc,
                        imagen: this.resolveImageSrc(imagenUrl),
                        id_semestre: item.id_semestre ?? item.idSemestre ?? null
                    });
                    cont.appendChild(mdl);
                });
            }
            
            // Volver a insertar módulo agregar si existía
            if (addModule) cont.appendChild(addModule);
            
        } catch (e) {
            console.error('Error cargando actividades:', e);
            cont.innerHTML = `<div class="error-actividades" style="color:#c33; text-align:center; padding:2rem; grid-column:1/-1;">
                Error cargando actividades: ${e.message}
            </div>`;
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

        const csrf = this.obtenerCsrfToken();
        const baseShow = (window.ActUH && window.ActUH.rutas && window.ActUH.rutas.showBase) ? window.ActUH.rutas.showBase : '/administrador/actividades';
        const urlDel = baseShow.replace(/\/+$/,'') + '/' + encodeURIComponent(id);

        try {
            // intentar DELETE directo
            let res = await fetch(urlDel, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}) 
                },
                credentials: 'same-origin'
            });

            // si DELETE directo no fue aceptado (p. ej. 405/403), intentar fallback POST + _method=DELETE
            if (!res.ok) {
                const fd = new FormData();
                fd.append('_method', 'DELETE');
                res = await fetch(urlDel, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                    },
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

// Exportar instancia global mejorada
document.addEventListener('DOMContentLoaded', function() {
    try {
        window.gestorActividades = new GestorActividades();
        console.log('Gestor de actividades inicializado correctamente');
    } catch (error) {
        console.error('Error inicializando gestor de actividades:', error);
    }
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