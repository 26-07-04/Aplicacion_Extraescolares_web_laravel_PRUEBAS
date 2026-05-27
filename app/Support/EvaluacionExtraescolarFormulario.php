<?php

namespace App\Support;

use App\Models\Actividad;
use App\Models\Evaluacion;
use App\Models\Semestre;

class EvaluacionExtraescolarFormulario
{
    /** @var list<string> */
    public const CRITERIOS = [
        'Cumple en tiempo y forma con las actividades encomendadas alcanzando los objetivos.',
        'Trabaja en equipo y se adapta a nuevas situaciones.',
        'Muestra liderazgo en las actividades encomendadas.',
        'Organiza su tiempo y trabaja de manera proactiva.',
        'Interpreta la realidad y se sensibiliza aportando soluciones a la problemática con la actividad Cultural y/o Deportiva.',
        'Realiza sugerencias innovadoras para beneficio o mejora del programa en el que participa.',
        'Tiene iniciativa para ayudar en las actividades encomendadas y muestra espíritu de servicio.',
    ];

  /** @var list<string> */
    public const COLUMNAS_NIVEL = ['insuficiente', 'suficiente', 'bueno', 'notable', 'excelente'];

    /**
     * @return array<string, mixed>
     */
    public static function datosImpresion(Evaluacion $evaluacion): array
    {
        $evaluacion->loadMissing(['estudiante', 'actividad', 'semestre']);

        $actividad = $evaluacion->actividad;
        $categoria = trim((string) ($actividad->categorias ?? ''));
        $nombreActividad = trim((string) ($actividad->nombre_actividad ?? ''));

        if ($categoria !== '') {
            $tipoCorto = self::etiquetaCategoriaCorta($categoria);
            $lineaActividad = $tipoCorto . ' (' . $nombreActividad . ')';
        } else {
            $lineaActividad = $nombreActividad;
        }

        $cal = (float) ($evaluacion->calificacion_numerica ?? 0);
        $calStr = (abs($cal - round($cal)) < 0.001)
            ? (string) (int) round($cal)
            : number_format($cal, 1, '.', '');

        return [
            'evaluacion' => $evaluacion,
            'estudiante' => $evaluacion->estudiante,
            'actividad' => $actividad,
            'semestre' => $evaluacion->semestre,
            'nombreEstudiante' => $evaluacion->estudiante->nombre ?? 'N/A',
            'lineaActividad' => $lineaActividad,
            'periodoRealizacion' => self::periodoTexto($evaluacion->semestre),
            'marcasFilas' => self::marcasFilasParaEvaluacion($evaluacion),
            'valorNumerico' => $calStr,
            'nivelAlcanzado' => trim((string) ($evaluacion->nivel_desempeno ?? '')),
            'observaciones' => trim((string) ($evaluacion->observaciones ?? '')),
        ];
    }

    public static function periodoTexto(?Semestre $semestre): string
    {
        $nombre = trim((string) ($semestre->nombre ?? ''));

        return $nombre !== '' ? mb_strtolower($nombre, 'UTF-8') : 'N/A';
    }

    public static function etiquetaCategoriaCorta(string $categoria): string
    {
        $c = mb_strtolower($categoria, 'UTF-8');

        if (str_contains($c, 'deport')) {
            return 'Deporte';
        }
        if (str_contains($c, 'cultur')) {
            return 'Cultural';
        }
        if (str_contains($c, 'acad')) {
            return 'Académica';
        }

        return $categoria;
    }

    /**
     * Una fila de marcas (X) por cada criterio, según calificación 0–4 guardada o nivel global.
     *
     * @return list<array<string, bool>>
     */
    public static function marcasFilasParaEvaluacion(Evaluacion $evaluacion): array
    {
        $valores = $evaluacion->criterios_desempeno;
        if (is_array($valores) && count($valores) === 7) {
            $filas = [];
            foreach ($valores as $valor) {
                $filas[] = self::marcasPorValorCriterio(
                    is_numeric($valor) ? (int) $valor : null,
                    $evaluacion
                );
            }

            return $filas;
        }

        $columna = self::columnaDesdeNivel(
            (string) ($evaluacion->nivel_desempeno ?? ''),
            (float) ($evaluacion->calificacion_numerica ?? 0)
        );
        $marcaUnica = self::marcasPorColumna($columna);

        return array_fill(0, 7, $marcaUnica);
    }

