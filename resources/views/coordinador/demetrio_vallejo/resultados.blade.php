<html lang="es">
<head>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/resultados.css') }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tabla para PDF</title>


</head>
<body>

<div class="contenedor-principal">
  <div class="encabezado">
    <h1>Resultados de Actividades</h1>
    <p>Complete los campos y genere el pdf de los resultados de actividades extraescolares</p>
  </div>

  <div class="seccion">
    <h2 class="seccion-titulo">Documento Base Membretado</h2>
    <div class="contenedor-tarjetas" id="documentosLista" style="margin-top:8px; display: flex; flex-wrap: nowrap; overflow-x: auto; gap: 10px; padding-bottom: 4px; min-height: 140px;">
      @if(isset($documentos) && $documentos->isEmpty())
        <div class="sin-documentos" id="emptyState">
          <i class="fas fa-folder-open" style="font-size:40px;color:#bdc3c7;margin-bottom:8px;"></i>
          <h3>No hay PDFs membretados disponibles</h3>
          <p>Contacte al administrador para cargar documentos membretados.</p>
        </div>
      @else
        @foreach($documentos as $doc)
          <div class="tarjeta-documento documento-card" data-id="{{ $doc->id }}" style="min-width:200px;max-width:220px;flex:0 0 200px;padding:10px 10px 8px 10px;">
            <div class="tarjeta-icono"><i class="fas fa-file-pdf" style="font-size:22px;color:#d9534f;"></i></div>
            <div class="tarjeta-contenido">
              <div class="tarjeta-titulo documento-title" style="font-size:0.93rem;">{{ $doc->nombre }}</div>
                <div class="tarjeta-descripcion documento-desc" style="font-size:0.78rem;">{{ $doc->descripcion }}</div>
                <div class="tarjeta-fecha documento-info" style="font-size:0.7rem;">Subido: {{ $doc->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="tarjeta-acciones documento-actions" style="gap:6px;margin-top:6px;">
              @if($doc->archivo)
                <a href="{{ asset($doc->archivo) }}" target="_blank" class="boton-accion boton-ver-pdf" title="Ver PDF" style="width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;padding:0;"><i class="fas fa-eye" style="font-size:15px;"></i></a>
                <button class="boton-accion btn-cargar-pdf" data-id="{{ $doc->id }}" title="Usar este PDF" style="background:#2ecc71;color:#fff;font-size:0.85rem;padding:0 8px;height:26px;"><i class="fas fa-check"></i> Usar PDF</button>
              @else
                <button disabled class="boton-accion boton-ver-pdf" style="color:#9aa0a6;cursor:default;"><i class="fas fa-eye"></i></button>
              @endif
            </div>
          </div>
        @endforeach
      @endif
    </div>
    <div id="pdfSeleccionadoInfo" style="margin-top:10px;"></div>
  </div>


  <div class="seccion">
    <h2 class="seccion-titulo">Datos de los resultados</h2>
    <div class="contenedor-tabla">
      <div class="tabla-titulo">
        Resultado de Actividades
        <span>18 registros disponibles</span>
      </div>
      <div id="contenedorTabla" class="scroll-wrapper">
        <table>
          <thead>
            <tr>
              <th>NO.</th>
              <th>NOMBRE</th>
              <th>NO.CONTROL</th>
              <th>CARRERA</th>
              <th>SEM</th>
              <th>RESULTADO</th>
              <th>FIRMA DE ENTERADO</th>
            </tr>
         <tbody>
           <!-- Las filas de la tabla se llenarán dinámicamente por una función JS -->
           <tr id="mensajeSinAlumnos">
             <td colspan="7" style="text-align:center; color:#888; font-style:italic; padding:20px; background:#f5f7fa;">
               No hay alumnos evaluados. Complete algunas evaluaciones primero.
             </td>
           </tr>
         </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="seccion">
    <h2 class="seccion-titulo">Información Adicional</h2>
    <div class="grupo-formulario">
      <label for="lugarFecha" class="indicador-obligatorio">Lugar y Fecha</label>
      <input type="text" id="lugarFecha" placeholder="Oaxaca, 06 de agosto de 2025" class="campo-entrada">
      <div class="ayuda-texto">Se establecerá automáticamente la fecha actual si no se especifica</div>
    </div>
  </div>

  <div class="contenedor-botones">
    <button id="btnLimpiar" class="boton boton-secundario">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
        <path d="M2 2v4h1v9a1 1 0 001 1h8a1 1 0 001-1V6h1V2H2zm3 12V7h1v7H5zm3 0V7h1v7H8zm3 0V7h1v7h-1zM4 3h8v2H4V3z"/>
      </svg>
      Limpiar
    </button>
    <button id="btnGenerarPDF" class="boton boton-principal">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
        <path d="M14 0H2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2zM5 4h6a1 1 0 010 2H5a1 1 0 010-2zm3 4a1 1 0 010 2H5a1 1 0 010-2h3zm0 4a1 1 0 010 2H5a1 1 0 010-2h3z"/>
      </svg>
      Generar PDF Final
    </button>
  </div>

<!-- Firmas -->
<div class="footer-firmas" style="display:none;">
  <div class="footer-content">
    <div class="firma">
      <div></div>
      <span>Promotor Cultural o Deportivo</span>
    </div>
    <div class="firma">
      <div></div>
      <span>Jefe(a) de Oficinas de Promoción</span>
    </div>
    <div class="firma">
      <div></div>
      <span>Alejandro Loma Bolaños<br>Jefe de Departamentos de Oficinas Extraescolares</span>
    </div>
  </div>
</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>

<script>
// Establece valor por defecto en el campo Lugar y Fecha
document.addEventListener('DOMContentLoaded', function() {
  const lugarFechaInput = document.getElementById('lugarFecha');
  if (lugarFechaInput && !lugarFechaInput.value) {
    const hoy = new Date();
    const fechaFormateada = hoy.toLocaleDateString('es-MX', {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    });
    lugarFechaInput.value = `Oaxaca, ${fechaFormateada}`;
  }
  
  // Botón limpiar
  document.getElementById('btnLimpiar').addEventListener('click', function() {
    if (confirm('¿Está seguro de que desea limpiar todos los campos?')) {
      document.getElementById('periodoInput').value = '';
      document.getElementById('actividadCulturalInput').value = '';
      document.getElementById('actividadDeportivaInput').value = '';
      document.getElementById('lugarFecha').value = '';
      
      // Limpiar tabla
      const inputs = document.querySelectorAll('#contenedorTabla input');
      inputs.forEach((input, index) => {
        if (index % 8 === 0) {
          // Primera columna (número)
          input.value = Math.floor(index / 8) + 1;
        } else {
          input.value = '';
        }
      });
    }
  });
  

// --- SELECCIÓN DE PDF MEMBRETADO (CORREGIDO Y FUNCIONAL) ---

const cargarBtns = document.querySelectorAll('.btn-cargar-pdf');
const infoDiv = document.getElementById('pdfSeleccionadoInfo');

window.pdfSeleccionado = null;

cargarBtns.forEach(btn => {
  btn.addEventListener('click', function () {
    const id = btn.getAttribute('data-id');
    const card = btn.closest('.documento-card');

    const nombre = card.querySelector('.documento-title').innerText;
    const archivo = card.querySelector("a[target='_blank']").href;

    window.pdfSeleccionado = { id, nombre, archivo };

    infoDiv.innerHTML = `
      <div style="color:#2ecc71; font-weight:600;">
        PDF seleccionado:
        <a href="${archivo}" target="_blank">${nombre}</a>
      </div>
    `;

    cargarBtns.forEach(b => {
      b.style.background = '#2ecc71';
      b.style.color = '#fff';
    });

    btn.style.background = '#ff9800';
    btn.style.color = '#fff';
  });
});

// Función pública para obtener el PDF seleccionado
window.getPDFSeleccionado = function () {
    return window.pdfSeleccionado;
};
});

// Convierte input en textarea si el texto es largo y ajusta el alto automáticamente
function ajustarInputsTabla() {
  const cells = document.querySelectorAll('#contenedorTabla td input');
  cells.forEach(input => {
    input.addEventListener('input', function() {
      // Detecta el ancho real de la celda
      const cell = this.parentNode;
      const cellWidth = cell.offsetWidth;
      // Si el texto sobrepasa el ancho visual, convierte a textarea
      const tempSpan = document.createElement('span');
      tempSpan.style.visibility = 'hidden';
      tempSpan.style.position = 'absolute';
      tempSpan.style.whiteSpace = 'pre';
      tempSpan.style.fontSize = window.getComputedStyle(this).fontSize;
      tempSpan.style.fontFamily = window.getComputedStyle(this).fontFamily;
      tempSpan.textContent = this.value;
      document.body.appendChild(tempSpan);
      const textWidth = tempSpan.offsetWidth;
      document.body.removeChild(tempSpan);
      if (textWidth > cellWidth && this.tagName === 'INPUT') {
        const textarea = document.createElement('textarea');
        textarea.className = 'table-cell-input';
        textarea.value = this.value;
        textarea.oninput = this.oninput;
        textarea.style.height = '20px';
        textarea.style.textAlign = 'center';
        textarea.style.width = cellWidth + 'px';
        this.parentNode.replaceChild(textarea, this);
        textarea.focus();
        ajustarAlturaTextarea(textarea);
      } else if (this.tagName === 'TEXTAREA') {
        this.style.width = cellWidth + 'px';
        ajustarAlturaTextarea(this);
      }
    });
  });
}

function ajustarAlturaTextarea(textarea) {
  textarea.style.height = '20px';
  textarea.style.height = (textarea.scrollHeight) + 'px';
}

window.addEventListener('DOMContentLoaded', ajustarInputsTabla);

// Enlaza el botón correctamente
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnGenerarPDF');
  if (btn) {
    btn.addEventListener('click', generarPDF);
  }
});


