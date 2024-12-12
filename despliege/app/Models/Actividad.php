<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

     // Nombre de la tabla en la base de datos
     protected $table = 'actividad';

     // Clave primaria de la tabla
     protected $primaryKey = 'id';
 
     // Campos que se permiten llenar de forma masiva
     protected $fillable = [
         'dia',
         'turno',
         'descripcion',
         'hora_inicio',
         'hora_fin',
         'id_educadora',
         'id_aula',
     ];
 
}
