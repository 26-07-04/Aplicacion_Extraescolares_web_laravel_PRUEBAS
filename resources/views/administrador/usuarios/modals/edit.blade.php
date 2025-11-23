<div id="modalEditarUsuario" class="modal" style="display:none; position: fixed; z-index: 999; left:0; top:0; width:100%; height:100%; background: rgba(0,0,0,0.6);">
  <div class="modal-dialog" style="background:#fff; width:40%; margin:auto; padding:28px; border-radius:10px; position: absolute; left:50%; top:55%; transform: translate(-50%,-50%); max-height:85vh; overflow-y:auto; box-sizing:border-box;">
    <div style="display:flex; align-items:center; justify-content:center; gap:10px; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid rgba(27,57,106,0.18); width:100%;">
      <i class="fas fa-user-edit" style="color:#1B396A; font-size:1.3em; line-height:1; vertical-align:middle;"></i>
      <h3 style="margin:0; color:#1B396A; font-weight:700; font-size:1.15em;">Editar Usuario</h3>
    </div>
    <form id="formEditarUsuario" action="#" method="POST">
      @csrf
      <input type="hidden" name="_method" value="PUT" />
      <input type="hidden" id="edit_id" name="id" />
      <input type="hidden" id="edit_id" />
      <div style="margin-bottom:16px;"><label style="font-weight:700; display:block; margin-bottom:6px;">Nombre</label><input type="text" id="edit_nombre" name="nombreUsuario" class="form-control" style="width:100%;padding:10px;" /><div id="edit_error_nombre" style="color:#d33;display:none;font-size:13px;"></div></div>
      <div style="margin-bottom:16px;"><label style="font-weight:700; display:block; margin-bottom:6px;">Contraseña</label>
        <div style="position:relative;">
          <input type="password" id="edit_password" name="passwordUsuario" class="form-control" style="width:100%;padding:10px;padding-right:48px;box-sizing:border-box;" />
          <button type="button" id="edit_toggle_password" aria-label="Mostrar contraseña" title="Mostrar contraseña" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; padding:2px; width:30px; height:30px; z-index:3;">
            <i class="fas fa-eye" id="edit_toggle_icon" style="color:#666; font-size:14px;"></i>
          </button>
        </div>
        <div id="edit_error_password" style="color:#d33;display:none;font-size:13px;"></div>
      </div>
      <div style="margin-bottom:16px;"><label style="font-weight:700; display:block; margin-bottom:6px;">Unidad Académica</label>
        <select id="edit_unidad" name="unidadUsuario" class="form-control" style="width:100%;padding:8px;">
          <option value="">Seleccione una opción</option>
          <option value="CIDERS unión Hidalgo">CIDERS unión Hidalgo</option>
          <option value="Unidad Demetrio Vallejo en el Espinal">Unidad Demetrio Vallejo en el Espinal</option>
          <option value="Unidad académica Tlahuitoltepec">Unidad académica Tlahuitoltepec</option>
          <option value="Valle de Etla">Valle de Etla</option>
        </select>
        <div id="edit_error_unidad" style="color:#d33;display:none;font-size:13px;"></div>
      </div>
      <div style="margin-bottom:16px;"><label style="font-weight:700; display:block; margin-bottom:6px;">Contacto (correo o teléfono)</label><input type="text" id="edit_contacto" name="contactoUsuario" class="form-control" style="width:100%;padding:10px;" /><div id="edit_error_contacto" style="color:#d33;display:none;font-size:13px;"></div></div>
      <div style="margin-bottom:16px;"><label style="font-weight:700; display:block; margin-bottom:6px;">Rol</label>
        <select id="edit_rol" name="rolUsuario" class="form-control" style="width:100%;padding:8px;">
          <option value="">Seleccione una opción</option>
          <option value="Administrador">Administrador</option>
          <option value="Coordinador">Coordinador</option>
        </select>
        <div id="edit_error_rol" style="color:#d33;display:none;font-size:13px;"></div>
      </div>
      <div style="text-align:center;margin-top:14px;">
        <button type="submit" class="btn btn-warning" style="background:#1B396A;color:#fff;padding:10px 16px;border:none;border-radius:6px;">Guardar</button>
        <button type="button" id="edit_cancel" class="btn btn-secondary" style="background:#ccc;color:#000;padding:8px 14px;border:none;border-radius:6px;margin-left:8px;">Cancelar</button>
      </div>
    </form>
  </div>
</div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var btn = document.getElementById('edit_toggle_password');
      var input = document.getElementById('edit_password');
      var icon = document.getElementById('edit_toggle_icon');
      if (btn && input && icon) {
        btn.addEventListener('click', function () {
          if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            btn.setAttribute('aria-label', 'Ocultar contraseña');
            btn.setAttribute('title', 'Ocultar contraseña');
          } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            btn.setAttribute('aria-label', 'Mostrar contraseña');
            btn.setAttribute('title', 'Mostrar contraseña');
          }
        });
      }
    });
  </script>