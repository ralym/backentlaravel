<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    use HasFactory;

    protected $table = 'permisos';
    protected $primaryKey = 'id';
    protected $fillable = ['id_rol', 'id_vista', 'permiso', 'estado'];

    public $timestamps = true; // Activar timestamps
}
