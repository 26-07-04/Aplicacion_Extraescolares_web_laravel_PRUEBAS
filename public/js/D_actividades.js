// Gestión del detalle de actividad (Union Hidalgo) - D_actividades_UH
(function () {
  if (!window.DActividadesUH) window.DActividadesUH = {};
  const cfg = window.DActividadesUH;
  const idActividad = cfg.id_actividad || new URLSearchParams(window.location.search).get('id_actividad') || '';

  // Mixin SweetAlert2 con clases personalizadas (coincide con CSS)
  const SwalCustom = (typeof Swal !== 'undefined') ? Swal.mixin({
    customClass: {
      popup: 'swal-custom-popup',
      title: 'swal-custom-title',
      content: 'swal-custom-content',
      confirmButton: 'swal-btn-confirm',
      cancelButton: 'swal-btn-cancel'
    },
    buttonsStyling: false
  }) : null;

  function obtenerJSON(url) { return fetch(url).then(r => r.json()); }

  function cargarActividad() {
    if (!cfg.endpoints || !cfg.endpoints.obtenerActividad) return;
    obtenerJSON(cfg.endpoints.obtenerActividad + '?id_actividad=' + encodeURIComponent(idActividad))
      .then(act => {
        if (!act) return;
        const nombre = act.nombre_actividad || act.nombre || '';
        const desc = act.descripcion || act.descripcion_actividad || '';
        document.getElementById('nombre-actividad').textContent = nombre || document.getElementById('nombre-actividad').textContent;
        document.getElementById('descripcion-actividad').textContent = desc || document.getElementById('descripcion-actividad').textContent;
      }).catch(()=>{/* silent */});
  }

  function cargarEstudiantes() {
    const endpoint = cfg.endpoints && cfg.endpoints.obtenerAlumnos;
    if (!endpoint) return;
    obtenerJSON(endpoint + '?id_actividad=' + encodeURIComponent(idActividad))
      .then(alumnos => {
        const tbody = document.getElementById('tabla-estudiantes');
        tbody.innerHTML = '';
        if (!alumnos || alumnos.length === 0) {
          tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:20px;color:#666">No hay estudiantes registrados en esta actividad</td></tr>`;
          return;
        }
        alumnos.forEach(al => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${escapeHtml(al.nombre)}</td>
            <td>${escapeHtml(al.numero_control)}</td>
            <td>${escapeHtml(al.carrera)}</td>
            <td>${escapeHtml(al.semestre)}</td>
            <td class="acciones-celda">
              <button class="btn-accion btn-editar" title="Editar" data-id="${al.id_alumno}" data-nombre="${htmlAttr(al.nombre)}" data-numero_control="${htmlAttr(al.numero_control)}" data-carrera="${htmlAttr(al.carrera)}" data-semestre="${htmlAttr(al.semestre)}">
                <i class="bi bi-pen-fill" style="color:#002147; font-size:1.15em;"></i>
              </button>
              <button class="btn-accion btn-eliminar" title="Eliminar" data-id="${al.id_alumno}">
                <i class="bi bi-trash-fill" style="color:#c33; font-size:1.15em;"></i>
              </button>
            </td>
          `;
          tbody.appendChild(tr);
        });
        attachRowEvents();
      }).catch(()=>{/* silent */});
  }

  function attachRowEvents() {
    document.querySelectorAll('.btn-editar').forEach(btn => {
      btn.onclick = function () {
        document.getElementById('editar-id').value = this.dataset.id || '';
        document.getElementById('editar-nombre').value = this.dataset.nombre || '';
        document.getElementById('editar-numero-control').value = this.dataset.numero_control || '';
        document.getElementById('editar-carrera').value = this.dataset.carrera || '';
        document.getElementById('editar-semestre').value = this.dataset.semestre || '';
        document.getElementById('modal-editar').style.display = 'flex';
      };
    });

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
      btn.onclick = function () {
        const idAlumno = this.dataset.id;
        (SwalCustom || Swal).fire({
          title: '¿Estás seguro?',
          text: "Se eliminará este estudiante",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            const endpoint = (cfg.endpoints && cfg.endpoints.eliminarAlumno) || cfg.endpoints && cfg.endpoints.obtenerAlumnos;
            fetch(endpoint, {
              method: 'POST',
              headers: {'Content-Type':'application/x-www-form-urlencoded'},
              body: `id_alumno=${encodeURIComponent(idAlumno)}&id_actividad=${encodeURIComponent(idActividad)}`
            }).then(r => r.json()).then(resp => {
              if (resp && resp.success) {
                (SwalCustom || Swal).fire('Eliminado','El estudiante ha sido eliminado.','success');
                cargarEstudiantes();
              } else {
                (SwalCustom || Swal).fire('Error','No se pudo eliminar el estudiante.','error');
              }
            }).catch(()=>Swal.fire('Error','No se pudo conectar con el servidor.','error'));
          }
        });
      };
    });
  }

  // Formulario editar / crear
  function initForm() {
    const form = document.getElementById('form-editar-estudiante');
    if (!form) return;
    form.onsubmit = function (e) {
      e.preventDefault();
      const id = document.getElementById('editar-id').value;
      const nombre = document.getElementById('editar-nombre').value;
      const numero_control = document.getElementById('editar-numero-control').value;
      const carrera = document.getElementById('editar-carrera').value;
      const semestre = document.getElementById('editar-semestre').value;

      const endpoint = id ? (cfg.endpoints && cfg.endpoints.editarAlumno) : (cfg.endpoints && cfg.endpoints.guardarAlumno);
      if (!endpoint) return Swal.fire('Error','No hay endpoint configurado.','error');

      const body = `id_alumno=${encodeURIComponent(id)}&nombre=${encodeURIComponent(nombre)}&numero_control=${encodeURIComponent(numero_control)}&carrera=${encodeURIComponent(carrera)}&semestre=${encodeURIComponent(semestre)}&id_actividad=${encodeURIComponent(idActividad)}`;

      fetch(endpoint, {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body
      }).then(r => r.json()).then(resp => {
        if (resp && resp.success) {
          Swal.fire('Listo','Datos guardados.','success');
          document.getElementById('modal-editar').style.display = 'none';
          cargarEstudiantes();
        } else {
          Swal.fire('Error','No se pudo guardar la información.','error');
        }
      }).catch(()=>Swal.fire('Error','No se pudo conectar con el servidor.','error'));
    };

    document.getElementById('cerrar-modal-editar').onclick = function () {
      document.getElementById('modal-editar').style.display = 'none';
    };
    document.getElementById('btn-cancelar-edicion').onclick = function () {
      document.getElementById('modal-editar').style.display = 'none';
    };

    // agregar estudiante (abre modal en modo crear)
    const btnAgregar = document.getElementById('btn-agregar-estudiante');
    if (btnAgregar) {
      btnAgregar.onclick = function () {
        document.getElementById('modal-titulo-editar').textContent = 'Agregar Estudiante';
        document.getElementById('editar-id').value = '';
        document.getElementById('editar-nombre').value = '';
        document.getElementById('editar-numero-control').value = '';
        document.getElementById('editar-carrera').value = '';
        document.getElementById('editar-semestre').value = '';
        document.getElementById('modal-editar').style.display = 'flex';
      };
    }

    // cerrar modal si se clickea fuera
    window.addEventListener('click', function (e) {
      const modal = document.getElementById('modal-editar');
      if (e.target === modal) modal.style.display = 'none';
    });
  }

  // init
  document.addEventListener('DOMContentLoaded', function () {
    cargarActividad();
    initForm && initForm();
    cargarEstudiantes();
  });

  // utilitarios
  function escapeHtml(text) { if (text === null || text === undefined) return ''; return String(text).replace(/[&<>"'`=\/]/g, function (s) { return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'})[s]; }); }
  function htmlAttr(text){ return (text||'').replace(/"/g,'&quot;'); }
})();
// --- ADICIÓN: manejo delegado de editar/eliminar y formulario del modal ---
(function () {
  // Abrir modal y poblar campos desde botones con class="btn-editar"
  function abrirModalConDatos(btn) {
    const id = btn.dataset.id || '';
    const nombre = btn.dataset.nombre || '';
    const numero = btn.dataset.numero_control || btn.dataset.numero || '';
    const carrera = btn.dataset.carrera || '';
    const semestre = btn.dataset.semestre || '';

    const modal = document.getElementById('modal-editar');
    if (!modal) return;
    document.getElementById('editar-id').value = id;
    document.getElementById('editar-nombre').value = nombre;
    document.getElementById('editar-numero-control').value = numero;
    document.getElementById('editar-carrera').value = carrera;
    document.getElementById('editar-semestre').value = semestre;
    modal.style.display = 'flex';
  }

  // Cerrar modal
  function cerrarModal() {
    const modal = document.getElementById('modal-editar');
    if (modal) modal.style.display = 'none';
  }

  // Delegated click para botones editar / eliminar
  document.addEventListener('click', function (e) {
    const editar = e.target.closest && e.target.closest('.btn-editar');
    if (editar) {
      e.preventDefault();
      abrirModalConDatos(editar);
      return;
    }

    const eliminar = e.target.closest && e.target.closest('.btn-eliminar');
    if (eliminar) {
      e.preventDefault();
      const idAlumno = eliminar.dataset.id;
      const idActividad = (window.DActividadesUH && window.DActividadesUH.id_actividad) || new URLSearchParams(window.location.search).get('id_actividad') || '';
      Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará este estudiante',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((res) => {
        if (!res.isConfirmed) return;
        // Llamada al endpoint de eliminación si existe
        const ep = window.DActividadesUH && window.DActividadesUH.endpoints && window.DActividadesUH.endpoints.eliminarAlumno;
        if (ep) {
          fetch(ep, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id_alumno=${encodeURIComponent(idAlumno)}&id_actividad=${encodeURIComponent(idActividad)}`
          })
          .then(r => r.json())
          .then(json => {
            if (json && json.success) {
              Swal.fire('Eliminado','El estudiante ha sido eliminado.','success');
              // eliminar fila del DOM
              const fila = eliminar.closest('tr');
              if (fila) fila.remove();
            } else {
              Swal.fire('Error','No se pudo eliminar el estudiante.','error');
            }
          })
          .catch(()=> Swal.fire('Error','No se pudo conectar al servidor.','error'));
        } else {
          // Si no hay endpoint, sólo eliminar fila (modo demo)
          const fila = eliminar.closest('tr');
          if (fila) fila.remove();
          Swal.fire('Eliminado','Fila eliminada (demo).','success');
        }
      });
      return;
    }

    // Cerrar modal con botones de cancelar o cerrar
    if (e.target.matches('#cerrar-modal-editar') || e.target.matches('#btn-cancelar-edicion')) {
      e.preventDefault();
      cerrarModal();
      return;
    }
  }, false);

  // Manejo del formulario de edición (guardar)
  const form = document.getElementById('form-editar-estudiante');
  if (form) {
    form.addEventListener('submit', function (evt) {
      evt.preventDefault();
      const id = document.getElementById('editar-id').value;
      const nombre = document.getElementById('editar-nombre').value;
      const numero_control = document.getElementById('editar-numero-control').value;
      const carrera = document.getElementById('editar-carrera').value;
      const semestre = document.getElementById('editar-semestre').value;
      const idActividad = (window.DActividadesUH && window.DActividadesUH.id_actividad) || new URLSearchParams(window.location.search).get('id_actividad') || '';

      const ep = window.DActividadesUH && window.DActividadesUH.endpoints && window.DActividadesUH.endpoints.editarAlumno;
      if (ep) {
        fetch(ep, {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `id_alumno=${encodeURIComponent(id)}&nombre=${encodeURIComponent(nombre)}&numero_control=${encodeURIComponent(numero_control)}&carrera=${encodeURIComponent(carrera)}&semestre=${encodeURIComponent(semestre)}&id_actividad=${encodeURIComponent(idActividad)}`
        })
        .then(r => r.json())
        .then(json => {
          if (json && json.success) {
            Swal.fire('Actualizado','Los datos del estudiante han sido actualizados.','success');
            cerrarModal();
            // actualizar fila si existe en DOM (demo)
            const fila = document.querySelector(`button.btn-editar[data-id="${id}"]`)?.closest('tr');
            if (fila) {
              fila.children[0].textContent = nombre;
              fila.children[1].textContent = numero_control;
              fila.children[2].textContent = carrera;
              fila.children[3].textContent = semestre;
            }
          } else {
            Swal.fire('Error','No se pudo actualizar el estudiante.','error');
          }
        })
        .catch(()=> Swal.fire('Error','No se pudo conectar al servidor.','error'));
      } else {
        // Modo demo: actualizar fila y cerrar
        const fila = document.querySelector(`button.btn-editar[data-id="${id}"]`)?.closest('tr');
        if (fila) {
          fila.children[0].textContent = nombre;
          fila.children[1].textContent = numero_control;
          fila.children[2].textContent = carrera;
          fila.children[3].textContent = semestre;
        }
        cerrarModal();
        Swal.fire('Actualizado','Datos actualizados (demo).','success');
      }
    });
  }

  // Cerrar modal al hacer clic fuera del contenido (ya existe en tu vista, pero aseguramos)
  window.addEventListener('click', function(event) {
    const modal = document.getElementById('modal-editar');
    if (modal && event.target === modal) modal.style.display = 'none';
  });
})();