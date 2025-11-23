<div class="gestion-usuarios" style="margin-top: 20px;">
  <h1 class="titulo-recuadro" style="font-family: 'Segoe UI', sans-serif; color: #1B396A; font-size: 24px; text-align: center;">G E S T I Ó N &nbsp; D E &nbsp; U S U A R I O S</h1>
  <p class="mb-4" style="font-family: 'Segoe UI', sans-serif; color: #555; font-size: 16px; text-align: center; margin-top: 10px;">Administra de forma sencilla la información de los usuarios. Agrega, edita o elimina perfiles, y controla los accesos al sistema de manera eficiente y segura.</p>

  <div class="card shadow mb-4" style="margin: 0 auto; max-width: 90%;">
    <div class="card-header py-3 encabezado-con-boton" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B396A; color: white; padding: 10px; border-radius: 5px;">
      <h6 class="m-0 font-weight-bold text-primary" style="font-family: 'Segoe UI', sans-serif; font-size: 18px; color: white;">Tabla de datos</h6>
      <button class="btn btn-personalizado btn-sm btn-agregar-usuario" id="btnAgregarUsuario" style="background-color: #ff7f00; color: white; padding: 5px 10px; border: none; border-radius: 5px; font-size: 14px; cursor: pointer;">
        <i class="fas fa-user-plus"></i> Agregar
      </button>
    </div>

    <div class="card-body" style="padding: 15px; background-color: white;">
      <!-- Añadido nuevo campo de búsqueda para usuarios -->
      <div style="margin-bottom: 15px;">
        <input type="text" id="busquedaUsuarios" placeholder="Buscar usuario..." style="width: 50%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;" onkeyup="filtrarUsuarios()">
      </div>
      
      <div class="table-responsive" style="overflow-x: auto;">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-family: 'Segoe UI', sans-serif; font-size: 14px;">
          <thead>
            <tr style="background-color: #1B396A; color: white;">
              <th>Nombre</th>
              <th>Contacto</th>
              <th>Contraseña</th>
              <th>Unidad Académica</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaUsuarios">
            @if(isset($usuarios) && $usuarios->isNotEmpty())
              @foreach($usuarios as $u)
                <tr class="fila-usuario">
                  <td>{{ $u->nombre }}</td>
                  <td>{{ $u->contacto }}</td>
                  <td>{{ $u->contrasena_texto ?? '—' }}</td>
                  <td>{{ $u->unidad_academica ?? '—' }}</td>
                  <td>{{ $u->rol ?? '—' }}</td>
                  <td>
                    <a href="#" class="btn-editar-usuario" data-id="{{ $u->id_usuario }}" style="margin-right:8px; color:#1B396A;"><i class="fas fa-edit"></i></a>
                    <a href="#" class="btn-eliminar-usuario" data-id="{{ $u->id_usuario }}" style="color:#dc3545;"><i class="fas fa-trash"></i></a>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color:#666;">No hay usuarios registrados.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function filtrarUsuarios() {
    const term = document.getElementById('busquedaUsuarios').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tablaUsuarios .fila-usuario');
    filas.forEach(fila => {
      const texto = fila.textContent.toLowerCase();
      fila.style.display = texto.indexOf(term) !== -1 ? '' : 'none';
    });
  }

  // Placeholder: manejar click en agregar/editar/eliminar
  document.addEventListener('DOMContentLoaded', function () {
    const baseUsuariosUrl = '{{ url("admin/usuarios") }}';

    const btnAgregar = document.getElementById('btnAgregarUsuario');
    const modalAdd = document.getElementById('modalAgregarUsuario');
    const modalEdit = document.getElementById('modalEditarUsuario');
    const addCancel = document.getElementById('add_cancel');
    const editCancel = document.getElementById('edit_cancel');

    // Mostrar modal agregar
    if (btnAgregar) {
      btnAgregar.addEventListener('click', function (e) {
        e.preventDefault();
        if (modalAdd) modalAdd.style.display = 'block';
      });
    }

    // Cancel botones
    addCancel && addCancel.addEventListener('click', function () { if (modalAdd) modalAdd.style.display = 'none'; });
    editCancel && editCancel.addEventListener('click', function () { if (modalEdit) modalEdit.style.display = 'none'; });

    // Editar: abrir modal y rellenar campos
    document.querySelectorAll('.btn-editar-usuario').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.dataset.id;
        const fila = this.closest('tr');
        if (!fila) return;
        const nombre = fila.children[0].textContent.trim();
        const contacto = fila.children[1].textContent.trim();
        const contrasena_texto = fila.children[2].textContent.trim();
        const unidad = fila.children[3].textContent.trim();
        const rol = fila.children[4].textContent.trim();

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_contacto').value = contacto === '—' ? '' : contacto;
        document.getElementById('edit_unidad').value = unidad === '—' ? '' : unidad;
        document.getElementById('edit_rol').value = rol === '—' ? '' : rol;
        document.getElementById('edit_password').value = contrasena_texto === '—' ? '' : contrasena_texto;

        // Set action on form
        const formEdit = document.getElementById('formEditarUsuario');
        if (formEdit) formEdit.action = baseUsuariosUrl + '/' + id;

        if (modalEdit) modalEdit.style.display = 'block';
      });
    });

    // Eliminar: confirmar y enviar formulario oculto
    document.querySelectorAll('.btn-eliminar-usuario').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.dataset.id;
        const deleteForm = document.getElementById('formEliminarUsuario');
        Swal.fire({
          title: '¿Eliminar usuario?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then(result => {
          if (result.isConfirmed) {
            if (deleteForm) {
              deleteForm.action = baseUsuariosUrl + '/' + id;
              deleteForm.submit();
            }
          }
        });
      });
    });
  });
</script>

<!-- Formulario oculto para eliminar usuario -->
<form id="formEliminarUsuario" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

@includeIf('administrador.usuarios.modals.create')
@includeIf('administrador.usuarios.modals.edit')

<style>
  /* Alinear y unificar tamaño de inputs/selects en los modales de usuarios */
  #modalAgregarUsuario .form-control,
  #modalEditarUsuario .form-control {
    width: 100% !important;
    box-sizing: border-box !important;
    padding: 8px !important;
    max-width: 100% !important;
  }
  /* Asegurar que selects también respeten el ancho */
  #modalAgregarUsuario select.form-control,
  #modalEditarUsuario select.form-control {
    appearance: auto;
  }
</style>

@if($errors->any() && old('_form') === 'create')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      try {
        var modalAdd = document.getElementById('modalAgregarUsuario');
        if (modalAdd) modalAdd.style.display = 'block';
      } catch (e) { console.error(e); }
    });
  </script>
@endif
<style>
  /* Reducir tamaño del título en SweetAlert2 para mensajes de éxito */
  .swal-small-title {
    font-size: 16px !important;
    font-weight: 600 !important;
    line-height: 1.2 !important;
  }
</style>

@if(session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      try {
        Swal.fire({
          title: '{{ addslashes(session('success')) }}',
          icon: 'success',
          draggable: true,
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true,
          customClass: {
            title: 'swal-small-title'
          }
        });
      } catch (e) { console.error(e); }
    });
  </script>
@endif
