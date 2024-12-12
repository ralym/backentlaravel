<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vista;
class PermissionController extends Controller
{
    public function getPermissions(Request $request)
    {
        // Obtener vistas con permisos y los roles asociados a cada permiso
        $data = Vista::with(['permisos.rol'])->get(); // Cargar permisos y rol de cada permiso

        $responseData = [];
        foreach ($data as $vista) {
            $permissions = $vista->permisos->pluck('permiso')->toArray();
            $roles = $vista->permisos->pluck('rol.nombre')->unique()->toArray(); // Usar unique para roles

            $responseData[] = [
                'id' => $vista->id,
                'nombre' => $vista->nombre,
                'descripcion' => $vista->descripcion,
                'ruta' => $vista->ruta,
                'icono' => $vista->icono,
                'estado' => $vista->estado,
                'permissions' => implode(' | ', $permissions),
                'roles' => implode(', ', $roles), // Convertir a cadena
            ];
        }

        return response()->json($responseData);
    }
}
