<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $table = 'informes';


    protected $fillable = [
        'titulo',
        'descripcion',
        'archivo',
        'fecha_generacion',
        'id_semestre',
        'id_unidad',
        'tipo_programa',
    ];

    /**
     * @return 'extraescolar'|'complementaria'
     */
    public static function tipoProgramaValido(?string $tipo): string
    {
        if ($tipo === Actividad::TIPO_COMPLEMENTARIA) {
            return Actividad::TIPO_COMPLEMENTARIA;
        }

        return Actividad::TIPO_EXTRAESCOLAR;
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre');
    }

    /**
     * Informes guardados para un semestre y una unidad académica (panel coordinador).
     */
    public static function listadoGeneradosPorUnidad(?int $idSemestre, int $idUnidad, ?string $tipoPrograma = null): Collection
    {
        if (! $idSemestre || $idUnidad < 1) {
            return new Collection();
        }

        $tipoPrograma = self::tipoProgramaValido($tipoPrograma);

        return static::query()
            ->where('id_semestre', $idSemestre)
            ->where('id_unidad', $idUnidad)
            ->where('tipo_programa', $tipoPrograma)
            ->whereNotNull('archivo')
            ->where('archivo', '!=', '')
            ->orderByDesc('fecha_generacion')
            ->get();
    }

}