    /**
     * @return array<string, bool>
     */
    public static function marcasPorValorCriterio(?int $valor, ?Evaluacion $evaluacion = null): array
    {
        if ($valor !== null && $valor >= 0 && $valor <= 4) {
            $map = [
                0 => 'insuficiente',
                1 => 'suficiente',
                2 => 'bueno',
                3 => 'notable',
                4 => 'excelente',
            ];

            return self::marcasPorColumna($map[$valor]);
        }

        if ($evaluacion) {
            $columna = self::columnaDesdeNivel(
                (string) ($evaluacion->nivel_desempeno ?? ''),
                (float) ($evaluacion->calificacion_numerica ?? 0)
            );

            return self::marcasPorColumna($columna);
        }

        return self::marcasPorColumna('insuficiente');
    }

    /**
     * @return array<string, bool>
     */
    public static function marcasPorColumna(string $columna): array
    {
        $marcas = [];
        foreach (self::COLUMNAS_NIVEL as $col) {
            $marcas[$col] = $col === $columna;
        }

        return $marcas;
    }

    /** @deprecated Use marcasFilasParaEvaluacion() */
    public static function marcasPorNivel(string $nivelDesempeno, ?float $calificacionFallback = null): array
    {
        return self::marcasPorColumna(self::columnaDesdeNivel($nivelDesempeno, $calificacionFallback ?? 0));
    }

    public static function columnaDesdeNivel(string $nivelDesempeno, float $calificacionFallback = 0): string
    {
        $n = mb_strtolower(trim($nivelDesempeno), 'UTF-8');

        if ($n === '' || str_contains($n, 'no evaluado')) {
            return self::columnaDesdeCalificacion($calificacionFallback);
        }

        return match (true) {
            str_contains($n, 'insuficiente') => 'insuficiente',
            str_contains($n, 'excelente') => 'excelente',
            str_contains($n, 'notable') => 'notable',
            str_contains($n, 'muy bien') => 'notable',
            str_contains($n, 'bueno') => 'bueno',
            $n === 'bien' || preg_match('/\bbien\b/u', $n) === 1 => 'bueno',
            str_contains($n, 'suficiente') => 'suficiente',
            default => self::columnaDesdeCalificacion($calificacionFallback),
        };
    }

    public static function columnaDesdeCalificacion(float $calificacion): string
    {
        if ($calificacion >= 3.5) {
            return 'excelente';
        }
        if ($calificacion >= 2.5) {
            return 'notable';
        }
        if ($calificacion >= 1.5) {
            return 'bueno';
        }
        if ($calificacion >= 1.0) {
            return 'suficiente';
        }

        return 'insuficiente';
    }

    /**
     * @param  mixed  $criterios
     * @return list<int>|null
     */
    public static function normalizarCriteriosDesempeno($criterios): ?array
    {
        if (! is_array($criterios) || count($criterios) !== 7) {
            return null;
        }

        $valores = [];
        foreach ($criterios as $valor) {
            if (! is_numeric($valor)) {
                return null;
            }
            $entero = (int) $valor;
            if ($entero < 0 || $entero > 4) {
                return null;
            }
            $valores[] = $entero;
        }

        return $valores;
    }

    /**
     * @param  array<string, mixed>  $datos
     * @return array{cargo_profesor: string, cargo_vobo: string, cargo_destinatario: string}
     */
    public static function cargosConstanciaDesdeEntrada(array $datos): array
    {
        $defProfesor = 'Profesor responsable';
        $defVobo = 'Subdirección de Planeación y Vinculación';
        $defDestinatario = 'Jefe del Departamento de Servicios Escolares';

        $profesor = trim((string) ($datos['cargo_profesor'] ?? ''));
        $vobo = trim((string) ($datos['cargo_vobo'] ?? ''));
        $destinatario = trim((string) ($datos['cargo_destinatario'] ?? ''));

        return [
            'cargo_profesor' => $profesor !== '' ? $profesor : $defProfesor,
            'cargo_vobo' => $vobo !== '' ? $vobo : $defVobo,
            'cargo_destinatario' => $destinatario !== '' ? $destinatario : $defDestinatario,
        ];
    }

    public static function cargoDestinatarioEvaluacion(Evaluacion $evaluacion): string
    {
        $def = 'Jefe del Departamento de Servicios Escolares';
        $destinatario = trim((string) ($evaluacion->cargo_destinatario ?? ''));
        if ($destinatario !== '') {
            return $destinatario;
        }

        return trim((string) ($evaluacion->cargo_docente ?? '')) ?: $def;
    }

    public static function asegurarEvaluacionExtraescolar(Evaluacion $evaluacion): void
    {
        $act = $evaluacion->actividad;
        if (! $act || ($act->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== Actividad::TIPO_EXTRAESCOLAR) {
            abort(404);
        }
    }

    /** @deprecated Use asegurarEvaluacionExtraescolar() */
    public static function asegurarEvaluacionExtraescolarValle(Evaluacion $evaluacion): void
    {
        self::asegurarEvaluacionExtraescolar($evaluacion);
    }
}
