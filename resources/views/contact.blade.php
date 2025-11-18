@extends('layouts.app')

@section('title', 'Contacto - Actividades Extraescolares')

@section('content')
  <div class="container page-section">
    <h1>Contacto</h1>
    <p>¿Necesitas ayuda? Completa el siguiente formulario y nos comunicaremos contigo.</p>

    <form class="contact-form" action="#" method="post" onsubmit="event.preventDefault(); alert('Formulario de contacto enviado (demo)');">
      <div class="form-row">
        <label>Nombre</label>
        <input type="text" name="name" required>
      </div>
      <div class="form-row">
        <label>Correo</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-row">
        <label>Mensaje</label>
        <textarea name="message" rows="5" required></textarea>
      </div>
      <div class="form-row">
        <button class="btn btn-primary" type="submit">Enviar</button>
      </div>
    </form>
  </div>
@endsection
