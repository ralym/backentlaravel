<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vista extends Model
{
    use HasFactory;
    protected $table = 'vistas'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Clave primaria de la tabla
    public $timestamps = true; // Usar timestamps (created_at y updated_at)

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'ruta',
        'icono',
        'estado',
    ];

    // Si necesitas definir relaciones, puedes hacerlo aquí
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_vista'); // Cambia 'Permiso' por el modelo que corresponda
    }

    public function roles()
    {
        return $this->hasManyThrough(Rol::class, Permiso::class, 'id_vista', 'id_rol', 'id', 'id'); // Cambia 'Rol' por el modelo que corresponda
    }
}