// Función corregida para generar PDF
async function generarPDF() {
  const pdfSeleccionado = window.pdfSeleccionado;
  if (!pdfSeleccionado || !pdfSeleccionado.archivo) {
    alert("Selecciona primero el PDF membretado usando el botón 'Usar PDF'.");
    return;
  }
  try {
    // Descargar PDF base
    const response = await fetch(pdfSeleccionado.archivo);
    if (!response.ok) throw new Error('No se pudo descargar el PDF.');
    const pdfBytes = await response.arrayBuffer();
    const pdfDoc = await PDFLib.PDFDocument.load(pdfBytes);
    const page = pdfDoc.getPage(0);
    const pageWidth = page.getWidth();
    const pageHeight = page.getHeight();

    // Fuentes
    const fontBold = await pdfDoc.embedFont(PDFLib.StandardFonts.HelveticaBold);
    const font = await pdfDoc.embedFont(PDFLib.StandardFonts.Helvetica);
    function centerText(text, y, size, fontType) {
      const textWidth = fontType.widthOfTextAtSize(text, size);
      const x = (pageWidth - textWidth) / 2;
      page.drawText(text, { x, y, size, font: fontType });
    }

    // Capa blanca para dar contraste (opcional)
    page.drawRectangle({
      x: 0,
      y: 0,
      width: pageWidth,
      height: pageHeight,
      color: PDFLib.rgb(1, 1, 1),
      opacity: 0.15
    });

    // Encabezado
    let y = pageHeight - 145;
    centerText('DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES', y, 12, fontBold); y -= 16;
    centerText('OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA', y, 10, fontBold); y -= 13;
    centerText('ACTIVIDAD CULTURAL', y, 10, fontBold); y -= 13;
   

    // Capturar tabla con fondo blanco y letras en negrita para el PDF
    const tabla = document.getElementById("contenedorTabla");
    const ths = tabla.querySelectorAll('th');
    const tds = tabla.querySelectorAll('td');
    const inputs = tabla.querySelectorAll('input, textarea');
    // Guardar estilos previos
    const prevMaxHeight = tabla.style.maxHeight;
    const prevOverflowY = tabla.style.overflowY;
    const prevThBg = [];
    const prevThColor = [];
    const prevThFontWeight = [];
    const prevTdBg = [];
    const prevTdColor = [];
    const prevTdFontWeight = [];
    const prevInputFontWeight = [];
    ths.forEach((th, i) => {
      prevThBg[i] = th.style.backgroundColor;
      prevThColor[i] = th.style.color;
      prevThFontWeight[i] = th.style.fontWeight;
      th.style.backgroundColor = '#fff';
      th.style.color = '#000';
      th.style.fontWeight = 'bold';
    });
    tds.forEach((td, i) => {
      prevTdBg[i] = td.style.backgroundColor;
      prevTdColor[i] = td.style.color;
      prevTdFontWeight[i] = td.style.fontWeight;
      td.style.backgroundColor = '#fff';
      td.style.color = '#000';
      td.style.fontWeight = 'bold';
    });
    inputs.forEach((input, i) => {
      prevInputFontWeight[i] = input.style.fontWeight;
      input.style.fontWeight = 'bold';
    });
    tabla.style.maxHeight = "none";
    tabla.style.overflowY = "visible";
    await new Promise(r => setTimeout(r, 100));

    const canvas = await html2canvas(tabla, {
      scale: 2,
      backgroundColor: "#ffffff"
    });

    // Restaurar estilos originales
    ths.forEach((th, i) => {
      th.style.backgroundColor = prevThBg[i];
      th.style.color = prevThColor[i];
      th.style.fontWeight = prevThFontWeight[i];
    });
    tds.forEach((td, i) => {
      td.style.backgroundColor = prevTdBg[i];
      td.style.color = prevTdColor[i];
      td.style.fontWeight = prevTdFontWeight[i];
    });
    inputs.forEach((input, i) => {
      input.style.fontWeight = prevInputFontWeight[i];
    });
    tabla.style.maxHeight = prevMaxHeight;
    tabla.style.overflowY = prevOverflowY;

    // Embebido de imagen de la tabla
    const imgData = canvas.toDataURL("image/png");
    const imgEmbed = await pdfDoc.embedPng(imgData);
    const imgWidth = pageWidth - 60;
    const ratio = imgEmbed.height / imgEmbed.width;
    const imgHeight = imgWidth * ratio;
    // Bajamos la tabla aún más (por ejemplo, 80px extra)
    const margenSuperior = y - 80;
    page.drawImage(imgEmbed, {
      x: 30,
      y: (margenSuperior - imgHeight) + 14,
      width: imgWidth,
      height: imgHeight
    });

    // --- Agregar firmas como imagen ---
    const firmas = document.querySelector('.footer-firmas');
    if (firmas) {
      // Mostrar temporalmente si está oculta
      const prevDisplay = firmas.style.display;
      firmas.style.display = 'flex';
      await new Promise(r => setTimeout(r, 120));
      const canvasFirmas = await html2canvas(firmas, {
        scale: 2,
        backgroundColor: null
      });
      firmas.style.display = prevDisplay;
      const imgFirmasData = canvasFirmas.toDataURL("image/png");
      const imgFirmasEmbed = await pdfDoc.embedPng(imgFirmasData);
      const firmasWidth = pageWidth - 120;
      const firmasRatio = imgFirmasEmbed.height / imgFirmasEmbed.width;
      const firmasHeight = firmasWidth * firmasRatio;
      // Colocar firmas al final de la página
      // Subir firmas 1cm (aprox 28.35 puntos)
      page.drawImage(imgFirmasEmbed, {
        x: 60,
        y: 68,
        width: firmasWidth,
        height: firmasHeight
      });
    }

    // Descargar PDF final
    const pdfFinal = await pdfDoc.save();
    const blob = new Blob([pdfFinal], { type: "application/pdf" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "informe_actividades.pdf";
    link.click();
  } catch (err) {
    alert("Error al generar PDF: " + err.message);
    console.error(err);
  }
}
</script>

</body>
</html>