// Gestión del detalle de actividad (Union Hidalgo) - D_actividades_UH
(function () {
  if (!window.DActividadesUH) window.DActividadesUH = {};
  const cfg = window.DActividadesUH;
  const actividadId = String(cfg.id_actividad || new URLSearchParams(window.location.search).get('id_actividad') || '').trim();
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // helpers para endpoints por defecto si no están en cfg.endpoints
  function ep(name, id) {
    const e = (cfg.endpoints && cfg.endpoints[name]) || '';
    if (e) return e;
    switch (name) {
      case 'obtenerActividad': return `/administrador/actividades/${encodeURIComponent(actividadId)}`;
      case 'obtenerAlumnos': return `/administrador/actividades/${encodeURIComponent(actividadId)}/estudiantes`;
      case 'guardarAlumno': return `/administrador/actividades/${encodeURIComponent(actividadId)}/estudiantes`;
      case 'editarAlumno': return id ? `/administrador/actividades/estudiantes/${encodeURIComponent(id)}` : `/administrador/actividades/estudiantes`;
      case 'eliminarAlumno': return id ? `/administrador/actividades/estudiantes/${encodeURIComponent(id)}` : `/administrador/actividades/estudiantes`;
      default: return '';
    }
  }

  // utilitarios
  function safeJsonResponse(res) {
    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) return res.json().catch(()=>null);
    return res.text().then(t => {
      try { return t ? JSON.parse(t) : null; } catch { return { message: t }; }
    }).catch(()=>null);
  }
  function escapeHtml(text) { if (text === null || text === undefined) return ''; return String(text).replace(/[&<>"'`=\/]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s])); }
  function htmlAttr(text){ return (text||'').replace(/"/g,'&quot;'); }

  // carga actividad (nombre/desc)
  function cargarActividad() {
    const url = ep('obtenerActividad');
    if (!url) return;
    fetch(url, { credentials: 'same-origin', headers: { 'Accept':'application/json' } })
      .then(r => safeJsonResponse(r))
      .then(act => {
        if (!act) return;
        const actividad = act.data ?? act;
        const nombre = actividad.nombre_actividad || actividad.nombre || '';
        const desc = actividad.descripcion || actividad.descripcion_actividad || '';
        const elN = document.getElementById('nombre-actividad');
        const elD = document.getElementById('descripcion-actividad');
        if (elN && nombre) elN.textContent = nombre;
        if (elD && desc) elD.textContent = desc;
      }).catch(()=>{/* silent */});
  }

  // cargar estudiantes desde la API Laravel
  function cargarEstudiantes() {
    const url = ep('obtenerAlumnos');
    const tbody = document.getElementById('tabla-estudiantes');
    if (!url || !tbody) return;
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:18px;color:#666">Cargando...</td></tr>';
    fetch(url, { credentials: 'same-origin', headers: { 'Accept':'application/json' } })
      .then(r => safeJsonResponse(r).then(data => ({ ok: r.ok, status: r.status, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:18px;color:#c33">No se pudo cargar la lista</td></tr>';
          return;
        }
        const items = Array.isArray(data) ? data : (data?.data || data);
        if (!items || items.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;color:#666">No hay estudiantes registrados en esta actividad</td></tr>';
          return;
        }
        tbody.innerHTML = '';
        items.forEach(al => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${escapeHtml(al.nombre ?? '')}</td>
            <td>${escapeHtml(al.numero_control ?? '')}</td>
            <td>${escapeHtml(al.carrera ?? '')}</td>
            <td>${escapeHtml(al.semestre ?? '')}</td>
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
      }).catch((err)=>{
        console.error('Error cargarEstudiantes', err);
        const tbody = document.getElementById('tabla-estudiantes');
        if (tbody) tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:18px;color:#c33">Error cargando estudiantes</td></tr>';
      });
  }

  // eliminar alumno: llama DELETE a /administrador/actividades/estudiantes/{id}
  async function eliminarAlumno(idAlumno) {
    const url = ep('eliminarAlumno', idAlumno);
    if (!url) throw new Error('Endpoint eliminar no configurado');
    const urlWithQuery = `${url}?id_actividad=${encodeURIComponent(actividadId)}`;
    const res = await fetch(urlWithQuery, {
      method: 'DELETE',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    });
    const data = await safeJsonResponse(res);
    if (!res.ok) {
      const msg = data?.message || `Status ${res.status}`;
      throw new Error(msg);
    }
    return data;
  }

  // actualizar alumno: POST _method=PUT a /administrador/actividades/estudiantes/{id}
  async function actualizarAlumno(idAlumno, payloadObj) {
    const url = ep('editarAlumno', idAlumno);
    if (!url) throw new Error('Endpoint editar no configurado');
    const fd = new FormData();
    Object.keys(payloadObj || {}).forEach(k => fd.append(k, payloadObj[k]));
    fd.append('_method','PUT');
    fd.append('id_actividad', actividadId); // para validación en servidor
    const res = await fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept':'application/json' },
      body: fd
    });
    const data = await safeJsonResponse(res);
    if (!res.ok) {
      const msg = data?.message || `Status ${res.status}`;
      throw new Error(msg);
    }
    return data;
  }

  // crear nuevo alumno: POST a /administrador/actividades/{actividad}/estudiantes
  async function crearAlumno(payloadObj) {
    const url = ep('guardarAlumno');
    if (!url) throw new Error('Endpoint crear no configurado');
    const fd = new FormData();
    Object.keys(payloadObj || {}).forEach(k => fd.append(k, payloadObj[k]));
    fd.append('id_actividad', actividadId);
    const res = await fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept':'application/json' },
      body: fd
    });
    const data = await safeJsonResponse(res);
    if (!res.ok) {
      const msg = data?.message || `Status ${res.status}`;
      throw new Error(msg);
    }
    return data;
  }

  // Delegated events: editar, eliminar
  document.addEventListener('click', function (e) {
    const btn = e.target.closest && e.target.closest('.btn-editar');
    if (btn) {
      e.preventDefault();
      const id = btn.dataset.id || '';
      document.getElementById('editar-id').value = id;
      document.getElementById('editar-nombre').value = btn.getAttribute('data-nombre') || '';
      document.getElementById('editar-numero-control').value = btn.getAttribute('data-numero_control') || '';
      document.getElementById('editar-carrera').value = btn.getAttribute('data-carrera') || '';
      document.getElementById('editar-semestre').value = btn.getAttribute('data-semestre') || '';
      const modal = document.getElementById('modal-editar');
      if (modal) modal.style.display = 'flex';
      return;
    }

    const btnDel = e.target.closest && e.target.closest('.btn-eliminar');
    if (btnDel) {
      e.preventDefault();
      const idAlumno = btnDel.dataset.id;
      Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará este estudiante',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(async (res) => {
        if (!res.isConfirmed) return;
        try {
          await eliminarAlumno(idAlumno);
          // eliminar fila en DOM y recargar la lista
          const fila = btnDel.closest('tr');
          if (fila) fila.remove();
          // recargar para mayor consistencia
          cargarEstudiantes();
          Swal.fire({ position: 'center', icon: 'success', title: 'Estudiante eliminado correctamente', showConfirmButton: false, timer: 1500 });
        } catch (err) {
          console.error('Error eliminarAlumno:', err);
          Swal.fire({ position: 'center', icon: 'error', title: 'No se pudo eliminar', text: err.message || '' });
        }
      });
      return;
    }

    // cerrar modal
    if (e.target.matches('#cerrar-modal-editar') || e.target.matches('#btn-cancelar-edicion')) {
      e.preventDefault();
      const modal = document.getElementById('modal-editar');
      if (modal) modal.style.display = 'none';
      return;
    }
  }, false);

  // formulario: crear o editar
  const form = document.getElementById('form-editar-estudiante');
  if (form) {
    form.addEventListener('submit', async function (evt) {
      evt.preventDefault();
      const id = document.getElementById('editar-id').value || '';
      const payload = {
        nombre: document.getElementById('editar-nombre').value || '',
        numero_control: document.getElementById('editar-numero-control').value || '',
        carrera: document.getElementById('editar-carrera').value || '',
        semestre: document.getElementById('editar-semestre').value || ''
      };
      try {
        if (id) {
          await actualizarAlumno(id, payload);
          await cargarEstudiantes();
          const modal = document.getElementById('modal-editar'); if (modal) modal.style.display = 'none';
          Swal.fire({ position: 'center', icon: 'success', title: 'Datos actualizados', showConfirmButton: false, timer: 1500 });
        } else {
          await crearAlumno(payload);
          await cargarEstudiantes();
          const modal = document.getElementById('modal-editar'); if (modal) modal.style.display = 'none';
          Swal.fire({ position: 'center', icon: 'success', title: 'Estudiante agregado', showConfirmButton: false, timer: 1500 });
        }
      } catch (err) {
        console.error('Error guardar:', err);
        Swal.fire({ position: 'center', icon: 'error', title: 'No se pudo guardar', text: err.message || '' });
      }
    }, false);
  }

  // abrir modal para crear (si existe botón)
  const btnAgregar = document.getElementById('btn-agregar-estudiante');
  if (btnAgregar) {
    btnAgregar.addEventListener('click', function () {
      document.getElementById('modal-titulo-editar').textContent = 'Agregar Estudiante';
      document.getElementById('editar-id').value = '';
      document.getElementById('editar-nombre').value = '';
      document.getElementById('editar-numero-control').value = '';
      document.getElementById('editar-carrera').value = '';
      document.getElementById('editar-semestre').value = '';
      const modal = document.getElementById('modal-editar'); if (modal) modal.style.display = 'flex';
    }, false);
  }

  // cerrar modal si clic fuera
  window.addEventListener('click', function (e) {
    const modal = document.getElementById('modal-editar');
    if (modal && e.target === modal) modal.style.display = 'none';
  });

  // init
  document.addEventListener('DOMContentLoaded', function () {
    cargarActividad();
    cargarEstudiantes();
  });
})();