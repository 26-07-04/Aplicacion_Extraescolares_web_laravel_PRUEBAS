<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidades';
    protected $primaryKey = 'id_unidad';

    protected $fillable = [
        'nombre_unidad',
        'id_semestre'
    ];

    public function actividades()
    {
        return $this->hasMany(\App\Models\Actividad::class, 'id_unidad', 'id_unidad');
    }
}