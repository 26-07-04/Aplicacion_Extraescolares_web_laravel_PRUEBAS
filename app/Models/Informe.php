<?php
namespace App\Models;

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
    ];

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre');
    }

}
