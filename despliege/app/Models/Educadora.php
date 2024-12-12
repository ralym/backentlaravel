<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Educadora extends Model
{
    use HasFactory;
    protected $table = 'educadora'; // Nombre de la tabla

    protected $fillable = [
        'id_persona',
        'id_rol',
        'especialidad',
        'experiencia',
        'fecha_ingreso',
        'estado',
    ]; // Atributos que se pueden asignar en masa

    // Relaciones con otros modelos (opcional)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
