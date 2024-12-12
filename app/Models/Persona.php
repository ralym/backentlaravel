<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
      // Nombre de la tabla en la base de datos
      protected $table = 'persona';

      // Clave primaria
      protected $primaryKey = 'id';
  
      // Definir los campos que pueden ser llenados masivamente
      protected $fillable = [
          'nombre', 'apellido_paterno', 'apellido_materno', 
          'genero', 'fecha_nacimiento', 'tipo_identidad', 'numero_identificacion',
          'direccion', 'celular', 'estado'
      ];
  
      // Si la tabla tiene campos de timestamp, Laravel los maneja automáticamente
      public $timestamps = true;
// Relación uno a uno con el modelo Apoderado
public function apoderado()
{
    return $this->hasOne(Apodera::class, 'id_persona', 'id');
}
      
}
