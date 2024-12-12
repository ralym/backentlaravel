<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    use HasFactory;
     // Nombre de la tabla asociada (opcional si el nombre coincide con la convención)
     protected $table = 'piso';

     // Campos que pueden ser asignados en masa
     protected $fillable = ['nombre', 'created_at', 'updated_at'];
 
     /**
      * Relación con la tabla `aula`.
      * Un piso tiene muchas aulas.
      */
     public function aulas()
     {
         return $this->hasMany(Aula::class, 'id_piso');
     }
}
