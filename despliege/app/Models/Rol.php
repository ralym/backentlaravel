<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'roles';       // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id';     // Clave primaria
    public $timestamps = true;        // Usar timestamps

    protected $fillable = [
        'nombre',
        'estado',
        'created_at',
        'updated_at'
    ];

    // Relación con permisos
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_rol'); // Relación uno a muchos con Permiso
    }
}
