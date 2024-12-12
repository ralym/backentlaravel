<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $fillable = ['correo', 'password'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_usuario', 'id_usuario', 'id_rol');
    }
}
