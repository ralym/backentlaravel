<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
class CursoController extends Controller
{
     // Obtener la lista de cursos con paginación
     public function getCursos(Request $request)
     {
         $start = $request->input('start', 0);   // Número de registros desde donde empezar
         $limit = $request->input('limit', 10);  // Número de registros a obtener
 
         // Consultar cursos con paginación
         $cursos = Curso::offset($start)
             ->limit($limit)
             ->get();
 
         // Total de registros para DataTables
         $totalRecords = Curso::count();
 
         return response()->json([
             'data' => $cursos,
             'totalRecords' => $totalRecords,
         ]);
     }
 
     // Obtener un curso específico por ID
     public function getCurso($id)
     {
         $curso = Curso::find($id);
 
         if (!$curso) {
             return response()->json(['message' => 'Curso no encontrado'], 404);
         }
 
         return response()->json($curso);
     }
 
     // Crear un nuevo curso
     public function createCurso(Request $request)
     {
         $request->validate([
             'nombre' => 'required|string|max:255',
             'descripcion' => 'required|string|max:500',
            'estado' => 'required|in:Activo,Inactivo', // Acepta solo 'Activo' o 'Inactivo'
         ]);
     
         // Crear el nuevo curso
         $curso = Curso::create([
             'nombre' => $request->nombre,
             'descripcion' => $request->descripcion,
             'estado' => $request->estado,
         ]);
     
         return response()->json(['message' => 'Curso creado con éxito', 'curso' => $curso], 201);
     }
     
 
     // Actualizar un curso existente
     public function updateCurso(Request $request, $id)
     {
         $curso = Curso::find($id);
 
         if (!$curso) {
             return response()->json(['message' => 'Curso no encontrado'], 404);
         }
 
         // Validar y actualizar los datos
         $request->validate([
             'nombre' => 'required|string|max:255',
             'estado' => 'required|in:Activo,Inactivo', // Acepta solo 'Activo' o 'Inactivo'
         ]);
 
         $curso->update([
             'nombre' => $request->nombre,
             'estado' => $request->estado,
         ]);
 
         return response()->json(['message' => 'Curso actualizado con éxito', 'curso' => $curso]);
     }
 
     // Eliminar un curso
     public function deleteCurso($id)
     {
         $curso = Curso::find($id);
 
         if (!$curso) {
             return response()->json(['message' => 'Curso no encontrado'], 404);
         }
 
         // Eliminar el curso
         $curso->delete();
 
         return response()->json(['message' => 'Curso eliminado con éxito']);
     }

    }