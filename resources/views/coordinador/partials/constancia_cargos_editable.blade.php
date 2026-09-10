@php
    $constanciaCargosStorageKey = $constanciaCargosStorageKey ?? 'constancia_cargos';
    $initFunctionName = $initFunctionName ?? 'inicializarConstanciaCargos';
    $obtenerFunctionName = $obtenerFunctionName ?? 'obtenerCargosConstancia';
    $defaultCargoProfesor = $defaultCargoProfesor ?? 'Profesor responsable';
    $defaultCargoVobo = $defaultCargoVobo ?? 'Subdirector Académico';
    $defaultCargoDestinatario = $defaultCargoDestinatario ?? 'Jefe del Departamento de Servicios Escolares';
@endphp
<style>
    .constancia-firma-cargo {
        font-size: 0.82rem;
        color: #495057;
        line-height: 1.35;
        text-align: center;
        margin: 8px 0 0 0;
        min-height: 2.4em;
        cursor: default;
        user-select: none;
    }
    .constancia-firma-cargo[contenteditable="true"] {
        user-select: text;
        cursor: text;
        background: #fffef5;
        outline: 1px dashed #17a2b8;
        border-radius: 2px;
    }
</style>
<script>
(function () {
    var STORAGE_KEY = @json($constanciaCargosStorageKey);
    var INIT_FN = @json($initFunctionName);
    var OBTENER_FN = @json($obtenerFunctionName);
    var DEFAULTS = {
        cargoProfesor: @json($defaultCargoProfesor),
        cargoVobo: @json($defaultCargoVobo),
        cargoDestinatario: @json($defaultCargoDestinatario)
    };

    function textoCargoConstancia(elId, def) {
        var el = document.getElementById(elId);
        if (!el) return def;
        if (el.tagName === 'SELECT') return String(el.value || '').trim() || def;
        var t = (el.textContent || '').trim();
        return t || def;
    }

    function leerCargosLocalStorage() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return null;
            var parsed = JSON.parse(raw);
            return parsed && typeof parsed === 'object' ? parsed : null;
        } catch (e) {
            return null;
        }
    }

    function guardarCargosLocalStorage() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                cargoProfesor: textoCargoConstancia('cargoProfesor', DEFAULTS.cargoProfesor),
                cargoVobo: textoCargoConstancia('cargoVobo', DEFAULTS.cargoVobo),
                cargoDestinatario: textoCargoConstancia('cargoDestinatario', DEFAULTS.cargoDestinatario)
            }));
        } catch (e) {}
    }

    window[OBTENER_FN] = function () {
        return {
            cargo_profesor: textoCargoConstancia('cargoProfesor', DEFAULTS.cargoProfesor),
            cargo_vobo: textoCargoConstancia('cargoVobo', DEFAULTS.cargoVobo),
            cargo_destinatario: textoCargoConstancia('cargoDestinatario', DEFAULTS.cargoDestinatario)
        };
    };

    window[INIT_FN] = function () {
        var saved = leerCargosLocalStorage();
        var map = {
            cargoProfesor: saved && saved.cargoProfesor,
            cargoVobo: saved && saved.cargoVobo,
            cargoDestinatario: saved && saved.cargoDestinatario
        };
        Object.keys(map).forEach(function (id) {
            var el = document.getElementById(id);
            if (!el) return;
            var val = map[id] || el.getAttribute('data-default') || '';
            if (el.tagName === 'SELECT') {
                if ([].some.call(el.options, function (option) { return option.value === val; })) {
                    el.value = val;
                }
                return;
            }
            el.textContent = val;
        });

        document.querySelectorAll('.constancia-firma-cargo').forEach(function (el) {
            if (el.dataset.cargoBound === '1') return;
            el.dataset.cargoBound = '1';

            el.addEventListener('dblclick', function (e) {
                e.preventDefault();
                el.contentEditable = 'true';
                el.focus();
                try {
                    var range = document.createRange();
                    range.selectNodeContents(el);
                    range.collapse(false);
                    var sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                } catch (ignore) {}
            });

            el.addEventListener('blur', function () {
                el.contentEditable = 'false';
                var def = el.getAttribute('data-default') || '';
                if (!(el.textContent || '').trim()) {
                    el.textContent = def;
                }
                guardarCargosLocalStorage();
            });

            el.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    el.blur();
                }
            });
        });
    };
})();
</script>
