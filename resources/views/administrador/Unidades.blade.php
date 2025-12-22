<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unidades - Instituto Tecnológico del Valle de Etla</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Administrador/actividades.css') }}">
  <style>
    /* Estilos generales */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f8fafc;
      padding-top: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #333;
    }

    main {
      flex: 1;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div style="display: flex; align-items: center; gap: 10px;">
      <a href="{{ route('admin.principal') }}"
         class="btn-flecha-back" title="Regresar al Panel Administrador" aria-label="Regresar">
        <svg viewBox="0 0 24 24" class="icon-flecha" aria-hidden="true" focusable="false" role="img">
          <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
        </svg>
      </a>
    </div>
    <main>
      <!-- Logos institucionales -->
      <div class="header-main">
        <div class="logo-enlace">
          <a href="https://www.gob.mx/sep" target="_blank">
            <img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="Secretaría de Educación Pública">
          </a>
          <a href="https://www.tecnm.mx" target="_blank">
            <img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="Tecnológico Nacional de México">
          </a>
          <a href="http://www.vetla.tecnm.mx/" target="_blank">
            <img src="{{ asset('Imagenes/pleca-ITVE.png') }}" alt="Instituto Tecnológico del Valle de Etla">
          </a>
        </div>
      </div>

      <style>
        .header-main {
          background-color: #ffffff;
          padding: 25px 10px;
          display: flex;
          justify-content: center;
          flex-wrap: wrap;
          gap: 25px;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
          border-bottom: 1px solid #eaeaea;
        }

        .logo-enlace img {
          height: 55px;
          transition: all 0.3s ease;
          filter: grayscale(20%);
        }

        .logo-enlace img:hover {
          transform: translateY(-2px);
          filter: grayscale(0%);
        }
      </style>

      <!-- Encabezado principal del Instituto -->
      <div class="header-instituto">
        <h1>Instituto Tecnológico del Valle de Etla</h1>
        <h2>Tecnológico Nacional de México</h2>
      </div>

      <style>
        .header-instituto {
          background-color: #002147;
          color: white;
          padding: 30px 15px;
          text-align: center;
          position: relative;
          overflow: hidden;
        }

        .header-instituto::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          height: 4px;
          background: linear-gradient(90deg, #ff7f00, #ffaa00);
        }

        .header-instituto h1 {
          font-size: 2.4rem;
          margin: 0 0 8px 0;
          font-weight: 600;
          letter-spacing: 0.5px;
          position: relative;
          z-index: 1;
        }

        .header-instituto h2 {
          font-size: 1.3rem;
          margin: 0;
          font-weight: 300;
          opacity: 0.9;
          letter-spacing: 0.3px;
          position: relative;
          z-index: 1;
        }
      </style>

      <!-- Subtítulo Unidades Académicas -->
      <div class="subtitulo-unidades">
        <h3>Unidades Académicas</h3>
      </div>

      <style>
        .subtitulo-unidades {
          text-align: center;
          padding: 20px 15px;
          background-color: #ffffff;
          margin-bottom: 20px;
        }

        .subtitulo-unidades h3 {
          font-size: 1.7rem;
          font-weight: 500;
          color: #002147;
          margin: 0;
          letter-spacing: 0.5px;
        }
      </style>

      <style>
        /* Animación de entrada suave para las tarjetas */
        @keyframes fadeInUp {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .contenedor-tarjetas {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          padding: 40px 20px;
          max-width: 1200px;
          margin: 40px auto;
          background-color: transparent;
        }

        .tarjeta-unidad {
          width: 280px;
          height: 180px;
          background-color: #ffffff;
          border-radius: 12px;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.30);
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          position: relative;
          overflow: hidden;
          transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
          cursor: pointer;
          margin: 20px;
          border: 1px solid #eaeaea;
          animation: fadeInUp 0.6s ease forwards;
          opacity: 0;
        }

        .tarjeta-unidad:nth-child(1) { animation-delay: 0.1s; }
        .tarjeta-unidad:nth-child(2) { animation-delay: 0.2s; }
        .tarjeta-unidad:nth-child(3) { animation-delay: 0.3s; }
        .tarjeta-unidad:nth-child(4) { animation-delay: 0.4s; }

        .tarjeta-unidad::after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          border-radius: 12px;
          border: 2px solid transparent;
          transition: border-color 0.3s ease;
          pointer-events: none;
        }

        .tarjeta-unidad:hover {
          transform: translateY(-8px) scale(1.02);
          box-shadow: 0 12px 24px rgba(0, 33, 71, 0.30);
          border-color: #e0e0e0;
        }

        .tarjeta-unidad:hover::after {
          border-color: #ff7f00;
        }

        .tarjeta-unidad:active {
          transform: translateY(-4px) scale(1.01);
        }

        .tarjeta-unidad .logo {
          position: absolute;
          width: 50px;
          height: 50px;
          top: 15px;
          right: 15px;
          opacity: 0.4;
          transition: all 0.4s ease;
          filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.1));
          z-index: 3;
        }

        .tarjeta-unidad:hover .logo {
          opacity: 0.7;
          transform: rotate(5deg) scale(1.05);
        }

        .tarjeta-unidad .nombre {
          font-size: 1.6rem;
          font-weight: 600;
          color: #002147;
          text-align: center;
          z-index: 2;
          padding: 0 20px;
          line-height: 1.3;
          margin-bottom: 10px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }

        .tarjeta-unidad .linea-decorativa {
          width: 50px;
          height: 3px;
          background: linear-gradient(90deg, #ff7f00, #ffaa00);
          margin: 8px 0;
          border-radius: 2px;
          transition: width 0.3s ease;
        }

        .tarjeta-unidad:hover .linea-decorativa {
          width: 70px;
        }

        .tarjeta-unidad .subtitulo {
          font-size: 1.2rem;
          color: #666;
          text-align: center;
          padding: 0 15px;
          margin-top: 5px;
          opacity: 0;
          transform: translateY(10px);
          transition: all 0.3s ease;
          z-index: 2;
        }

        .tarjeta-unidad:hover .subtitulo {
          opacity: 1;
          transform: translateY(0);
        }

        .tarjeta-unidad::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: 
            radial-gradient(circle at 20% 80%, rgba(255, 127, 0, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(0, 33, 71, 0.03) 0%, transparent 50%);
          z-index: 1;
          border-radius: 12px;
        }

        @media (max-width: 992px) {
          .tarjeta-unidad {
            width: 240px;
            height: 160px;
            margin: 15px;
          }
          
          .tarjeta-unidad .nombre {
            font-size: 1.2rem;
          }
          
          .tarjeta-unidad .subtitulo {
            font-size: 1rem;
          }
        }

        @media (max-width: 768px) {
          .header-instituto {
            padding: 25px 15px;
          }
          
          .header-instituto h1 {
            font-size: 1.9rem;
          }
          
          .header-instituto h2 {
            font-size: 1.1rem;
          }
          
          .subtitulo-unidades h3 {
            font-size: 1.5rem;
          }
          
          .tarjeta-unidad {
            width: 220px;
            height: 150px;
            margin: 12px;
          }
          
          .tarjeta-unidad .nombre {
            font-size: 1.1rem;
            padding: 0 15px;
          }
          
          .tarjeta-unidad .subtitulo {
            font-size: 0.9rem;
          }
          
          .logo-centrado img {
            height: 100px;
          }
        }
        
        @media (max-width: 576px) {
          .header-instituto {
            padding: 20px 10px;
          }
          
          .header-instituto h1 {
            font-size: 1.6rem;
          }
          
          .header-instituto h2 {
            font-size: 1rem;
          }
          
          .subtitulo-unidades {
            padding: 15px 10px;
          }
          
          .subtitulo-unidades h3 {
            font-size: 1.3rem;
          }
          
          .header-main {
            padding: 20px 10px;
            gap: 15px;
          }
          
          .logo-enlace img {
            height: 45px;
          }
          
          .tarjeta-unidad {
            width: 280px;
            height: 140px;
            margin: 10px;
          }
          
          .tarjeta-unidad .nombre {
            font-size: 1.3rem;
          }
          
          .tarjeta-unidad .subtitulo {
            font-size: 0.8rem;
          }
          
          .contenedor-tarjetas {
            padding: 20px 10px;
          }
          
          .logo-centrado img {
            height: 80px;
          }
        }
      </style>

      <!-- Contenedor de tarjetas -->
      <div class="contenedor-tarjetas">
        <!-- Tarjeta Unión Hidalgo -->
        <a href="{{ route('coordinador.semestres.union') }}" style="text-decoration:none;">
          <div class="tarjeta-unidad" onclick="animarTarjeta(this)">
            <img src="/Imagenes/2.png" alt="Logo ITVE" class="logo">
            <div class="nombre">Unión Hidalgo</div>
            <div class="linea-decorativa"></div>
            <div class="subtitulo">Campus educativo</div>
          </div>
        </a>
        
        <!-- Tarjeta Demetrio Vallejo -->
        <a href="{{ route('coordinador.semestres.demetrio') }}" style="text-decoration:none;">
          <div class="tarjeta-unidad" onclick="animarTarjeta(this)">
            <img src="/Imagenes/2.png" alt="Logo ITVE" class="logo">
            <div class="nombre">Demetrio Vallejo</div>
            <div class="linea-decorativa"></div>
            <div class="subtitulo">Campus educativo</div>
          </div>
        </a>
        
        <!-- Tarjeta Tlahutoltepec -->
        <a href="{{ route('coordinador.semestres.tlahuitoltepec') }}" style="text-decoration:none;">
          <div class="tarjeta-unidad" onclick="animarTarjeta(this)">
            <img src="/Imagenes/2.png" alt="Logo ITVE" class="logo">
            <div class="nombre">Tlahuitoltepec</div>
            <div class="linea-decorativa"></div>
            <div class="subtitulo">Campus educativo</div>
          </div>
        </a>
        
        <!-- Tarjeta Valle de Etla -->
        <a href="{{ route('coordinador.semestres.valle') }}" style="text-decoration:none;">
          <div class="tarjeta-unidad" onclick="animarTarjeta(this)">
            <img src="/Imagenes/2.png" alt="Logo ITVE" class="logo">
            <div class="nombre">Valle de Etla</div>
            <div class="linea-decorativa"></div>
            <div class="subtitulo">Campus principal</div>
          </div>
        </a>
      </div>

      <style>
        .logo-centrado {
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 40px 20px;
          margin: 60px 0 40px 0;
        }

        .logo-centrado img {
          height: 120px;
          width: auto;
          opacity: 0.8;
          transition: all 0.3s ease;
          filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .logo-centrado img:hover {
          opacity: 1;
          transform: scale(1.05);
        }
      </style>

      <!-- Logo centrado -->
      <div class="logo-centrado">
        <img 
          src="/Imagenes/1.png" 
          alt="Logo TecNM"
        >
      </div>

    </main>

    <!-- FOOTER ORIGINAL -->
    <footer class="site-footer">
      <div class="footer-container">
        <!-- Información de contacto -->
        <div class="footer-text">
          <h3>Dirección</h3>
          <p>Abasolo S/N, Barrio del Agua Buena<br>Santiago Suchilquitongo Oaxaca, C.P. 68230</p>
          
          <h3>Contacto</h3>
          <p>
            Email: info@vetla.tecnm.mx<br>
            Teléfono: 951 305 29 27
          </p>
          
          <h3>Preguntar por Whatsapp</h3>
          <a href="https://wa.me/9513052927?text=Buen%20d%C3%ADa%2C%20deseo%20informaci%C3%B3n%20" target="_blank" class="whatsapp-link">
            <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="Whatsapp">
          </a>
        </div>
        
        <!-- Mapa -->
        <div class="footer-map">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15241.367048623282!2d-96.8702191!3d17.2506929!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85c6e36d891967db%3A0x512f566ad941ae57!2sTecnol%C3%B3gico%20Nacional%20de%20M%C3%A9xico%20campus%20Instituto%20Tecnol%C3%B3gico%20del%20Valle%20de%20Etla!5e0!3m2!1ses!2smx!4v1621983062705!5m2!1ses!2smx"
            allowfullscreen>
          </iframe>
        </div>
      </div>
      
      <!-- Derechos reservados -->
      <div class="footer-copyright">
        <p>© 2023 Tecnológico Nacional de México - Campus Valle de Etla. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <script>
    function animarTarjeta(tarjeta) {
      // Animación al hacer clic más suave
      tarjeta.style.transform = 'translateY(-4px) scale(1.01)';
      tarjeta.style.boxShadow = '0 8px 20px rgba(0, 33, 71, 0.25)';
      
      // Volver al estado original después de 0.3 segundos
      setTimeout(function() {
        tarjeta.style.transform = 'translateY(-8px) scale(1.02)';
        tarjeta.style.boxShadow = '0 12px 24px rgba(0, 33, 71, 0.20)';
      }, 150);
      
      // Aquí puedes agregar la lógica para lo que sucede al hacer clic en cada tarjeta
      const nombreUnidad = tarjeta.querySelector('.nombre').textContent;
      console.log('Accediendo a: ' + nombreUnidad);
    }
    
    // Animación de entrada para las tarjetas cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
      const tarjetas = document.querySelectorAll('.tarjeta-unidad');
      tarjetas.forEach((tarjeta, index) => {
        tarjeta.style.animationDelay = `${index * 0.1}s`;
      });
    });
  </script>
</body>
</html>
