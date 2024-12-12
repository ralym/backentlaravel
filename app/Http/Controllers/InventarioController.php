<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
class InventarioController extends Controller
{
    public function index()
    {
        try {
            $inventarios = Inventario::all();
            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Datos obtenidos correctamente',
                'data' => $inventarios,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al obtener los datos',
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_producto' => 'required|string|max:255',
                'cantidad' => 'required|integer',
                'id_aula' => 'required|integer',
            ]);

            $inventario = Inventario::create($validated);

            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Producto creado exitosamente',
                'data' => $inventario,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al crear el producto',
                'data' => null,
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $inventario = Inventario::findOrFail($id);

            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Producto encontrado',
                'data' => $inventario,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Producto no encontrado',
                'data' => null,
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nombre_producto' => 'sometimes|required|string|max:255',
                'cantidad' => 'sometimes|required|integer',
                'id_aula' => 'sometimes|required|integer',
            ]);

            $inventario = Inventario::findOrFail($id);
            $inventario->update($validated);

            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Producto actualizado exitosamente',
                'data' => $inventario,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al actualizar el producto',
                'data' => null,
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $inventario = Inventario::findOrFail($id);
            $inventario->delete();

            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Producto eliminado correctamente',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al eliminar el producto',
                'data' => null,
            ], 500);
        }
    }
    // Obtener todos los productos de un aula específica
    public function getInventarioPorAula($id_aula)
    {
        try {
            $inventarios = Inventario::where('id_aula', $id_aula)
                ->join('aula', 'aula.id', '=', 'inventario.id_aula')
                ->select('inventario.*', 'aula.nombre as nombre_aula')
                ->get();

            if ($inventarios->isEmpty()) {
                return response()->json([
                    'codigoRespuesta' => 0,
                    'mensaje' => 'No se encontraron productos para esta aula',
                    'data' => null,
                ]);
            }

            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $inventarios,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al obtener productos',
                'data' => null,
            ], 500);
        }
    }

    // Actualizar un producto específico por aula e inventario
    public function actualizarPorAula(Request $request, $id_aula, $id)
    {
        try {
            $validated = $request->validate([
                'nombre_producto' => 'sometimes|required|string|max:255',
                'cantidad' => 'sometimes|required|integer',
            ]);

            $inventario = Inventario::where('id_aula', $id_aula)
                ->where('id', $id)
                ->first();

            if (!$inventario) {
                return response()->json([
                    'codigoRespuesta' => 0,
                    'mensaje' => 'Producto no encontrado para esta aula',
                    'data' => null,
                ], 404);
            }

            $inventario->update($validated);

            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'Producto actualizado correctamente',
                'data' => $inventario,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Error al actualizar producto',
                'data' => null,
            ], 500);
        }
    }
}
