<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard - Actividades Extraescolares Valle de Etla')</title>
  
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  
  <!-- SweetAlert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  <!-- Sidebar -->
  @include('layouts.sidebar')

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navbar -->
    @include('partials.header')

    <!-- Content -->
    <div class="content-wrapper">
      @yield('content')
    </div>

    <!-- Footer -->
    @include('partials.footer')
  </div>

  <!-- Icono de perfil -->
  <div id="iconoPerfilContainer">
    <i class="fas fa-user-circle" id="iconoPerfil"></i>
  </div>

  <!-- Scripts -->
  @include('partials.scripts')
</body>
</html>