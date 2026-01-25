@extends('layouts.app')

@section('title', 'Contacto - Actividades Extraescolares')

@section('content')
  <div class="container page-section">
    <h1>Contacto</h1>
    <p>¿Necesitas ayuda? Completa el siguiente formulario y nos comunicaremos contigo.</p>

    {{-- SweetAlert2 popups for success / error --}}

    <form class="contact-form" action="{{ route('contact.send') }}" method="post">
      @csrf
      <div class="form-row">
        <label>Nombre</label>
        <input type="text" name="name" required value="{{ old('name') }}">
      </div>
      <div class="form-row">
        <label>Correo</label>
        <input type="email" name="email" required value="{{ old('email') }}">
      </div>
      <div class="form-row">
        <label>Mensaje</label>
        <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
      </div>
      <div class="form-row">
        <button class="btn btn-primary" type="submit">Enviar</button>
      </div>
    </form>
  </div>
@endsection

@push('head')
<style>
  /* Estilos para reducir el tamaño de letra del SweetAlert central */
  .swal2-popup-center {
    font-size: 14px !important;
    padding: 1.25rem !important;
    max-width: 350px !important;
  }
  .swal2-popup-center .swal2-title {
    font-size: 16px !important;
    margin-bottom: 0.25rem !important;
  }
  .swal2-popup-center .swal2-content {
    font-size: 13px !important;
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: '{{ addslashes(session('success')) }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
          popup: 'swal2-popup-center'
        }
      });
    @endif

    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: '{{ addslashes(session('error')) }}',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        customClass: {
          popup: 'swal2-popup-center'
        }
      });
    @endif
  });
</script>
@endpush
