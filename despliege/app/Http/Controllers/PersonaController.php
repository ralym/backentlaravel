<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Apodera;
use App\Models\Nino;
class PersonaController extends Controller
{

 
    public function buscar(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'tipo_identidad' => 'nullable|string|max:1',
            'numero_identificacion' => 'nullable|string',
            'fecha_nacimiento' => 'required|date_format:Y-m-d',
        ]);
    
        // Obtener los datos enviados en el cuerpo de la solicitud
        $tipoIdentidad = $request->input('tipo_identidad');
        $numeroIdentificacion = $request->input('numero_identificacion');
        $fechaNacimiento = $request->input('fecha_nacimiento');
    
        // Verificar que al menos uno de los campos tenga un valor
        if (empty($tipoIdentidad) && empty($numeroIdentificacion) && empty($fechaNacimiento)) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Debe proporcionar al menos un criterio de búsqueda.',
                'data' => [],
            ]);
        }
    
        // Construir la consulta dinámicamente
        $query = \DB::table('persona as p')
            ->join('apoderado as a', 'a.id_persona', '=', 'p.id')
            ->select(
                'a.relacion',
                'p.tipo_identidad',
                'p.numero_identificacion',
                'p.fecha_nacimiento',
                'p.nombre',
                'p.apellido_paterno',
                'p.apellido_materno',
                'a.grado_estudio',
                'a.profesion',
                'a.ocupacion_actual',
                'a.lugar_trabajo',
                'a.celular'
            );
    
        // Agregar condiciones dinámicamente según los campos llenos
        if (!empty($tipoIdentidad)) {
            $query->where('p.tipo_identidad', $tipoIdentidad);
        }
        if (!empty($numeroIdentificacion)) {
            $query->where('p.numero_identificacion', $numeroIdentificacion);
        }
        if (!empty($fechaNacimiento)) {
            $query->where('p.fecha_nacimiento', $fechaNacimiento);
        }
    
        // Ejecutar la consulta
        $resultados = $query->get();
    
        // Verificar si se encontraron resultados
        if ($resultados->isEmpty()) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'No se encontró ninguna persona que coincida con los criterios.',
                'data' => [],
            ]);
        }
    
        // Retornar resultados si se encuentran personas
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Persona encontrada.',
            'data' => $resultados,
        ]);
    }
    
    public function buscarNinos(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'tipo_identidad' => 'required|string|max:1',
            'numero_identificacion' => 'required|string',
            'fecha_nacimiento' => 'required|date',
        ]);
    
        // Obtener los datos enviados en el cuerpo de la solicitud
        $tipoIdentidad = $request->input('tipo_identidad');
        $numeroIdentificacion = $request->input('numero_identificacion');
        $fechaNacimiento = $request->input('fecha_nacimiento');
    
        // Consulta con la relación 'ninos'
        $resultados = \DB::table('persona as p')
            ->join('ninos as a', 'a.id_persona', '=', 'p.id')
            ->where('p.tipo_identidad', $tipoIdentidad)
            ->where('p.numero_identificacion', $numeroIdentificacion)
            ->where('p.fecha_nacimiento', $fechaNacimiento)
            ->select(
                'p.tipo_identidad',
                'p.numero_identificacion',
                'p.fecha_nacimiento',
                'p.nombre',
                'p.apellido_paterno',
                'p.apellido_materno',
                'a.telefono_emergencia',
                'a.condiciones_medicas'
            )
            ->get();
    
        // Si no se encuentran resultados
        if ($resultados->isEmpty()) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'No se encontró ninguna persona que coincida con los criterios.',
                'data' => []
            ]);
        }
    
        // Si se encuentran resultados
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Persona encontrada correctamente.',
            'data' => $resultados
        ]);
    }
    
    public function buscarnormal(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'tipo_identidad' => 'nullable|string|max:1',
            'numero_identificacion' => 'nullable|string',
            'fecha_nacimiento' => 'required|date_format:Y-m-d',
        ]);
    
        // Obtener los datos enviados en el cuerpo de la solicitud
        $tipoIdentidad = $request->input('tipo_identidad');
        $numeroIdentificacion = $request->input('numero_identificacion');
        $fechaNacimiento = $request->input('fecha_nacimiento');
    
        // Verificar que al menos uno de los campos tenga un valor
        if (empty($tipoIdentidad) && empty($numeroIdentificacion) && empty($fechaNacimiento)) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'Debe proporcionar al menos un criterio de búsqueda.',
                'data' => [],
            ]);
        }
    
        // Construir la consulta dinámicamente
        $query = \DB::table('persona as p')
            ->select(
                'p.id',
                'p.tipo_identidad',
                'p.numero_identificacion',
                'p.fecha_nacimiento',
                'p.nombre',
                'p.apellido_paterno',
                'p.apellido_materno',
                'p.genero',
                'p.direccion',
                'p.celular'
            );
    
        // Agregar condiciones dinámicamente según los campos llenos
        if (!empty($tipoIdentidad)) {
            $query->where('p.tipo_identidad', $tipoIdentidad);
        }
        if (!empty($numeroIdentificacion)) {
            $query->where('p.numero_identificacion', $numeroIdentificacion);
        }
        if (!empty($fechaNacimiento)) {
            $query->where('p.fecha_nacimiento', $fechaNacimiento);
        }
    
        // Ejecutar la consulta
        $resultados = $query->get();
    
        // Verificar si se encontraron resultados
        if ($resultados->isEmpty()) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'No se encontró ninguna persona que coincida con los criterios.',
                'data' => [],
            ]);
        }
    
        // Retornar resultados si se encuentran personas
        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Persona encontrada.',
            'data' => $resultados,
        ]);
    }
}
