<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permisos;
use App\Models\Vistas;
use App\Models\Persona;
use App\Models\Ninos;
use App\Models\Actividad;
use App\Models\Matriculacion;
use Illuminate\Support\Facades\DB;
class MatriculacionControler extends Controller

{
    public function getMatriculas(Request $request)
    {
        $start = $request->input('start', 0);   // Número de registros desde donde empezar
        $limit = $request->input('limit', 10);  // Número de registros a obtener

        // Consulta con paginación
        $matriculas = DB::table('inscripcion as m')
            ->join('ninos as n', 'n.id', '=', 'm.id_nino')
            ->join('persona as p', 'p.id', '=', 'n.id_persona')
            ->select(
                'm.id_nino',
                DB::raw('CONCAT(p.nombre, " ", p.apellido_paterno, " ", p.apellido_materno) AS nombre_completo'),
                'p.genero'
            )
            ->distinct()
            ->offset($start)
            ->limit($limit)
            ->get();

        // Total de registros para DataTables
        $totalRecords = DB::table('inscripcion as m')
        ->join('ninos as n', 'n.id', '=', 'm.id_nino')
        ->join('persona as p', 'p.id', '=', 'n.id_persona')
        ->select('m.id_nino')
        ->distinct()
        ->count('m.id_nino');

        return response()->json([
            'data' => $matriculas,
            'totalRecords' => $totalRecords
        ]);
    }

    public function obtenerTutor($id_nino)
    {
        $result = DB::table('ninos as m')
            ->join('persona as per', 'per.id', '=', 'm.id_padre')
            ->join('apoderado as apo', 'apo.id', '=', 'm.id_padre')
            ->where('m.id', $id_nino)
            ->select('per.nombre', 'per.apellido_paterno', 'per.apellido_materno', 'per.genero', 'apo.relacion')
            ->get();
    
        if ($result->isEmpty()) {
            return response()->json(['message' => 'Tutor no encontrado'], 404);
        }
    
        return response()->json($result);
    }
    
    public function obtenerHorarios($id_nino)
{
    $result = DB::table('inscripcion as m')
        ->join('horario as h', 'h.id', '=', 'm.id_horario')
        ->join('curso as c', 'c.id', '=', 'h.id_curso')
        ->select('h.dia_semana', 'h.turno', 'h.hora_inicio', 'h.hora_fin', 'c.nombre as nombre_curso')
        ->where('m.id_nino', $id_nino)
        ->get();

        if ($result->isEmpty()) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

    return response()->json($result);
}

public function obtenerHorarioscurso($id_curso)
{
    $result = DB::table('inscripcion as m')
        ->join('horario as h', 'h.id', '=', 'm.id_horario')
        ->join('curso as c', 'c.id', '=', 'h.id_curso')
        ->select('h.dia_semana', 'h.turno', 'h.hora_inicio', 'h.hora_fin', 'c.nombre as nombre_curso')
        ->where('m.id_nino', $id_nino)
        ->get();

        if ($result->isEmpty()) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

    return response()->json($result);
}
    
public function obtenerHorarioPorCursoYAula(Request $request)
{
    $id_curso = $request->input('id_curso');
    $id_aula = $request->input('id_aula');

    // Validar los datos
    if (!$id_curso || !$id_aula) {
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Parámetros insuficientes: id_curso e id_aula son requeridos.',
            'data' => null
        ], 400);
    }

    // Realizar la consulta
    $result = DB::table('horario as h')
        ->join('curso as c', 'c.id', '=', 'h.id_curso')
        ->join('aula as a', 'a.id', '=', 'h.id_aula')
        ->select('h.*')
        ->where('h.id_curso', $id_curso)
        ->where('h.id_aula', $id_aula)
        ->get();

    // Manejo de la respuesta
    if ($result->isEmpty()) {
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Horario no encontrado.',
            'data' => null
        ], 404);
    }

    return response()->json([
        'codigoRespuesta' => 0,
        'mensaje' => 'Datos encontrados.',
        'data' => $result
    ]);
}


public function obtenerHorarioPorAula($id_aula)
{
    // Validar los datos
    if (!$id_aula) {
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Parámetros insuficientes: id_aula es requerido.',
            'data' => null
        ], 400);
    }

    // Realizar la consulta
    $result = DB::table('horario as h')
        ->join('curso as c', 'c.id', '=', 'h.id_curso')
        ->join('aula as a', 'a.id', '=', 'h.id_aula')
        ->select('h.*')
        ->where('h.id_aula', $id_aula)
        ->get();

    // Manejo de la respuesta
    if ($result->isEmpty()) {
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Horario no encontrado.',
            'data' => null
        ], 404);
    }

    return response()->json([
        'codigoRespuesta' => 0,
        'mensaje' => 'Datos encontrados.',
        'data' => $result
    ]);
}


}
