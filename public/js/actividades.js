// Gestión de actividades - Versión Optimizada para 4 Unidades
class GestorActividades {
    constructor() {
        this.actividadEditando = null;
        this.contenedorActividadesActual = null;
        this.init();
    }

    // helper: detectar la configuración activa (ActSMT, ActDV, ActUH, ActVE)
    getActConfig() {
        // Buscar en orden específico
        const keys = ['ActUH', 'ActDV', 'ActSMT', 'ActVE'];
        for (const k of keys) {
            if (window[k] && window[k].rutas) {
                console.log(`Usando configuración: ${k}`);
                return window[k];
            }
        }
        
        // Fallback: tomar cualquier window.Act* con rutas
        for (const p in window) {
            if (p.startsWith('Act') && window[p] && window[p].rutas) {
                console.log(`Usando configuración fallback: ${p}`);
                return window[p];
            }
        }
        
        console.warn('No se encontró configuración de actividades');
        return null;
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

        // Evitar abrir el selector dos veces
        let openingFileDialog = false;
        dropArea.addEventListener('click', (e) => {
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
        // PRIORIDAD 1: Configuración activa
        const config = this.getActConfig();
        if (config && config.unidadId) {
            console.log('ID unidad obtenido de configuración:', config.unidadId);
            return config.unidadId;
        }
        
        // PRIORIDAD 2: Campo oculto específico
        const unidadInput = document.getElementById('id-unidad-actividad');
        if (unidadInput && unidadInput.value) return unidadInput.value;
        
        // PRIORIDAD 3: Input genérico
        const unidadInputGen = document.getElementById('id_unidad');
        if (unidadInputGen && unidadInputGen.value) return unidadInputGen.value;
        
        // PRIORIDAD 4: Query string de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const unidadFromUrl = urlParams.get('unidad');
        if (unidadFromUrl) return this.convertirUnidadANumero(unidadFromUrl);
        
        // ÚLTIMO RECURSO: Determinar por URL
        const path = window.location.pathname.toLowerCase();
        if (path.includes('union-hidalgo') || path.includes('uh')) return 1;
        if (path.includes('demetrio') || path.includes('dv')) return 2;
        if (path.includes('tlahuitoltepec') || path.includes('smt')) return 3;
        if (path.includes('valle-etla') || path.includes('ve')) return 4;
        
        console.warn('No se pudo determinar ID de unidad, usando valor por defecto');
        return null;
    }

    // Función auxiliar para convertir nombre de unidad a número
    convertirUnidadANumero(unidadNombre) {
        const unidades = {
            'union hidalgo': 1,
            'unión hidalgo': 1,
            'uh': 1,
            'demetrio vallejo': 2,
            'demetrio': 2,
            'dv': 2,
            'tlahuitoltepec': 3,
            'smt': 3,
            'valle de etla': 4,
            'valle': 4,
            've': 4
        };
        
        const nombreLower = unidadNombre.toLowerCase().trim();
        return unidades[nombreLower] || null;
    }

    obtenerSemestreId() {
        // PRIORIDAD 1: Campo oculto específico para actividades
        const semestreInputActividad = document.getElementById('id-semestre-actividad');
        if (semestreInputActividad && semestreInputActividad.value) {
            console.log('ID semestre obtenido de campo oculto:', semestreInputActividad.value);
            return semestreInputActividad.value;
        }
        
        // PRIORIDAD 2: Configuración activa
        const config = this.getActConfig();
        if (config && config.semestreId) {
            console.log('ID semestre obtenido de configuración:', config.semestreId);
            return config.semestreId;
        }
        
        // PRIORIDAD 3: Query string de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const semestreFromUrl = urlParams.get('id_semestre');
        if (semestreFromUrl) {
            console.log('ID semestre obtenido de URL:', semestreFromUrl);
            return semestreFromUrl;
        }
        
        // PRIORIDAD 4: Input genérico
        const semestreInput = document.getElementById('id_semestre');
        if (semestreInput && semestreInput.value) {
            console.log('ID semestre obtenido de input genérico:', semestreInput.value);
            return semestreInput.value;
        }
        
        // ÚLTIMO RECURSO: Valor por defecto
        console.warn('No se pudo obtener ID de semestre, usando valor por defecto 1');
        return 1;
    }

    obtenerUrlGuardado() {
        const config = this.getActConfig();
        if (config && config.rutas && config.rutas.store) {
            return config.rutas.store;
        }
        
        console.warn('URL de guardado no encontrada en configuración, usando fallback');
        return '/administrador/actividades';
    }

    obtenerUrlEdicion(id) {
        const config = this.getActConfig();
        if (config && config.rutas && config.rutas.showBase) {
            return `${config.rutas.showBase}/${id}`;
        }
        
        console.warn('URL de edición no encontrada en configuración, usando fallback');
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

        // Obtener IDs de manera más robusta
        const unidadId = this.obtenerUnidadId();
        const semestreId = this.obtenerSemestreId();

        console.log('=== DATOS PARA CREAR ACTIVIDAD ===');
        console.log('Nombre:', nombre);
        console.log('Descripción:', descripcion);
        console.log('Unidad ID:', unidadId);
        console.log('Semestre ID:', semestreId);
        console.log('==============================');

        if (!unidadId || !semestreId) {
            this.mostrarAlerta('Error', 'No se pudo determinar la unidad o semestre. Recarga la página.', 'error');
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
            fd.append('id_unidad', unidadId);
            fd.append('id_semestre', semestreId);

            // Log para debug
            console.log('Enviando id_unidad:', unidadId);
            console.log('Enviando id_semestre:', semestreId);

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

            // Obtener CSRF token
            const csrf = this.obtenerCsrfToken();

            console.log('Enviando datos a:', url, 'Método:', method);

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
        const config = this.getActConfig();
        if (config && config.rutas && config.rutas.list) {
            return config.rutas.list;
        }
        
        console.warn('URL de listado no encontrada en configuración, usando fallback');
        return '/administrador/actividades';
    }

    // nueva función: carga actividades desde servidor para la unidad actual
    async cargarActividadesDesdeServidor() {
        const cont = document.getElementById('actividades-container') || document.querySelector('.actividades-container');
        if (!cont) {
            console.warn('Contenedor de actividades no encontrado');
            return;
        }
        
        // Mostrar loading
        const loadingHtml = '<div class="loading-actividades" style="color:#666; text-align:center; padding:2rem;"><i class="fas fa-spinner fa-spin"></i> Cargando actividades del semestre...</div>';
        cont.innerHTML = loadingHtml;
        
        try {
            const unidadId = this.obtenerUnidadId();
            const semestreId = this.obtenerSemestreId();
            
            console.log('=== SOLICITANDO ACTIVIDADES ===');
            console.log('Unidad ID:', unidadId);
            console.log('Semestre ID:', semestreId);
            console.log('==============================');
            
            let url = this.obtenerUrlListado();
            const params = new URLSearchParams();
            
            if (unidadId) params.append('id_unidad', unidadId);
            if (semestreId) params.append('id_semestre', semestreId);
            
            if (params.toString()) {
                url += (url.includes('?') ? '&' : '?') + params.toString();
            }

            console.log('URL de solicitud:', url);

            const res = await fetch(url, { 
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) {
                throw new Error(`Error ${res.status} al cargar actividades`);
            }

            const data = await res.json();
            
            console.log('Respuesta del servidor:', data);
            
            // Manejar diferentes estructuras de respuesta
            let items = [];
            if (Array.isArray(data)) {
                items = data; // Formato antiguo
            } else if (data && data.actividades) {
                items = data.actividades; // Nuevo formato con metadata
                console.log('Actividades filtradas por semestre:', data.id_semestre_filtrado);
                console.log('Total actividades:', data.total);
            } else if (data && Array.isArray(data.data)) {
                items = data.data; // Formato paginado
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
        const config = this.getActConfig();
        const baseShow = config && config.rutas && config.rutas.showBase ? 
            config.rutas.showBase : '/administrador/actividades';
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

            // si DELETE directo no fue aceptado, intentar fallback POST + _method=DELETE
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

            // intentar parsear JSON
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

    crearModuloActividad(actividad) {
        const modulo = document.createElement('div');
        modulo.classList.add('modulo','actividad');
        
        // Añadir dataset necesario
        modulo.dataset.idActividad = actividad.id;
        modulo.dataset.nombre = actividad.nombre;
        modulo.dataset.descripcion = actividad.descripcion;
        if (actividad.id_semestre) modulo.dataset.idSemestre = actividad.id_semestre;
        
        // Obtener unidadId de la configuración activa
        const config = this.getActConfig();
        if (config && config.unidadId) {
            modulo.dataset.unidadId = config.unidadId;
        }

        // Estructura HTML del módulo
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

        // Eventos en botones
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

        // Click en el módulo abre detalle
        modulo.addEventListener('click', (e) => {
            const target = e.target;
            if (target.closest('.btn-accion')) return;

            // Obtener configuración activa
            const config = this.getActConfig();
            if (!config || !config.rutas || !config.rutas.detail) {
                console.warn('No se encontró ruta de detalle en configuración');
                return;
            }

            // Obtener IDs
            const idActividad = modulo.dataset.idActividad || actividad.id || null;
            const unidadId = modulo.dataset.unidadId || config.unidadId || null;
            const semestreId = modulo.dataset.idSemestre || config.semestreId || null;

            if (!idActividad) {
                console.warn('ID de actividad no encontrado');
                return;
            }

            // Construir URL
            const params = new URLSearchParams();
            params.append('id_actividad', idActividad);
            if (unidadId) params.append('id_unidad', unidadId);
            if (semestreId) params.append('id_semestre', semestreId);

            const sep = config.rutas.detail.includes('?') ? '&' : '?';
            window.location.href = config.rutas.detail + sep + params.toString();
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

// Exportar instancia global
document.addEventListener('DOMContentLoaded', function() {
    try {
        window.gestorActividades = new GestorActividades();
        console.log('Gestor de actividades inicializado correctamente');
        
        // Log para depuración
        const configs = ['ActUH', 'ActDV', 'ActSMT', 'ActVE'];
        configs.forEach(configName => {
            if (window[configName]) {
                console.log(`Configuración ${configName} encontrada:`, window[configName]);
            }
        });
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