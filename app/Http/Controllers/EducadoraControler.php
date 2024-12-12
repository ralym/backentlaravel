<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EducadoraControler extends Controller
{
    public function getEducadoras(Request $request)
    {
        $start = $request->input('start', 0);   // Número de registros desde donde empezar
        $limit = $request->input('limit', 10);  // Número de registros a obtener

        // Consulta con paginación
        $educadoras = DB::table('educadora as e')
            ->join('persona as p', 'e.id_persona', '=', 'p.id')
            ->select(
                DB::raw("CONCAT(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno) AS nombre_completo"),
                'p.id',
                'p.numero_identificacion',
                'p.celular',
                'e.especialidad',
                'e.fecha_ingreso'
            )
            ->offset($start)
            ->limit($limit)
            ->get();

        // Total de registros para DataTables
        $totalRecords = DB::table('educadora as e')
            ->join('persona as p', 'e.id_persona', '=', 'p.id')
            ->select('e.id')
            ->count();

        return response()->json([
            'data' => $educadoras,
            'totalRecords' => $totalRecords
        ]);
    }

// Obtener una educadora específica por ID
public function getEducadora($id)
{
    $educadora = DB::table('educadora as e')
        ->join('persona as p', 'e.id_persona', '=', 'p.id')
        ->select(
            'p.id',
            DB::raw("CONCAT(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno) AS nombre_completo"),
            'p.numero_identificacion',
            'p.celular',
            'e.especialidad',
            'e.fecha_ingreso'
        )
        ->where('e.id_persona', $id)
        ->first();

    if (!$educadora) {
        return response()->json(['message' => 'Educadora no encontrada'], 404);
    }

    return response()->json($educadora);
}

// Crear una nueva educadora
public function createEducadora(Request $request)
{
    $request->validate([
        'id_persona' => 'required|exists:persona,id', // Validar que la persona exista
        'especialidad' => 'required|string|max:255',
        'fecha_ingreso' => 'required|date',
    ]);

    // Crear la nueva educadora
    $educadora = new Educadora();
    $educadora->id_persona = $request->id_persona;
    $educadora->especialidad = $request->especialidad;
    $educadora->fecha_ingreso = $request->fecha_ingreso;
    $educadora->estado = $request->estado ?? 1; // Si no se pasa, por defecto está activa
    $educadora->save();

    return response()->json(['message' => 'Educadora creada con éxito', 'educadora' => $educadora], 201);
}

// Actualizar una educadora existente
public function updateEducadora(Request $request, $id)
{
    $educadora = Educadora::find($id);

    if (!$educadora) {
        return response()->json(['message' => 'Educadora no encontrada'], 404);
    }

    // Validar y actualizar los datos
    $request->validate([
        'id_persona' => 'required|exists:persona,id',
        'especialidad' => 'required|string|max:255',
        'fecha_ingreso' => 'required|date',
    ]);

    $educadora->id_persona = $request->id_persona;
    $educadora->especialidad = $request->especialidad;
    $educadora->fecha_ingreso = $request->fecha_ingreso;
    $educadora->estado = $request->estado ?? $educadora->estado; // Si no se pasa, mantiene el actual
    $educadora->save();

    return response()->json(['message' => 'Educadora actualizada con éxito', 'educadora' => $educadora]);
}

// Eliminar una educadora
public function deleteEducadora($id)
{
    $educadora = Educadora::find($id);

    if (!$educadora) {
        return response()->json(['message' => 'Educadora no encontrada'], 404);
    }

    // Eliminar la educadora
    $educadora->delete();

    return response()->json(['message' => 'Educadora eliminada con éxito']);
}

}
