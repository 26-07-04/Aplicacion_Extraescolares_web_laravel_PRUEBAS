// Gestión de actividades 

class GestorActividades {
    constructor() {
        this.actividadEditando = null;
        this.contenedorActividadesActual = null;
        this.init();
    }

    init() {
        this.configurarEventos();
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
            document.getElementById('nombre-actividad').value = modulo.querySelector('h3').textContent;
            document.getElementById('descripcion-actividad').value = modulo.querySelector('p').textContent;
            if (vistaPrevia) {
                vistaPrevia.src = modulo.querySelector('img').src;
                vistaPrevia.style.display = 'block';
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

    guardarActividad() {
        const nombre = (document.getElementById('nombre-actividad') || {}).value;
        const descripcion = (document.getElementById('descripcion-actividad') || {}).value;

        if (!nombre || !descripcion) {
            this.mostrarAlerta('Error', 'Por favor completa todos los campos obligatorios', 'error');
            return;
        }

        if (this.actividadEditando) {
            // actualizar DOM del módulo editado
            this.actividadEditando.querySelector('h3').textContent = nombre;
            this.actividadEditando.querySelector('p').textContent = descripcion;
            this.mostrarAlerta('Éxito', `Actividad "${nombre}" actualizada correctamente`, 'success');
        } else {
            // crear nuevo módulo y añadir al contenedor
            const nueva = this.crearModuloActividad({
                id: Date.now(),
                nombre: nombre,
                descripcion: descripcion,
                imagen: document.getElementById('vista-previa')?.src || '/Imagenes/placeholder-actividad.jpg'
            });
            const cont = document.getElementById('actividades-container');
            const moduloAgregar = cont.querySelector('.modulo.agregar');
            if (moduloAgregar) cont.insertBefore(nueva, moduloAgregar);
            else cont.appendChild(nueva);
            this.mostrarAlerta('Éxito', `Actividad "${nombre}" creada correctamente`, 'success');
        }

        this.cerrarModalActividad();
    }

    editarActividad(modulo) {
        this.abrirModalActividad(modulo.closest('.actividades-container'), modulo);
    }

    eliminarActividad(modulo) {
        const nombreActividad = modulo.querySelector('h3').textContent;
        Swal.fire({
            title: '¿Eliminar actividad?',
            text: `¿Eliminar "${nombreActividad}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#fff',
            backdrop: 'rgba(0, 33, 71, 0.4)'
        }).then((result) => {
            if (result.isConfirmed) {
                modulo.remove();
                this.mostrarAlerta('Eliminado', 'La actividad ha sido eliminada correctamente', 'success');
            }
        });
    }

    // DATOS DE EJEMPLO: (DESACTIVADO) antes insertaba módulos estáticos
    cargarDatosEjemplo() {
        // función desactivada: ya no inserta actividades estáticas.
        // Si necesitas cargar desde servidor, implementa aquí fetch('/api/actividades') y usa crearModuloActividad para renderizar.
    }

    crearModuloActividad(actividad /* objeto con id, nombre, descripcion, etc */) {
        const modulo = document.createElement('div');
        modulo.classList.add('modulo','actividad');
        // Añadir dataset necesario para la redirección
        modulo.dataset.idActividad = actividad.id;
        modulo.dataset.nombre = actividad.nombre;
        modulo.dataset.descripcion = actividad.descripcion;

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
        modulo.querySelector('.btn-editar').addEventListener('click', (e) => {
            e.stopPropagation();
            this.editarActividad(modulo);
        });

        modulo.querySelector('.btn-eliminar').addEventListener('click', (e) => {
            e.stopPropagation();
            this.eliminarActividad(modulo);
        });

        // click en el módulo abre detalle (o redirección)
        modulo.addEventListener('click', () => {
            // comportamiento por defecto: ir a detalle (si exists) o no hacer nada
            // window.location.href = `/detalle-actividad?id=${actividad.id}`;
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