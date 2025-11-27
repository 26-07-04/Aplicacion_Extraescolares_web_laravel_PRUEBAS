<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Coordinador - {{ $unidad ?? auth()->user()->unidad_academica ?? '' }}</title>
  <link rel="stylesheet" href="{{ asset('css/Coordinador/UnionHidalgo-panel.css') }}">
  <link rel="stylesheet" href="{{ asset('css/constancia.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('Imagenes/ITVE.png') }}" alt="ITVE" class="sidebar-logo">
      <h3>Actividades Extraescolares</h3>
    </div>
    <div class="sidebar-menu">
      <ul>
        <li>
          <a href="{{ route('coordinator.panel') }}" >
            <i class="fas fa-home"></i> Inicio
          </a>
        </li>
        <li>
          <a href="{{ route('coordinador.verestudiantes', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}">
            <i class="fas fa-users"></i> Ver Estudiantes
          </a>
        </li>
        <li>
          <a href="{{ route('coordinador.constancia', ['unidad' => $unidad ?? auth()->user()->unidad_academica ?? '']) }}" class="active">
            <i class="fas fa-file-signature"></i> Constancia de Cumplimiento
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fas fa-file-pdf"></i> Informe de Actividad
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fas fa-chart-line"></i> Resultados
          </a>
        </li>
      </ul>
    </div>
  </div>


  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Navbar -->
      <div class="top-navbar">
      <div class="user-menu" style="position:relative; display:flex; align-items:center; gap:24px; margin-left:auto;">
        <a href="{{ route('coordinator.panel') }}" class="btn-regresar-header" title="Regresar al panel" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background:#1B396A; color:#fff; padding:6px 10px; min-width:36px; border-radius:6px; text-decoration:none;">
          <i class="fas fa-arrow-left" style="font-size:16px; color:#fff; line-height:1;"></i>
        </a>
        <i class="fas fa-user-circle" id="iconoPerfil" style="font-size:34px; color:#1B396A; cursor:pointer;"></i>
        <div id="perfilDropdown" style="display:none; position:absolute; right:0; top:50px; background:#fff; border:1px solid #e5e5e5; box-shadow:0 6px 18px rgba(0,0,0,0.08); border-radius:6px; min-width:220px; z-index:2000;">
          <div style="padding:12px 14px; border-bottom:1px solid #f0f0f0;">
            <strong>{{ $user->nombre ?? 'Usuario' }}</strong>
            <div style="font-size:13px; color:#666;">{{ $user->contacto ?? $user->unidad_academica ?? '' }}</div>
          </div>
          <div style="padding:10px;">
            <button id="btnCerrarSesion" style="width:100%; background:#dc3545; color:#fff; border:none; padding:8px 10px; border-radius:4px; cursor:pointer; font-weight:600;">Cerrar sesión</button>
          </div>
        </div>

        

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
      </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper">
      <!-- Logos -->
      <div class="header-main">
        <div class="logo-enlace">
              <a href="https://www.gob.mx/" target="_blank"><img src="{{ asset('Imagenes/gobt.png') }}" alt="GobMX"></a>
              <a href="https://www.gob.mx/sep" target="_blank"><img src="{{ asset('Imagenes/Logo Educacion.png') }}" alt="SEP"></a>
              <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('Imagenes/Logo TecNM.png') }}" alt="TecNM"></a>
              <a href="http://www.vetla.tecnm.mx/" target="_blank"><img src="{{ asset('Imagenes/pleca-ITVE.png') }}" alt="ITVE"></a>
        </div>
      </div>

      <!-- Encabezado de unidad -->
      <div class="unidad-header">
        Tecnológico Nacional de México - {{ $unidad ?? auth()->user()->unidad_academica ?? '' }}
      </div>

      <!-- Welcome Section -->
      <div class="welcome-section">
        <h1>Bienvenido al Sistema de Actividades Extraescolares</h1>
        <div class="unidad-nombre">{{ $unidad ?? auth()->user()->unidad_academica ?? '' }}</div>
        <p style="margin-top:6px; font-size:14px; color:#333;">Mostrando estudiantes para: <strong>{{ $unidad ?? auth()->user()->unidad_academica ?? '' }}</strong></p>
      </div>

 
