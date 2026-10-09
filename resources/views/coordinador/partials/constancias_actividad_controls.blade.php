<div style="color: white; display: flex; align-items: center; gap: 10px; margin-left: 8px;">
    <i class="fas fa-calendar-alt"></i>
    <label for="fechaConstancia" style="font-weight: 600; font-size: 1.05em;">Fecha:</label>
</div>
<input id="fechaConstancia" type="date" value="{{ date('Y-m-d') }}" style="padding: 10px 12px; border: 2px solid white; border-radius: 6px; font-size: 0.95em; background: white; color: #333;">
<select id="selectActividadConstancia" style="min-width: 210px; padding: 10px 12px; border: 2px solid white; border-radius: 6px; font-size: 0.95em; background: white; color: #333; cursor: pointer;">
    <option value="all">Todas las actividades</option>
    @foreach(($actividades ?? collect()) as $actividad)
        <option value="{{ $actividad->id_actividad ?? $actividad['id_actividad'] ?? '' }}">{{ $actividad->nombre_actividad ?? $actividad['nombre_actividad'] ?? 'Actividad' }}</option>
    @endforeach
</select>
<label style="display: flex; flex-direction: column; gap: 4px; min-width: 220px; flex: 1; color: white; font-size: 0.85em; font-weight: 600;">Profesor(a) responsable
    <input id="nombreProfesorGeneral" type="text" value="DR. FERNANDO ADRIHEL SARUBBI BALTAZAR" aria-label="Profesor responsable" style="width: 100%; padding: 10px 12px; border: 2px solid white; border-radius: 6px; font-size: 0.95em; background: white; color: #333;">
</label>
<label style="display: flex; flex-direction: column; gap: 4px; min-width: 220px; flex: 1; color: white; font-size: 0.85em; font-weight: 600;">Jefe(a) de Actividades Extraescolares
    <input id="jefeExtraescolaresGeneral" type="text" value="M.C. ALEJANDRO LOMA BOLAÑOS" aria-label="Jefe del Departamento de Actividades Extraescolares" style="width: 100%; padding: 10px 12px; border: 2px solid white; border-radius: 6px; font-size: 0.95em; background: white; color: #333;">
</label>
<label style="display: flex; flex-direction: column; gap: 4px; min-width: 220px; flex: 1; color: white; font-size: 0.85em; font-weight: 600;">Jefe(a) de Servicios Escolares
    <input id="jefeServiciosEscolaresGeneral" type="text" value="LIC.HIRAM GALLEGOS FELIPE" aria-label="Jefe del Departamento de Servicios Escolares" style="width: 100%; padding: 10px 12px; border: 2px solid white; border-radius: 6px; font-size: 0.95em; background: white; color: #333;">
</label>
<button type="button" onclick="evaluarActividadCompleta()" title="Evaluar los alumnos pendientes de esta actividad con la misma rúbrica" style="padding: 10px 14px; border: 0; border-radius: 6px; background: #198754; color: white; font-weight: 600; cursor: pointer; white-space: nowrap;">
    <i class="fas fa-users"></i> Evaluar todos
</button>
<button type="button" onclick="imprimirConstanciasActividad()" title="Abrir en un solo PDF las constancias de esta actividad" style="padding: 10px 14px; border: 0; border-radius: 6px; background: #0d6efd; color: white; font-weight: 600; cursor: pointer; white-space: nowrap;">
    <i class="fas fa-print"></i> Imprimir constancias
</button>
<button type="button" onclick="actualizarResponsablesConstancias()" title="Actualizar los nombres de responsables en las evaluaciones guardadas de esta actividad" style="padding: 10px 14px; border: 0; border-radius: 6px; background: #495057; color: white; font-weight: 600; cursor: pointer; white-space: nowrap;">
    <i class="fas fa-user-edit"></i> Actualizar datos de evaluación
