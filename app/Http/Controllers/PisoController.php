<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piso;

class PisoController extends Controller
{
    // Obtener todos los pisos
    public function index()
    {
        return response()->json(Piso::all());
    }

    // Obtener un piso por su ID
    public function show($id)
    {
        $piso = Piso::find($id);
        if (!$piso) {
            return response()->json(['message' => 'Piso no encontrado'], 404);
        }
        return response()->json($piso);
    }

    // Crear un nuevo piso
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $piso = Piso::create($request->all());
        return response()->json($piso, 201);
    }

    // Actualizar un piso
    public function update(Request $request, $id)
    {
        $piso = Piso::find($id);
        if (!$piso) {
            return response()->json(['message' => 'Piso no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $piso->update($request->all());
        return response()->json($piso);
    }

    // Eliminar un piso
    public function destroy($id)
    {
        $piso = Piso::find($id);
        if (!$piso) {
            return response()->json(['message' => 'Piso no encontrado'], 404);
        }

        $piso->delete();
        return response()->json(['message' => 'Piso eliminado con éxito']);
    }
}