<body data-theme="light">
    <div class="container">
        <div class="stats-panel">
            <div class="stat-card"><div class="stat-value"><i class="fas fa-users"></i> <span id="stat-total">0</span></div><div class="stat-label">Total de Alumnos</div></div>
            <div class="stat-card"><div class="stat-value"><i class="fas fa-user-check"></i> <span id="stat-evaluated">0</span></div><div class="stat-label">Alumnos Evaluados</div></div>
            <div class="stat-card"><div class="stat-value"><i class="fas fa-user-clock"></i> <span id="stat-pending">0</span></div><div class="stat-label">Alumnos Pendientes</div></div>
        </div>
        <div class="table-controls">
            <h3 class="title">Actividad</h3>
            <div class="controls-right">
                <button class="btn-action btn-export" onclick="exportToCSV()"><i class="fas fa-file-csv"></i> Exportar a CSV</button>
                <div class="search-wrapper"><i class="fas fa-search"></i><input type="text" id="searchInput" onkeyup="renderTable()" placeholder="Buscar alumno..."></div>
                <div class="theme-switch-wrapper"><i class="fas fa-moon"></i><label class="theme-switch" for="theme-toggle"><input type="checkbox" id="theme-toggle" /><span class="slider round"></span></label><i class="fas fa-sun"></i></div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="sortable" data-column="numero_control" onclick="handleSort('numero_control')">No. Control<i class="fas fa-sort sort-icon"></i></th>
                    <th class="sortable" data-column="nombre" onclick="handleSort('nombre')">Nombre<i class="fas fa-sort sort-icon"></i></th>
                    <th class="sortable" data-column="carrera" onclick="handleSort('carrera')">Carrera<i class="fas fa-sort sort-icon"></i></th>
                    <th class="sortable" data-column="semestre" onclick="handleSort('semestre')">Semestre<i class="fas fa-sort sort-icon"></i></th>
                    <th class="sortable" data-column="status" onclick="handleSort('status')">Estado<i class="fas fa-sort sort-icon"></i></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-alumnos"></tbody>
            <tbody id="empty-state-tbody" style="display: none;"><tr class="empty-row"><td colspan="6"></td></tr></tbody>
        </table>
    </div>
    <div id="overlay" class="overlay"></div>
    <div id="formularioEvaluacion" class="modal">
        <h2>Evaluación de Actividades Complementarias</h2>
        <form id="evaluationForm">
            <input type="hidden" id="evalStudentId">
            <label for="nombreEstudianteEval">Nombre del Estudiante:</label>
            <input type="text" id="nombreEstudianteEval" readonly />
            <label for="actividadComplementaria">Actividad Complementaria:</label>
            <input type="text" id="actividadComplementaria" value="Club de Danza" />
            <label for="periodo">Periodo de Realización:</label>
            <input type="text" id="periodo" placeholder="Ej: Agosto - Diciembre 2025" required />
            <fieldset>
                <legend>Criterios de Evaluación</legend>
                <div id="criteriosContainer"></div>
            </fieldset>
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" rows="3"></textarea>
            <label for="valorNumericoEval">Valor Numérico:</label>
            <input type="number" id="valorNumericoEval" readonly />
            <label for="nivelDesempenoEval">Nivel de Desempeño:</label>
            <input type="text" id="nivelDesempenoEval" readonly />
            <hr />
            <h3>Validación Final</h3>
            <label for="nombreResponsable">Nombre del Profesor Responsable:</label>
            <input type="text" id="nombreResponsable" placeholder="Nombre completo del responsable" required/>
            <label for="nombreJefeExtra">Nombre del Jefe(a) de Act. Extraescolares:</label>
            <input type="text" id="nombreJefeExtra" placeholder="Nombre completo del jefe/a" required/>
            <label for="fechaExpedicion">Fecha de Expedición:</label>
            <input type="date" id="fechaExpedicion" required/>
            <button type="button" onclick="finalizarEvaluacion()">Finalizar Evaluación y Generar Constancia</button>
            <button class="btn-cerrar" type="button" onclick="closeModal('formularioEvaluacion')">Cerrar</button>
        </form>
    </div>
    <div id="toast-notification">
        <i class="fa-solid fa-circle-check"></i>
        <span id="toast-message"></span>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="script.js"></script>