</button>
<script>
    const semestreConstanciaId = @json($semestre->id_semestre ?? $semestre->id ?? '');
    const rutaConstanciasActividad = @json($rutaConstanciasActividad);
    const rutaActualizarResponsables = @json($rutaActualizarResponsables);
    const rutaGuardarEvaluacion = @json($rutaGuardarEvaluacion);
    let estudiantesEvaluacionMasiva = null;

    function obtenerFechaConstanciaSeleccionada() {
        const input = document.getElementById('fechaConstancia');
        return input && input.value ? input.value : new Date().toISOString().slice(0, 10);
    }

    function obtenerFirmasConstancia() {
        return {
            nombre_profesor: document.getElementById('nombreProfesorGeneral').value.trim(),
            jefe_extraescolares: document.getElementById('jefeExtraescolaresGeneral').value.trim(),
            jefe_servicios_escolares: document.getElementById('jefeServiciosEscolaresGeneral').value.trim(),
        };
    }

    function validarSeleccionActividad() {
        const actividad = document.getElementById('selectActividadConstancia')?.value;
        if (!actividad || actividad === 'all') {
            Swal.fire('Selecciona una actividad', 'Elige una actividad antes de continuar.', 'warning');
            return null;
        }
        return actividad;
    }

    function imprimirConstanciasActividad() {
        const idActividad = validarSeleccionActividad();
        const idDocumento = document.getElementById('selectDocumentoMembrete')?.value;
        if (!idActividad) return;
        if (!idDocumento) {
            Swal.fire('Documento requerido', 'Selecciona el documento membretado.', 'warning');
            return;
        }

        const parametros = new URLSearchParams({
            id_actividad: idActividad,
            id_semestre: semestreConstanciaId,
            id_documento: idDocumento,
            fecha_constancia: obtenerFechaConstanciaSeleccionada(),
            ...obtenerFirmasConstancia(),
        });
        window.open(`${rutaConstanciasActividad}?${parametros.toString()}`, '_blank');
    }

    async function actualizarResponsablesConstancias() {
        const idActividad = validarSeleccionActividad();
        if (!idActividad) return;

        const firmas = obtenerFirmasConstancia();
        if (Object.values(firmas).some(nombre => !nombre)) {
            Swal.fire('Faltan nombres', 'Completa los tres nombres de responsables antes de actualizar.', 'warning');
            return;
        }

        const confirmacion = await Swal.fire({
            title: 'Actualizar datos de evaluación',
            text: 'Se actualizarán los nombres de responsables en las evaluaciones guardadas de esta actividad. Calificaciones, criterios y observaciones no cambiarán.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar',
        });
        if (!confirmacion.isConfirmed) return;

        try {
            const response = await fetch(rutaActualizarResponsables, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    id_actividad: idActividad,
                    id_semestre: semestreConstanciaId,
                    ...firmas,
                }),
            });
            const resultado = await response.json();
            if (!response.ok || !resultado.success) {
                throw new Error(resultado.message || 'No se pudieron actualizar los datos.');
            }

            Swal.fire(
                'Datos actualizados',
                `Se actualizaron los responsables en ${resultado.actualizadas} evaluaciones de esta actividad.`,
                'success'
            );
        } catch (error) {
            Swal.fire('No se pudo actualizar', error.message, 'error');
        }
    }

    function evaluarActividadCompleta() {
        const idActividad = validarSeleccionActividad();
        if (!idActividad) return;

        const pendientes = todosEstudiantes.filter(est =>
            String(est.id_actividad ?? '') === String(idActividad) && est.status !== 'completed'
        );
        if (pendientes.length === 0) {
            Swal.fire('Sin pendientes', 'Todos los alumnos de esta actividad ya tienen evaluación.', 'info');
            return;
        }

        Swal.fire({
            title: `Evaluar ${pendientes.length} alumnos`,
            text: 'La misma rúbrica y calificación se aplicarán a todos los alumnos pendientes de esta actividad.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar',
        }).then(resultado => {
            if (!resultado.isConfirmed) return;
            estudiantesEvaluacionMasiva = pendientes;
            abrirModalEvaluacion(pendientes[0]);
            const titulo = document.querySelector('#modalEvaluacion h2');
            if (titulo) titulo.innerHTML = '<i class="fas fa-users" style="margin-right: 10px;"></i> Evaluación para toda la actividad';
        });
    }

    async function guardarEvaluacionesMasivas(datosEvaluacion) {
        const alumnos = [...(estudiantesEvaluacionMasiva || [])];
        const boton = document.querySelector('#formEvaluacion button[type="submit"]');
        if (boton) {
            boton.disabled = true;
            boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando evaluaciones...';
        }

        let guardados = 0;
        for (const estudiante of alumnos) {
            try {
                const respuesta = await fetch(rutaGuardarEvaluacion, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ...datosEvaluacion, id_alumno: estudiante.id_alumno }),
                });
                const resultado = await respuesta.json();
                if (!respuesta.ok || !resultado.success) {
                    throw new Error(resultado.message || `No se pudo evaluar a ${estudiante.nombre}.`);
                }

                estudiante.status = 'completed';
                estudiante.id_evaluacion = resultado.id_evaluacion;
                guardados++;
            } catch (error) {
                estudiantesEvaluacionMasiva = null;
                cerrarModalEvaluacion();
                buscar();
                Swal.fire('Evaluación parcial', `${guardados} evaluaciones guardadas. ${error.message}`, 'error');
                return;
            }
        }

        estudiantesEvaluacionMasiva = null;
        cerrarModalEvaluacion();
        buscar();
        Swal.fire('Evaluación completada', `Se guardaron ${guardados} evaluaciones. Puedes imprimirlas juntas con el botón de impresión.`, 'success');
    }
</script>