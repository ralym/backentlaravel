<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;
   // Nombre de la tabla asociada (opcional si el nombre coincide con la convención)
   protected $table = 'aula';

   // Campos que pueden ser asignados en masa
   protected $fillable = ['id_piso', 'nombre', 'created_at', 'updated_at'];

   /**
    * Relación con la tabla `piso`.
    * Un aula pertenece a un piso.
    */
   public function piso()
   {
       return $this->belongsTo(Piso::class, 'id_piso');
   }
}
