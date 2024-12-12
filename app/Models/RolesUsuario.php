<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesUsuario extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'roles_usuario';

    // Clave primaria
    protected $primaryKey = 'id';

    // Definir los campos que pueden ser llenados masivamente
    protected $fillable = ['id_rol', 'id_usuario', 'estado'];

    // Si la tabla tiene campos de timestamp, Laravel los maneja automáticamente
    public $timestamps = true;
    public function usuario()
{
    return $this->belongsTo(Usuario::class, 'id_usuario');
}
}
