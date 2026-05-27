@php
    $constanciaCargosStorageKey = $constanciaCargosStorageKey ?? 'constancia_cargos_extraescolar';
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

    function cargosPorDefecto() {
        return {
            cargoProfesor: 'Profesor responsable',
            cargoVobo: 'Subdirección de Planeación y Vinculación',
            cargoDestinatario: 'Jefe del Departamento de Servicios Escolares'
        };
    }

    function textoCargoConstancia(elId, def) {
        var el = document.getElementById(elId);
        if (!el) return def;
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
                cargoProfesor: textoCargoConstancia('cargoProfesor', cargosPorDefecto().cargoProfesor),
                cargoVobo: textoCargoConstancia('cargoVobo', cargosPorDefecto().cargoVobo),
                cargoDestinatario: textoCargoConstancia('cargoDestinatario', cargosPorDefecto().cargoDestinatario)
            }));
        } catch (e) {}
    }

    window.obtenerCargosConstanciaExtraescolar = function () {
        var d = cargosPorDefecto();
        return {
            cargo_profesor: textoCargoConstancia('cargoProfesor', d.cargoProfesor),
            cargo_vobo: textoCargoConstancia('cargoVobo', d.cargoVobo),
            cargo_destinatario: textoCargoConstancia('cargoDestinatario', d.cargoDestinatario)
        };
    };

    window.inicializarConstanciaExtraescolarCargos = function () {
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
