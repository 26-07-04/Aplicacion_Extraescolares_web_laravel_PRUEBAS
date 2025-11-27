<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_semestre',
        'archivo',
    ];

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre');
    }
}
