<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actividad extends Model
{
    use HasFactory;

    public const TIPO_EXTRAESCOLAR = 'extraescolar';

    public const TIPO_COMPLEMENTARIA = 'complementaria';

    protected $table = 'actividades';
    protected $primaryKey = 'id_actividad';
    public $timestamps = true;

    // permitir asignación masiva (evita MassAssignmentException)
    protected $fillable = [
        'nombre_actividad',
        'descripcion',
        'id_unidad',
        'id_semestre',
        'imagen_url',
        'categorias',
        'tipo_programa',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id_unidad');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre', 'id_semestre');
    }

    /**
     * Mapea el texto de categorías (p. ej. "Cultural", "Deportivo") a la clave usada en el informe.
     */
    public function categoriaParaInforme(): ?string
    {
        return self::resolverCategoriaInforme($this->categorias ?? null);
    }

    /**
     * @return 'cultural'|'deportiva'|null
     */
    public static function resolverCategoriaInforme(?string $categorias): ?string
    {
        $c = mb_strtolower(trim((string) $categorias), 'UTF-8');
        if ($c === '') {
            return null;
        }
        if (str_contains($c, 'cultural')) {
            return 'cultural';
        }
        if (str_contains($c, 'deportiva')
            || str_contains($c, 'deportivo')
            || str_contains($c, 'deporte')) {
            return 'deportiva';
        }

        return null;
    }
}
