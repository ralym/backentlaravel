<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;

use Illuminate\Support\Facades\DB; // Importa DB correctamente
class AulaController extends Controller
{


    public function getByPiso($idPiso)
    {
        try {
            // Query con subconsulta para obtener la cantidad de inventario
            $aulas = Aula::where('id_piso', $idPiso)
                ->select([
                    'id',
                    'id_piso',
                    'nombre',
                    'created_at',
                    'updated_at',
                    DB::raw('(SELECT COUNT(1) FROM inventario i WHERE i.id_aula = aula.id) AS cantidad_inventario')
                ])
                ->get();
    
            $cantidad = $aulas->count();
    
            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Aulas obtenidas correctamente',
                'data' => [
                    'cantidad' => $cantidad,
                    'aulas' => $aulas
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al obtener las aulas',
                'data' => null
            ], 500);
        }
    }
    
    

    // Obtener un aula específica por su ID
    public function show($id)
    {
        $aula = Aula::find($id);
        if (!$aula) {
            return response()->json(['message' => 'Aula no encontrada'], 404);
        }
        return response()->json($aula);
    }

    // Crear un aula
    public function store(Request $request)
    {
        $request->validate([
            'id_piso' => 'required|exists:piso,id',
            'nombre' => 'required|string|max:255',
        ]);

        $aula = Aula::create($request->all());
        return response()->json($aula, 201);
    }

    // Actualizar un aula
    public function update(Request $request, $id)
    {
        $aula = Aula::find($id);
        if (!$aula) {
            return response()->json(['message' => 'Aula no encontrada'], 404);
        }

        $request->validate([
            'id_piso' => 'required|exists:piso,id',
            'nombre' => 'required|string|max:255',
        ]);

        $aula->update($request->all());
        return response()->json($aula);
    }

    // Eliminar un aula
    public function destroy($id)
    {
        $aula = Aula::find($id);
        if (!$aula) {
            return response()->json(['message' => 'Aula no encontrada'], 404);
        }

        $aula->delete();
        return response()->json(['message' => 'Aula eliminada con éxito']);
    }
}
