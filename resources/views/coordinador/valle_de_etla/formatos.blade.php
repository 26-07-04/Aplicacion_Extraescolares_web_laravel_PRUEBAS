<style>
    .formatos-container {
        padding: 24px;
        background: #f8f9fa;
        min-height: calc(100vh - 120px);
    }
    .formatos-header {
        background: linear-gradient(135deg, #1B396A 0%, #2c5aa0 100%);
        color: white;
        padding: 24px 32px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(27, 57, 106, 0.15);
    }
    .formatos-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0 0 6px 0;
    }
    .formatos-header p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.95rem;
    }
    .membrete-block {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .membrete-block h3 {
        margin: 0 0 10px 0;
        font-size: 1rem;
        color: #1B396A;
    }
    .membrete-scroll {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 4px 2px 12px;
        margin: 0 -2px;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x proximity;
        scrollbar-width: thin;
        scrollbar-color: #1B396A #e8ecf1;
    }
    .membrete-scroll::-webkit-scrollbar {
        height: 8px;
    }
    .membrete-scroll::-webkit-scrollbar-track {
        background: #eef1f5;
        border-radius: 4px;
    }
    .membrete-scroll::-webkit-scrollbar-thumb {
        background: #1B396A;
        border-radius: 4px;
    }
    .documento-card-formatos {
        flex: 0 0 auto;
        width: 168px;
        min-width: 168px;
        max-width: 168px;
        background: #f9fafb;
        border-radius: 10px;
        border: 1px solid #e1e5eb;
        padding: 10px 10px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 6px;
        scroll-snap-align: start;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .documento-card-formatos:hover {
        border-color: #b8c4d6;
        box-shadow: 0 2px 8px rgba(27, 57, 106, 0.08);
    }
    .documento-card-formatos.is-selected {
        border-color: #2e7d32;
        background: #f1f8f2;
        box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.2);
    }
    .documento-card-formatos .doc-icon {
        color: #c62828;
        font-size: 1.35rem;
        line-height: 1;
    }
    .documento-card-formatos .doc-info {
        width: 100%;
        min-width: 0;
    }
    .documento-card-formatos .doc-info strong {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: #222;
        line-height: 1.25;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .documento-card-formatos .doc-info span {
        display: block;
        font-size: 0.65rem;
        color: #777;
        margin-top: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .btn-usar-pdf-formatos {
        background: #1B396A;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        cursor: pointer;
        width: 100%;
        white-space: nowrap;
    }
    .btn-usar-pdf-formatos:hover {
        background: #2c5aa0;
    }
    .btn-usar-pdf-formatos.active {
        background: #2e7d32;
    }
    .membrete-seleccion-info {
        margin-top: 6px;
        font-size: 0.82rem;
        color: #2e7d32;
        min-height: 1.2em;
    }
    .formatos-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .formatos-tab {
        flex: 1;
        min-width: 200px;
        padding: 14px 18px;
        border: 2px solid #d0d7e2;
        border-radius: 10px;
        background: #fff;
        color: #1B396A;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .formatos-tab.active {
        background: #1B396A;
        color: #fff;
        border-color: #1B396A;
        box-shadow: 0 4px 12px rgba(27, 57, 106, 0.25);
    }
    .formatos-seccion { display: none; }
    .formatos-seccion.active { display: block; }
    .formatos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
    }
    .formato-actividad-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        padding: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 140px;
    }
    .formato-actividad-card h4 {
        margin: 0;
        font-size: 1rem;
        color: #222;
        line-height: 1.35;
        flex: 1;
    }
    .formato-actividad-card .categoria {
        font-size: 0.78rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .btn-imprimir-formato {
        background: #d87b15;
        color: #fff;
        border: none;
        padding: 10px 14px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }
    .btn-imprimir-formato:hover {
        background: #c06d10;
    }
    .sin-actividades {
        padding: 32px;
        text-align: center;
        color: #666;
        background: #fff;
        border-radius: 12px;
    }
    .formatos-firmas-block {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #17a2b8;
    }
    .formatos-firmas-block h3 {
        margin: 0 0 14px 0;
        color: #17a2b8;
        font-size: 1rem;
    }
    .formatos-firmas-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    @media (max-width: 960px) {
        .formatos-firmas-grid { grid-template-columns: 1fr; }
    }
    .formatos-firma-card {
        background: #f9fafb;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 14px;
    }
    .formatos-firma-recuadro {
        min-height: 76px;
        margin-bottom: 10px;
        border-bottom: 1px solid #333;
        background: #fff;
    }
    .formatos-firma-card label {
        display: block;
        font-weight: 600;
        font-size: 0.82rem;
        margin-bottom: 6px;
        color: #333;
    }
    .formatos-firma-card input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        padding: 8px 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .formatos-firma-cargo {
        font-size: 0.82rem;
        color: #495057;
        text-align: center;
        margin: 0;
        min-height: 2.2em;
        line-height: 1.3;
    }
    .formatos-firma-cargo[contenteditable="true"] {
        background: #fffef5;
        outline: 1px dashed #17a2b8;
    }
    .formatos-fecha-grupo {
        margin-top: 14px;
    }
    .formatos-fecha-grupo label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }
    .formatos-fecha-grupo input {
        width: 100%;
        box-sizing: border-box;
        padding: 8px 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
    }
</style>

@php
    $firmasUnidadKey = $firmasUnidadKey ?? 'valle_etla';
    $formatosPrintRoute = $formatosPrintRoute ?? 'coordinador.valle.formatos.print';
    $formatosLugarBase = $formatosLugarBase ?? 'Santiago Suchilquitongo';
    $firmasFormatos = \App\Support\ResultadosExtraescolaresFirmas::forUnidad($firmasUnidadKey);
    \Carbon\Carbon::setLocale('es');
    $fechaMxFormatos = \Carbon\Carbon::now('America/Mexico_City');
    $fechaDefaultFormatos = $formatosLugarBase . ', a los ' . $fechaMxFormatos->day . ' días del mes de ' . $fechaMxFormatos->translatedFormat('F') . ' de ' . $fechaMxFormatos->year . '.';
@endphp

<div class="formatos-container">
    <div class="formatos-header">
        <h2><i class="fas fa-file-alt"></i> Formatos de impresión</h2>
        <p>Selecciona el PDF membretado, el tipo de formato y la actividad para generar el documento.</p>
    </div>

    <div class="membrete-block">
        <h3><i class="fas fa-file-pdf"></i> PDF membretado</h3>
        @if(isset($documentos) && $documentos->count() > 0)
            <div class="membrete-scroll" role="list" aria-label="PDFs membretados disponibles">
            @foreach($documentos as $doc)
                <div class="documento-card-formatos" role="listitem">
                    <i class="fas fa-file-pdf doc-icon" aria-hidden="true"></i>
                    <div class="doc-info">
                        <strong title="{{ $doc->nombre ?? 'Documento' }}">{{ $doc->nombre ?? 'Documento' }}</strong>
                        <span title="{{ basename($doc->archivo ?? '') }}">{{ basename($doc->archivo ?? '') }}</span>
                    </div>
                    <button type="button" class="btn-usar-pdf-formatos" data-id="{{ $doc->id }}" data-archivo="{{ asset($doc->archivo) }}">
                        Usar PDF
                    </button>
                </div>
            @endforeach
            </div>
            <div id="pdfFormatosSeleccionadoInfo" class="membrete-seleccion-info"></div>
        @else
            <p style="margin:0; color:#666;">No hay PDF membretado cargado para este semestre. Puedes imprimir sin fondo o subir uno desde el panel de documentos.</p>
        @endif
    </div>


    <div class="formatos-tabs">
        <button type="button" class="formatos-tab active" data-formato="registro">
            <i class="fas fa-clipboard-list"></i> Formato de registro
        </button>
        <button type="button" class="formatos-tab" data-formato="resultados">
            <i class="fas fa-list-check"></i> Formato de resultados
        </button>
    </div>

    <div class="formatos-firmas-block">
        <h3><i class="fas fa-signature"></i> Firmas y fecha del documento</h3>
        <p style="margin:0 0 14px 0; color:#666; font-size:0.9rem;">Configure las firmas antes de imprimir. Los cargos se editan con doble clic, igual que en el informe.</p>
        <div class="formatos-firmas-grid">
            <div class="formatos-firma-card">
                <div class="formatos-firma-recuadro" title="Espacio para firma"></div>
                <label>Promotor cultural o deportivo</label>
                <input type="text" id="formatosFirmaIzqNombre" data-slot="izquierda" value="{{ $firmasFormatos['izquierda']['nombre'] ?? '' }}" data-default="{{ $firmasFormatos['izquierda']['nombre'] ?? '' }}">
                <p id="formatosFirmaIzqCargo" class="formatos-firma-cargo" data-slot="izquierda" data-default="{{ $firmasFormatos['izquierda']['cargo'] ?? '' }}" title="Doble clic para editar">{{ $firmasFormatos['izquierda']['cargo'] ?? '' }}</p>
            </div>
            <div class="formatos-firma-card">
                <div class="formatos-firma-recuadro" title="Espacio para firma"></div>
                <label>Jefe de oficina de promoción</label>
                <input type="text" id="formatosFirmaCentroNombre" data-slot="centro" value="{{ $firmasFormatos['centro']['nombre'] ?? '' }}" data-default="{{ $firmasFormatos['centro']['nombre'] ?? '' }}">
                <p id="formatosFirmaCentroCargo" class="formatos-firma-cargo" data-slot="centro" data-default="{{ $firmasFormatos['centro']['cargo'] ?? '' }}" title="Doble clic para editar">{{ $firmasFormatos['centro']['cargo'] ?? '' }}</p>
            </div>
            <div class="formatos-firma-card">
                <div class="formatos-firma-recuadro" title="Espacio para firma"></div>
                <label>Jefe de departamento</label>
                <input type="text" id="formatosFirmaDerNombre" data-slot="derecha" value="{{ $firmasFormatos['derecha']['nombre'] ?? '' }}" data-default="{{ $firmasFormatos['derecha']['nombre'] ?? '' }}">
                <p id="formatosFirmaDerCargo" class="formatos-firma-cargo" data-slot="derecha" data-default="{{ $firmasFormatos['derecha']['cargo'] ?? '' }}" title="Doble clic para editar">{{ $firmasFormatos['derecha']['cargo'] ?? '' }}</p>
            </div>
        </div>
        <div class="formatos-fecha-grupo">
            <label for="formatosLugarFecha">Lugar y fecha:</label>
            <input type="text" id="formatosLugarFecha" value="{{ $fechaDefaultFormatos }}">
        </div>
    </div>

    <div id="seccion-resultados" class="formatos-seccion">
        <div class="formatos-grid">
            @forelse($actividades ?? [] as $actividad)
                @php
                    $idAct = $actividad->id_actividad ?? $actividad->id ?? '';
                    $cat = $actividad->categorias ?? '';
                @endphp
                <div class="formato-actividad-card">
                    <span class="categoria">{{ $cat ?: 'Actividad' }}</span>
                    <h4>{{ $actividad->nombre_actividad }}</h4>
                    <button type="button" class="btn-imprimir-formato" data-actividad-id="{{ $idAct }}" data-formato="resultados">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            @empty
                <div class="sin-actividades" style="grid-column:1/-1;">
                    No hay actividades registradas para este semestre.
                </div>
            @endforelse
        </div>
    </div>

    <div id="seccion-registro" class="formatos-seccion active">
        <div class="formatos-grid">
            @forelse($actividades ?? [] as $actividad)
                @php $idAct = $actividad->id_actividad ?? $actividad->id ?? ''; @endphp
                <div class="formato-actividad-card">
                    <span class="categoria">{{ $actividad->categorias ?? 'Actividad' }}</span>
                    <h4>{{ $actividad->nombre_actividad }}</h4>
                    <button type="button" class="btn-imprimir-formato" data-actividad-id="{{ $idAct }}" data-formato="registro">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            @empty
                <div class="sin-actividades" style="grid-column:1/-1;">
                    No hay actividades registradas para este semestre.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
(function () {
    window.pdfFormatosSeleccionado = null;

    function textoCargoFormatos(el) {
        if (!el) return '';
        var t = String(el.innerText || el.textContent || '').trim();
        return t || String(el.getAttribute('data-default') || '').trim();
    }

    function inicializarFirmasFormatos() {
        document.querySelectorAll('.formatos-firma-cargo').forEach(function (el) {
            el.addEventListener('dblclick', function (e) {
                e.preventDefault();
                this.contentEditable = 'true';
                this.focus();
            });
            el.addEventListener('blur', function () {
                this.contentEditable = 'false';
                var d = this.getAttribute('data-default') || '';
                if (!(this.textContent || '').trim()) this.textContent = d;
            });
            el.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); this.blur(); }
            });
        });
    }

    function recolectarPayloadFormatos() {
        var slots = {
            izquierda: { nombreId: 'formatosFirmaIzqNombre', cargoId: 'formatosFirmaIzqCargo' },
            centro: { nombreId: 'formatosFirmaCentroNombre', cargoId: 'formatosFirmaCentroCargo' },
            derecha: { nombreId: 'formatosFirmaDerNombre', cargoId: 'formatosFirmaDerCargo' }
        };
        var firmas = {};
        Object.keys(slots).forEach(function (slot) {
            var n = document.getElementById(slots[slot].nombreId);
            var c = document.getElementById(slots[slot].cargoId);
            firmas[slot] = {
                nombre: n ? String(n.value || n.getAttribute('data-default') || '').trim() : '',
                cargo: textoCargoFormatos(c)
            };
        });
        var fecha = document.getElementById('formatosLugarFecha');
        return {
            firmas: firmas,
            fecha: fecha ? String(fecha.value || '').trim() : ''
        };
    }

    function guardarPayloadEImprimir(url) {
        try {
            sessionStorage.setItem('formatosPrintPayload', JSON.stringify(recolectarPayloadFormatos()));
        } catch (e) {}
        var frame = document.getElementById('formatosPrintFrame');
        if (!frame) {
            frame = document.createElement('iframe');
            frame.id = 'formatosPrintFrame';
            frame.title = 'Impresión de formato';
            frame.setAttribute('aria-hidden', 'true');
            frame.style.cssText = 'position:fixed;width:0;height:0;border:0;visibility:hidden;';
            document.body.appendChild(frame);
        }
        frame.src = url;
    }

    inicializarFirmasFormatos();

    document.querySelectorAll('.btn-usar-pdf-formatos').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.btn-usar-pdf-formatos').forEach(function (b) {
                b.classList.remove('active');
            });
            document.querySelectorAll('.documento-card-formatos').forEach(function (card) {
                card.classList.remove('is-selected');
            });
            btn.classList.add('active');
            var card = btn.closest('.documento-card-formatos');
            if (card) {
                card.classList.add('is-selected');
            }
            window.pdfFormatosSeleccionado = {
                id: btn.getAttribute('data-id'),
                archivo: btn.getAttribute('data-archivo')
            };
            var info = document.getElementById('pdfFormatosSeleccionadoInfo');
            if (info) {
                var nombre = card ? (card.querySelector('.doc-info strong')?.textContent || '') : '';
                info.textContent = nombre ? ('PDF seleccionado: ' + nombre) : 'PDF membretado seleccionado.';
            }
        });
    });

    document.querySelectorAll('.formatos-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var formato = tab.getAttribute('data-formato');
            document.querySelectorAll('.formatos-tab').forEach(function (t) {
                t.classList.toggle('active', t === tab);
            });
            document.getElementById('seccion-resultados').classList.toggle('active', formato === 'resultados');
            document.getElementById('seccion-registro').classList.toggle('active', formato === 'registro');
        });
    });

    document.querySelectorAll('.btn-imprimir-formato').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var actividadId = btn.getAttribute('data-actividad-id');
            var formato = btn.getAttribute('data-formato');
            var semestreId = {{ $semestre->id_semestre ?? 'null' }};
            if (!semestreId || !actividadId) {
                alert('No se encontró el semestre o la actividad.');
                return;
            }
            var documentosCount = {{ isset($documentos) ? $documentos->count() : 0 }};
            if (documentosCount > 0 && (!window.pdfFormatosSeleccionado || !window.pdfFormatosSeleccionado.id)) {
                alert('Selecciona el PDF membretado con el botón "Usar PDF".');
                return;
            }
            var baseUrl = `{{ route($formatosPrintRoute, ['semestre' => '__SEM__', 'actividad' => '__ACT__']) }}`
                .replace('__SEM__', semestreId)
                .replace('__ACT__', actividadId);
            var url = baseUrl + '?formato=' + encodeURIComponent(formato);
            if (window.pdfFormatosSeleccionado && window.pdfFormatosSeleccionado.id) {
                url += '&id_documento=' + encodeURIComponent(window.pdfFormatosSeleccionado.id);
            }
            guardarPayloadEImprimir(url);
        });
    });
})();
</script>