</body>
    <!-- JS for constancia table/modal -->
    <script>
    const { jsPDF } = window.jspdf;
let students = [];
let studentIdToEvaluate = null;
let currentSort = { column: 'nombre', direction: 'asc' };

document.addEventListener('DOMContentLoaded', () => {
    setupTheme();
    loadStudents();
    initializeEvaluationForm();
});

function setupTheme() {
    const themeToggle = document.getElementById('theme-toggle');
    const storedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', storedTheme);
    themeToggle.checked = storedTheme === 'dark';
    themeToggle.addEventListener('change', () => {
        const newTheme = themeToggle.checked ? 'dark' : 'light';
        document.body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
}

async function loadStudents() {
    try {
        const response = await fetch('../php/obtener_alumnos.php');
        if (!response.ok) throw new Error('Error al cargar alumnos');
        const data = await response.json();
        if (data.success && Array.isArray(data.alumnos)) {
            students = data.alumnos.map(alumno => ({
                ...alumno,
                status: alumno.status || 'Pendiente'
            }));
            renderTable();
        } else {
            throw new Error(data.message || "Formato de respuesta inválido");
        }
    } catch (error) {
        showToast('Error al cargar datos.');
        students = [];
        renderTable();
    }
}

function updateStats() {
    const total = students.length;
    const evaluated = students.filter(s => s.status === 'Evaluado').length;
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-evaluated').textContent = evaluated;
    document.getElementById('stat-pending').textContent = total - evaluated;
}

function handleSort(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    renderTable();
}

function renderTable() {
    updateStats();
    const filter = document.getElementById("searchInput").value.toUpperCase();
    let filteredStudents = students.filter(s => 
        (s.nombre && s.nombre.toUpperCase().includes(filter)) || 
        (s.numero_control && s.numero_control.toUpperCase().includes(filter)) ||
        (s.carrera && s.carrera.toUpperCase().includes(filter)) ||
        (s.semestre && s.semestre.toString().includes(filter))
    );

    filteredStudents.sort((a, b) => {
        const valA = a[currentSort.column];
        const valB = b[currentSort.column];
        if (valA === undefined || valB === undefined) return 0;
        let comp = valA > valB ? 1 : (valA < valB ? -1 : 0);
        return currentSort.direction === 'desc' ? comp * -1 : comp;
    });

    document.querySelectorAll('th.sortable .sort-icon').forEach(icon => {
        const column = icon.parentElement.dataset.column;
        icon.className = `fas sort-icon fa-sort${column === currentSort.column ? (currentSort.direction === 'asc' ? '-up' : '-down') : ''}`;
    });

    const tableBody = document.getElementById('tabla-alumnos');
    const emptyStateTbody = document.getElementById('empty-state-tbody');
    tableBody.innerHTML = '';

    if (filteredStudents.length === 0) {
        tableBody.style.display = 'none';
        emptyStateTbody.style.display = '';
        emptyStateTbody.querySelector('td').textContent = students.length === 0 ? 
            "Aún no hay alumnos registrados." : 
            "No se encontraron alumnos que coincidan.";
    } else {
        tableBody.style.display = '';
        emptyStateTbody.style.display = 'none';
        filteredStudents.forEach(student => {
            const isEvaluado = student.status === 'Evaluado';
            tableBody.innerHTML += `
                <tr>
                    <td>${student.numero_control || 'N/A'}</td>
                    <td>${student.nombre || 'N/A'}</td>
                    <td>${student.carrera || 'N/A'}</td>
                    <td>${student.semestre || 'N/A'}</td>
                    <td><span class="status-badge ${isEvaluado ? 'status-completed' : 'status-pending'}">${student.status || 'Pendiente'}</span></td>
                    <td>
                        <div class="actions-container">
                            <button class="btn-generar-constancia" onclick="mostrarFormularioEvaluacion(${student.id_alumno})" ${isEvaluado ? 'disabled' : ''}>
                                <i class="fa-solid ${isEvaluado ? 'fa-check' : 'fa-clipboard-check'}"></i> ${isEvaluado ? 'Evaluado' : 'Evaluar'}
                            </button>
                        </div>
                    </td>
                </tr>`;
        });
    }
}

function exportToCSV() {
    if (students.length === 0) {
        showToast("No hay alumnos para exportar.");
        return;
    }
    const headers = ['No. Control', 'Nombre Completo', 'Carrera', 'Semestre', 'Estado'];
    let csv = "data:text/csv;charset=utf-8," + headers.join(',') + '\n';
    csv += students.map(s => [
        `"${s.numero_control || ''}"`,
        `"${s.nombre || ''}"`,
        `"${s.carrera || ''}"`,
        `"${s.semestre || ''}"`,
        `"${s.status || 'Pendiente'}"`
    ].join(',')).join('\n');
    const a = document.createElement("a");
    a.setAttribute("href", encodeURI(csv));
    a.setAttribute("download", `alumnos_danza_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

const criterios = ["Cumple en tiempo y forma", "Trabaja en equipo", "Muestra liderazgo", "Organiza su tiempo", "Interpreta la realidad", "Realiza sugerencias", "Tiene iniciativa"];

function initializeEvaluationForm() {
    const container = document.getElementById("criteriosContainer");
    container.innerHTML = "";
    criterios.forEach((texto, i) => {
        container.innerHTML += `
            <div class="criterio-item">
                <label>${i + 1}. ${texto}</label>
                <div class="radio-group">
                    ${["Insuficiente", "Suficiente", "Bueno", "Notable", "Excelente"]
                        .map((nivel, idx) => `
                            <label>
                                <input type="radio" name="criterio${i}" value="${idx}" required>
                                ${nivel}
                            </label>
                        `).join(" ")}
                </div>
            </div>`;
    });

    document.getElementById('evaluationForm').addEventListener("change", (e) => {
        if (e.target.name && e.target.name.startsWith("criterio")) {
            calcularPromedioYDesempeno();
        }
    });
}

function mostrarFormularioEvaluacion(studentId) {
    const student = students.find(s => s.id_alumno == studentId);
    if(!student) return;
    studentIdToEvaluate = studentId;
    document.getElementById('evaluationForm').reset();
    document.getElementById('fechaExpedicion').valueAsDate = new Date();
    document.getElementById("nombreEstudianteEval").value = student.nombre || '';
    document.getElementById("evalStudentId").value = studentId;
    calcularPromedioYDesempeno();
    openModal('formularioEvaluacion');
}

function calcularPromedioYDesempeno() {
    let total = 0;
    let count = 0;
    const radios = document.querySelectorAll('#evaluationForm input[type="radio"]:checked');
    radios.forEach(radio => {
        total += parseInt(radio.value);
        count++;
    });
    const promedio = count > 0 ? total / count : 0;
    let nivel = "Insuficiente";
    if (promedio >= 4) nivel = "Excelente";
    else if (promedio >= 3) nivel = "Notable";
    else if (promedio >= 2) nivel = "Bueno";
    else if (promedio >= 1) nivel = "Suficiente";
    document.getElementById("valorNumericoEval").value = promedio.toFixed(2);
    document.getElementById("nivelDesempenoEval").value = nivel;
}

function finalizarEvaluacion() {
    if (!document.getElementById('evaluationForm').checkValidity()) {
        showToast('Complete todos los campos requeridos');
        return;
    }
    
    // Obtener datos de la evaluación
    const student = students.find(s => s.id_alumno == studentIdToEvaluate);
    const form = document.getElementById('evaluationForm');
    
    // Guardar resultados en localStorage
    const evaluationData = {
        studentId: studentIdToEvaluate,
        nombre: student.nombre || 'N/A',
        numero_control: student.numero_control || 'N/A',
        carrera: student.carrera || 'N/A',
        semestre: student.semestre || 'N/A',
        periodo: form.periodo.value || 'N/A',
        valorNumerico: form.valorNumericoEval.value || '0',
        nivelDesempeno: form.nivelDesempenoEval.value || 'N/A',
        nombreResponsable: form.nombreResponsable.value || 'N/A',
        fechaExpedicion: form.fechaExpedicion.value || new Date().toISOString().split('T')[0],
        timestamp: new Date().toISOString()
    };
    
    // Guardar en lista de evaluaciones
    let evaluations = JSON.parse(localStorage.getItem('evaluations') || '[]');
    
    // Eliminar evaluación previa si existe para este estudiante
    evaluations = evaluations.filter(eval => eval.studentId != studentIdToEvaluate);
    
    // Agregar la nueva evaluación
    evaluations.push(evaluationData);
    localStorage.setItem('evaluations', JSON.stringify(evaluations));
    
    // Generar la constancia
    generarConstanciaPDF();
    
    // Actualizar el estado del alumno
    const idx = students.findIndex(s => s.id_alumno == studentIdToEvaluate);
    if(idx > -1) {
        students[idx].status = 'Evaluado';
        students[idx].resultado = `${evaluationData.nivelDesempeno} (${evaluationData.valorNumerico})`;
        renderTable();
    }
    
    // Cerrar el modal y mostrar mensaje de éxito
    closeModal('formularioEvaluacion');
    showToast('Evaluación completada y constancia generada con éxito.');
    
    // Agregar botón para ver resultados
    showResultsButton();
    
    studentIdToEvaluate = null;
}

function generarConstanciaPDF() {
    const doc = new jsPDF();
    const student = students.find(s => s.id_alumno == studentIdToEvaluate);
    const form = document.getElementById('evaluationForm');
    const fechaExpedicion = new Date(form.fechaExpedicion.value);
    const day = fechaExpedicion.getDate();
    const monthNames = ["enero", "febrero", "marzo", "abril", "mayo", "junio",
                      "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
    const month = monthNames[fechaExpedicion.getMonth()];
    const year = fechaExpedicion.getFullYear();

    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');

    // --- ENCABEZADO TIPO TABLA CON LOGO Y LÍNEAS ---
    doc.setDrawColor(0);
    doc.setLineWidth(0.3);
    doc.rect(15, 15, 32, 16); // Celda logo
    doc.rect(47, 15, 108, 8); // Celda central fila 1
    doc.rect(47, 23, 108, 8); // Celda central fila 2
    doc.rect(155, 15, 40, 8); // Celda derecha fila 1
    doc.rect(155, 23, 40, 8); // Celda derecha fila 2

    // --- LOGO ---
    doc.addImage('../Administrador/assets/img/Logo TecNM.png', 'PNG', 16, 16, 30, 14);

    // --- TEXTOS ---
    doc.setFont('arial', 'normal');
    doc.setFontSize(10);
    doc.text('Constancia de cumplimiento de actividad Cultural y/o Deportiva', 49, 20);
    doc.text('Referencia a la Norma ISO 9001:2015   8.1', 49, 28);
    doc.setFont('arial', 'bold');
    doc.text('Código:TecNM-VI-PO-003-05', 157, 20);
    doc.text('Revisión: 0', 157, 28);
    doc.setFont('arial', 'normal');
    doc.text('Página 1 de 1', 157, 32);

    // --- CONTENIDO DE LA CONSTANCIA ---
    let yContent = 40;
    doc.setFont('arial', 'bold');
    doc.setFontSize(12);
    doc.text('Constancia de cumplimiento de actividad Cultural y/o Deportiva', 105, yContent, { align: 'center' });

    yContent += 12;
    doc.setFont('arial', 'normal');
    doc.setFontSize(10);
    doc.text('C.', 22, yContent);
    yContent += 6;
    doc.setFont('arial', 'bold');
    doc.text('JEFE(A) DEL DEPARTAMENTO DE SERVICIOS ESCOLARES', 22, yContent);
    yContent += 6;
    doc.text('PRESENTE', 22, yContent);
    yContent += 10;
    doc.setFont('arial', 'normal');

    let texto1 = `El que suscribe ${form.nombreResponsable.value}, por este medio se permite hacer de su conocimiento que el (la) estudiante ${student.nombre}, con número de control ${student.numero_control} de la carrera de ${student.carrera}, ha cumplido su actividad Cultural y/o Deportiva con el nivel de desempeño ${form.nivelDesempenoEval.value} y un valor numérico de ${form.valorNumericoEval.value} durante el periodo escolar ${form.periodo.value}.`;
    let textoCreditos = `Con un valor curricular de 1 créditos.`;
    let texto2 = `Se extiende la presente en la Valle de Etla, a los ${day} días del mes de ${month} de ${year}.`;

    const ancho = 170;
    const interlineado = 7;
    [texto1, textoCreditos, texto2].forEach(texto => {
        const lineas = doc.splitTextToSize(texto, ancho);
        lineas.forEach(linea => {
            doc.text(linea, 22, yContent, { align: 'justify' });
            yContent += interlineado;
        });
        yContent += 2;
    });

    doc.setFont('arial', 'bold');
    doc.setFontSize(11);
    doc.text('ATENTAMENTE', 105, yContent, { align: 'center' });
    yContent += 8;
    doc.setFontSize(10);
    doc.setTextColor(150, 0, 0);
    doc.text('Vº. Bo.', 105, yContent, { align: 'center' });
    doc.setTextColor(0, 0, 0);

    doc.setDrawColor(0);
    doc.rect(80, yContent + 10, 50, 30);
    doc.setFontSize(12);
    doc.text('SELLO', 105, yContent + 27, { align: 'center' });

    yContent += 50;
    doc.setFontSize(10);
    doc.text('___________________________', 30, yContent);
    doc.text('___________________________', 150, yContent);
    doc.text(form.nombreResponsable.value, 30, yContent + 7);
    doc.text(form.nombreJefeExtra.value, 150, yContent + 7);
    doc.text('Nombre y firma del (de la) profesor(a) responsable', 30, yContent + 13);
    doc.text('Jefe(a) del Depto. de Actividades Extraescolares', 150, yContent + 13);

    doc.setFontSize(8);
    doc.text('c.c.p. Jefe (a) de Departamento Correspondiente', 22, yContent + 25);

    doc.save(`Constancia_${student.nombre.replace(/\s/g, "_")}.pdf`);
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        overlay.classList.add('visible');
        modal.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        overlay.classList.remove('visible');
        modal.classList.remove('visible');
        document.body.style.overflow = '';
    }
}

function showToast(msg) {
    const toast = document.getElementById('toast-notification');
    toast.querySelector('#toast-message').textContent = msg;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function showResultsButton() {
    // Función para mostrar botón de resultados si es necesario
    console.log("Función para mostrar resultados");
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.visible').forEach(modal => {
            closeModal(modal.id);
        });
    }
});
    </script>








      


    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-container">
        <div class="footer-text">
          <h3>Dirección</h3>
          <p>I.m. Altamirano 1776, Centro<br>70150 Unión Hidalgo, Oax.</p>
          <h3>Contacto</h3>
          <p>Teléfono: <a href="tel:+529631251607" style="color:#fff;text-decoration:none">+52 963 125 1607</a></p>
          <h3>Preguntar por Whatsapp</h3>
          <p>
            <a href="https://wa.me/529631251607" target="_blank" rel="noopener">
              <img src="{{ asset('Imagenes/whatsapp.png') }}" alt="WhatsApp" class="footer-whatsapp">
            </a>
          </p>
        </div>
        <div class="footer-map">
          <iframe
            src="https://maps.google.com/maps?q=I.m.%20Altamirano%201776%2C%20Centro%2C%2070150%20Uni%C3%B3n%20Hidalgo%2C%20Oax.&output=embed"
            width="100%" height="280" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
      <div class="footer-copyright">
        <p>© 2025 Tecnológico Nacional de México - Unión Hidalgo. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>

  <!-- Script para dropdown de perfil y logout -->
  <script>
    (function(){
      const icon = document.getElementById('iconoPerfil');
      const dropdown = document.getElementById('perfilDropdown');
      const btnCerrar = document.getElementById('btnCerrarSesion');
      const logoutForm = document.getElementById('logoutForm');

      if (!icon) return;

      function hideDropdown() { if (dropdown) dropdown.style.display = 'none'; }
      function showDropdown() { if (dropdown) dropdown.style.display = 'block'; }

      icon.addEventListener('click', function (e) {
        e.stopPropagation();
        if (!dropdown) return;
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
      });

      document.addEventListener('click', function (e) {
        if (!dropdown) return;
        const target = e.target;
        if (target === dropdown || dropdown.contains(target) || target === icon) return;
        hideDropdown();
      });

      if (btnCerrar) btnCerrar.addEventListener('click', function (e) {
        e.preventDefault();
        if (!logoutForm) return window.location.href = '/';
        logoutForm.submit();
      });
    })();
  </script>

</body>
</html>
