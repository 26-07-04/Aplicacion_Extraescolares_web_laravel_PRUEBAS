<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Acceso - Tecnológico del Valle de Etla</title>
  <link rel="stylesheet" href="Coordinador/assets/css/estilos.css" />
  <style>
    /* Fuente Nunito para todo el contenido */
@import url('https://fonts.googleapis.com/css?family=Nunito:300,400,700,900&display=swap');

body, .contenedor-principal, .lado-izquierdo, .lado-derecho, .formulario-login, .formulario-login input, .formulario-login label, .formulario-login button {
  font-family: 'Nunito', Arial, sans-serif !important;
}

/* Mejora el estilo de los cuadros de texto */
.formulario-login .campo {
  width: 100%;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
}

.formulario-login .campo label {
  display: none; /* Oculta los labels de usuario y contraseña */
}

.input-login {
  width: 100%;
  padding: 7px 8px;
  margin-bottom: 2px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 0.75rem; /* Mucho más pequeña */
  font-family: 'Nunito', Arial, sans-serif;
  background: #f8f9fc;
}

.input-login:focus {
  border-color: #002147;
  outline: none;
}

/* Botón con estilo más llamativo */
.boton-inicio-sesion {
  width: 60%; /* Menos ancho */
  min-width: 140px;
  max-width: 250px;
  margin: 12px auto 0 auto;
  display: block;
  padding: 8px 0;
  border-radius: 6px;
  border: none;
  background: #002147;
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  font-weight: bold;
  letter-spacing: 1px;
}

.boton-inicio-sesion:hover {
  background: #005fa3;
}

/* Mejora la legibilidad de los textos principales */
.lado-izquierdo .lema,
.lado-izquierdo .info-adicional,
.lado-izquierdo .enlace-itve,
.lado-izquierdo .titulo h1,
.lado-izquierdo .titulo p {
  color: #111 !important;
  font-family: 'Nunito', Arial, sans-serif !important;
  letter-spacing: 0.5px;
}

.lado-izquierdo .titulo h1 {
  font-size: 1.3rem;
  font-weight: bold;
  margin-bottom: 2px;
  text-transform: uppercase;
}

