<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nino extends Model
{
    use HasFactory;
    protected $table = 'ninos';
    protected $primaryKey = 'id';
    protected $fillable = ['id_persona', 'id_padre', 'telefono_emergencia', 'condiciones_medicas', 'estado'];

    public $timestamps = true; // Activar timestamps (created_at, updated_at)
}
