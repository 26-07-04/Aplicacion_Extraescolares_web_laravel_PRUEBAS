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
}