.lado-izquierdo .titulo p {
  font-size: 1.05rem;
  margin-bottom: 0;
  font-weight: 500;
}

    .mensaje-error {
      color: red;
      margin-top: 10px;
      font-weight: bold;
      display: none;
    }

    body {
      background: url('Imagenes/Fondo.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .contenedor-principal {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: flex-start; /* Subir el contenido más arriba */
      min-height: 80vh;
      background: rgba(255,255,255,0.9);
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(191, 191, 191, 0.15);
      width: 900px;
      max-width: 98vw;
      margin: 65px auto;
      padding: 0;
      height: 250px; /* Más pequeño verticalmente */
    }

    .lado-izquierdo {
      width: 50%;
      padding: 18px 18px 10px 30px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      border-radius: 18px 0 0 18px;
      background: none;
      color: #000000;
    }

    .lado-izquierdo .lema {
      font-size: 1.6rem; /* Más grande */
      font-weight: bold;
      color: #111;
      margin-bottom: 6px;
      text-shadow: 0 1px 6px #fff;
    }

    .lado-izquierdo .info-adicional {
      font-size: 0.9rem;
      color: #111;
      font-weight: 500;
      margin-bottom: 4px;
      text-shadow: 0 1px 6px #fff;
    }

    .lado-izquierdo .enlace-itve {
      font-size: 1rem;
      color: #002147;
      font-weight: bold;
      margin-bottom: 10px;
      text-decoration: underline;
      word-break: break-all;
    }

    .lado-izquierdo .titulo {
      margin-top: 10px;
      margin-bottom: 0;
      color: #111;
      text-shadow: 0 1px 6px #fff;
    }

    .lado-izquierdo .titulo h1 {
      font-size: 1.3rem;
      font-weight: bold;
      margin-bottom: 2px;
      color: #111;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .lado-izquierdo .titulo p {
      font-size: 1.05rem;
      margin-bottom: 0;
      color: #111;
      font-weight: 500;
    }

    .logos-institucionales {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 10px;
    }

    .logo-tecnm {
      width: 140px; /* Un poco más grande */
      margin-bottom: 18px;
    }

    .logo-itvetla {
      width: 250px; /* Mucho más grande */
      margin-top: 40px;
    }

    .lado-derecho {
      width: 50%;
      padding: 18px 30px 10px 18px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      border-radius: 0 18px 18px 0;
      background: none;
    }

    .formulario-login {
      width: 100%;
      background: none;
      box-shadow: none;
      border-radius: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .formulario-login .campo {
      width: 100%;
      margin-bottom: 10px;
    }

    .formulario-login .campo label {
      display: none; /* Oculta los labels de usuario y contraseña */
    }

    .input-login {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 0.95rem; /* texto más grande */
      font-family: 'Nunito', Arial, sans-serif;
      background: #f8f9fc;
    }

    .input-login:focus {
      border-color: #002147;
      outline: none;
    }

    .boton-inicio-sesion {
      width: 60%; /* Menos ancho */
      min-width: 140px;
      max-width: 250px;
      margin: 12px auto 0 auto;
      display: block;
      padding: 8px 0;
      border-radius: 6px;
      border: none;
      background: #002147;
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.2s;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .boton-inicio-sesion:hover {
      background: #005fa3;
    }

    .opciones-login {
      margin-top: 10px;
      text-align: center;
    }

    .lado-derecho .titulo {
      margin-top: 120px !important;    /* Menos espacio arriba */
      margin-bottom: 10px !important; /* Menos espacio abajo */
      text-align: center;
    }
    .formulario-login {
      margin-top: 40px !important;    /* Menos espacio entre título y formulario */
    }

    .input-login-pequeno {
      width: 80%;           /* Menos ancho */
      min-width: 160px;
      max-width: 380px;
      padding: 10px 12px;
      margin-bottom: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 0.95rem; /* tamaño legible como el original */
      font-family: 'Nunito', Arial, sans-serif;
      background: #f8f9fc;
    }

    .input-login-pequeno:focus {
      border-color: #002147;
      outline: none;
    }

    @media (max-width: 900px) {
      .contenedor-principal {
        flex-direction: column;
        width: 98vw;
        height: auto;
        padding: 0;
      }
      .lado-izquierdo, .lado-derecho {
        width: 100%;
        border-radius: 0;
        padding: 20px 10px;
      }
    }

    /* Agrega esto en tu <style> */
.swal2-title-small {
  font-size: 1rem !important;
}
.swal2-popup-small {
  font-size: 0.9rem !important;
}
  </style>
</head>
<body>
  <div class="contenedor-principal">
    <div class="lado-izquierdo">
      <div class="logos-institucionales">
        <img src="Imagenes/1.png" alt="Logo del Tecnológico Nacional de México" class="logo-tecnm" />
        <img src="Imagenes/2.png" alt="Logo del Instituto Tecnológico del Valle de Etla" class="logo-itvetla" />
      </div>
      <div class="lema">
        Impulsando el futuro desde el Valle de Etla
      </div>
      <div class="info-adicional">
        ¡Formando líderes con excelencia académica y compromiso social!
      </div>
    </div>
    <div class="lado-derecho">
      <div class="titulo" style="margin-bottom: 30px; margin-top: 30px; text-align: center;">
        <h1 style="font-size:1.5rem; font-weight:bold; margin-bottom: 8px;">ACTIVIDADES EXTRAESCOLARES</h1>
        <p style="font-size:1.05rem; font-weight:500; margin-bottom: 0;">Sistema de gestión de actividades complementarias</p>
      </div>
      <form class="formulario-login" style="margin-top: 30px;" method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="campo">
          <img src="Imagenes/user.png" alt="Usuario" style="width:35px; height:35px; margin-right:8px;">
          <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" class="input-login-pequeno" placeholder="Ingresa tu nombre de usuario" />
        </div>
        <div class="campo">
          <img src="Imagenes/pass.png" alt="Contraseña" style="width:35px; height:35px; margin-right:8px;">
          <input type="password" id="contrasena" name="contrasena" class="input-login-pequeno" placeholder="Ingresa tu contraseña" />
        </div>
        <button type="submit" class="boton-inicio-sesion">
          <i class="fas fa-sign-in-alt"></i> Verificar y Acceder
        </button>
        @if ($errors->any())
          <div class="mensaje-error" style="display:block; margin-top:12px;">
            <ul style="margin:0; padding-left:18px; color:#c00; font-weight:600;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </form>
    </div>
  </div>

  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/your_fontawesome_kit.js" crossorigin="anonymous"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Validación -->
  <script>
  function validarAcceso() {
    const usuario = document.getElementById("usuario").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();

    if (!usuario || !contrasena) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Por favor, llena ambos campos.",
        customClass: {
          title: 'swal2-title-small',
          popup: 'swal2-popup-small'
        }
      });
      return;
    }

    fetch('login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: usuario,
        password: contrasena
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        let mensaje = "";
        let destino = "";
  sessionStorage.setItem('usuario', usuario); // usuario es el nombre del usuario logueado
  // Guardar la unidad académica para que la página destino pueda usarla
  if (data.unidad_academica) sessionStorage.setItem('unidad_academica', data.unidad_academica);
        if (data.role === "Administrador") {
          mensaje = "¡Bienvenido Administrador!";
          destino = "Administrador/Semestres-cursados.html";
        } else if (data.role === "Coordinador") {
          mensaje = "¡Bienvenido Coordinador!";
          switch (data.unidad_academica) {
            case "CIDERS unión Hidalgo":
              // Redirigir coordinador de Unión Hidalgo al panel de Semestres cursados de la unidad
              destino = "Coordinador/U_CentrodeInvestigacion_Semestres-cursados.html";
              break;
            case "Unidad Demetrio Vallejo en el Espinal":
              // Redirigir coordinador de Demetrio al panel de Semestres cursados de la unidad
              destino = "Coordinador/U_Demetrio_Semestres-cursados.html";
              break;
            case "Unidad académica Tlahuitoltepec":
              // Redirigir coordinador de Santa María/Tlahuitoltepec al panel de Semestres cursados de la unidad
              destino = "Coordinador/U_SantaMaria_Semestres-cursados.html";
              break;
            case "Valle de Etla":
              // Redirigir coordinador de Valle de Etla al panel de Semestres cursados de la unidad
              destino = "Coordinador/U_ValleEtla_Semestres-cursados.html";
              break;
            default:
              destino = ""; // Fallback en caso de unidad desconocida
          }
        }
        Swal.fire({
          position: "center",
          icon: "success",
          title: mensaje,
          showConfirmButton: false,
          timer: 1500,
          customClass: {
            title: 'swal2-title-small'
          }
        });
        setTimeout(() => {
          window.location.href = destino;
        }, 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: data.message || "Usuario o contraseña incorrectos.",
          customClass: {
            title: 'swal2-title-small',
            popup: 'swal2-popup-small'
          }
        });
      }
    })
    .catch(error => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo conectar con el servidor.",
        customClass: {
          title: 'swal2-title-small',
          popup: 'swal2-popup-small'
        }
      });
    });
  }
  </script>
</body>
</html>