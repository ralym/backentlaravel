<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';  // Nombre de la tabla
    protected $primaryKey = 'id';   // Clave primaria
    public $timestamps = true;      // Usar timestamps

    protected $fillable = [
        'id_rol',
        'id_vista',
        'permiso',
        'estado',
        'created_at',
        'updated_at'
    ];

    // Relación con el modelo Vista
    public function vista()
    {
        return $this->belongsTo(Vista::class, 'id_vista'); // Relación uno a muchos con Vista
    }

    // Relación con el modelo Rol (si tienes un modelo para roles)
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol'); // Relación uno a muchos con Rol
    }
}
