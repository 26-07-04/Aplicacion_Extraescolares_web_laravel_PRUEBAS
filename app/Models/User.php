<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Semestre;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'numero_control',
        'carrera',
        'semestre',
        'actividad_extraescolar',
        'contrasena',
        'contrasena_texto',
        'email',
        'password',
        'rol',
        'id_semestre',
        'unidad_academica',
        'contacto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Table and primary key customization to match the new `usuarios` table.
     */
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Devuelve el campo que almacena la contraseña para el guard de autenticación.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Relación con el semestre al que pertenece este usuario.
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class, 'id_semestre', 'id_semestre');
    }

    /**
     * Scope para filtrar usuarios por un semestre dado.
     */
    public function scopeOfSemestre($query, $id)
    {
        return $query->where('id_semestre', $id);
    }
}
