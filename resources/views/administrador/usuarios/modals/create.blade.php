<div id="modalAgregarUsuario" class="modal" style="display:none; position: fixed; z-index: 999; left:0; top:0; width:100%; height:100%; background: rgba(0,0,0,0.6);">
  <div class="modal-dialog" style="background:#fff; width:36%; margin:auto; padding:20px; border-radius:10px; position: absolute; left:50%; top:50%; transform: translate(-50%,-50%);">
    <h3 style="text-align:center; color:#1B396A;">Agregar Usuario</h3>
    <form id="formAgregarUsuario" action="{{ route('admin.usuarios.store') }}" method="POST">
      @csrf
      <input type="hidden" name="_form" value="create" />
      <div style="margin-bottom:12px;"><label>Nombre</label>
        <input type="text" id="add_nombre" name="nombreUsuario" class="form-control" style="width:100%;padding:8px;" value="{{ old('nombreUsuario') }}" />
        @if($errors->has('nombreUsuario'))
          <div style="color:#d33;font-size:13px;">{{ $errors->first('nombreUsuario') }}</div>
        @endif
      </div>
      <div style="margin-bottom:12px;"><label>Contraseña</label>
        <input type="password" id="add_password" name="passwordUsuario" class="form-control" style="width:100%;padding:8px;" />
        @if($errors->has('passwordUsuario'))
          <div style="color:#d33;font-size:13px;">{{ $errors->first('passwordUsuario') }}</div>
        @endif
      </div>
      <div style="margin-bottom:12px;"><label>Unidad Académica</label>
        <select id="add_unidad" name="unidadUsuario" class="form-control" style="width:100%;padding:8px;">
          <option value="">Seleccione una opción</option>
          <option value="CIDERS unión Hidalgo" {{ old('unidadUsuario') == 'CIDERS unión Hidalgo' ? 'selected' : '' }}>CIDERS unión Hidalgo</option>
          <option value="Unidad Demetrio Vallejo en el Espinal" {{ old('unidadUsuario') == 'Unidad Demetrio Vallejo en el Espinal' ? 'selected' : '' }}>Unidad Demetrio Vallejo en el Espinal</option>
          <option value="Unidad académica Tlahuitoltepec" {{ old('unidadUsuario') == 'Unidad académica Tlahuitoltepec' ? 'selected' : '' }}>Unidad académica Tlahuitoltepec</option>
          <option value="Valle de Etla" {{ old('unidadUsuario') == 'Valle de Etla' ? 'selected' : '' }}>Valle de Etla</option>
        </select>
        @if($errors->has('unidadUsuario'))
          <div style="color:#d33;font-size:13px;">{{ $errors->first('unidadUsuario') }}</div>
        @endif
      </div>
      <div style="margin-bottom:12px;"><label>Contacto (correo o teléfono)</label>
        <input type="text" id="add_contacto" name="contactoUsuario" class="form-control" style="width:100%;padding:8px;" value="{{ old('contactoUsuario') }}" />
        @if($errors->has('contactoUsuario'))
          <div style="color:#d33;font-size:13px;">{{ $errors->first('contactoUsuario') }}</div>
        @endif
      </div>
      <div style="margin-bottom:12px;"><label>Rol</label>
        <select id="add_rol" name="rolUsuario" class="form-control" style="width:100%;padding:8px;">
          <option value="">Seleccione una opción</option>
          <option value="Administrador" {{ old('rolUsuario') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
          <option value="Coordinador" {{ old('rolUsuario') == 'Coordinador' ? 'selected' : '' }}>Coordinador</option>
        </select>
        @if($errors->has('rolUsuario'))
          <div style="color:#d33;font-size:13px;">{{ $errors->first('rolUsuario') }}</div>
        @endif
      </div>
      <div style="text-align:center;margin-top:10px;">
        <button type="submit" class="btn btn-primary" style="background:#1B396A;color:#fff;padding:8px 14px;border:none;border-radius:6px;">Agregar</button>
        <button type="button" id="add_cancel" class="btn btn-secondary" style="background:#ccc;color:#000;padding:8px 14px;border:none;border-radius:6px;margin-left:8px;">Cancelar</button>
      </div>
    </form>
  </div>
</div>

