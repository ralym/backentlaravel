<?php
/**
 * @OA\Schema(
 *     schema="Usuario",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="correo", type="string"),
 *     // Agrega más propiedades según sea necesario
 * )
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'usuario';

    // Clave primaria
    protected $primaryKey = 'id';

    // Indica que la clave primaria no es un entero autoincremental
    public $incrementing = true; // Cambia a false si la clave primaria no es autoincremental

    // Tipo de datos de la clave primaria
    protected $keyType = 'int'; // Cambia a 'string' si es de tipo string

    // Si tu tabla tiene timestamps (created_at y updated_at)
    public $timestamps = true;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['correo', 'id_persona', 'password', 'estado'];

    // Campos que deben ser ocultados en las respuestas JSON (opcional)
    protected $hidden = ['password']; // Oculta la contraseña si no quieres mostrarla
     // Mutadores para formatear las fechas
    protected $dates = ['created_at', 'updated_at'];

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H-i-s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H-i-s');
    }
    public function rolesUsuario()
{
    return $this->hasMany(RolesUsuario::class, 'id_usuario');
}
}
