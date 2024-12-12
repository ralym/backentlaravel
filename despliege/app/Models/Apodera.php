<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apodera extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'apoderado';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_persona', 'relacion','grado_estudio','profesion','ocupacion_actual','lugar_trabajo','celular'
    ];

    public $timestamps = true;

    // Relación inversa con el modelo Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
}
