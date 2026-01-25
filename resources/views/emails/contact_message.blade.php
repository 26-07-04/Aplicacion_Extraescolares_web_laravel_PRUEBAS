<div style="font-family: Arial, Helvetica, sans-serif; color: #222;">
  <h2>Nuevo mensaje desde formulario de contacto</h2>

  <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
  <p><strong>Correo:</strong> {{ $data['email'] }}</p>

  <p><strong>Mensaje:</strong></p>
  <div style="white-space: pre-wrap; background:#f7f7f7; padding:10px; border-radius:4px;">{{ $data['message'] }}</div>

  <p style="font-size:12px; color:#666;">Enviado desde la plataforma de Actividades Extraescolares.</p>
</div>
