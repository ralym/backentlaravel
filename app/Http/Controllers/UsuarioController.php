<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class UsuarioController extends Controller
{
    public function login(Request $request)
    {
        $user = Usuario::where('correo', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token, 'vistas' => $user->vistas]);
        }
        return response()->json(['mensaje' => 'Credenciales inválidas'], 401);
    }



   // Obtener todos los usuarios
   public function index()
   {
       $usuarios = Usuario::all(); // Obtiene todos los usuarios
       return response()->json($usuarios); // Retorna como JSON
   }

   // Crear un nuevo usuario
   public function store(Request $request)
   {
       // Validación de datos
       $request->validate([
           'correo' => 'required|email',
           'id_persona' => 'required|integer',
           'password' => 'required|min:6',
           'estado' => 'required|boolean',
       ]);

       $usuario = Usuario::create($request->all()); // Crea el usuario
       return response()->json($usuario, 201); // Retorna el usuario creado con estado 201
   }

   // Obtener un usuario específico
   public function show($id)
   {
       $usuario = Usuario::find($id); // Busca el usuario por ID
       if ($usuario) {
           return response()->json($usuario); // Retorna el usuario encontrado
       } else {
           return response()->json(['message' => 'Usuario no encontrado'], 404); // Retorna error 404 si no se encuentra
       }
   }

   // Actualizar un usuario existente
   public function update(Request $request, $id)
   {
       $usuario = Usuario::find($id);
   
       if ($usuario) {
           // Excluir campos que no quieres actualizar
           $data = $request->except('id'); // No actualizamos el campo 'id'
   
           // Validar los datos si es necesario
           $request->validate([
               'correo' => 'sometimes|email',
               'estado' => 'sometimes|in:activo,inactivo',
           ]);
   
           // Actualizar el usuario
           $usuario->update($data);
   
           return response()->json($usuario); // Retornar el usuario actualizado
       } else {
           return response()->json(['message' => 'Usuario no encontrado'], 404); // Error 404 si no existe
       }
   }
   
   // Eliminar un usuario
   public function destroy($id)
   {
       $usuario = Usuario::find($id);
       if ($usuario) {
           $usuario->delete(); // Elimina el usuario
           return response()->json(['message' => 'Usuario eliminado']); // Retorna un mensaje de éxito
       } else {
           return response()->json(['message' => 'Usuario no encontrado'], 404); // Retorna error 404 si no se encuentra
       }
   }
   public function fetchUsers(Request $request)
    {
        $start = $request->input('start', 0); // Valor por defecto 0 si no se proporciona
        $length = $request->input('length', 10); // Valor por defecto 10 si no se proporciona
        $search = $request->input('search.value', ''); // Búsqueda

        // Total de registros
        $totalRecords = Usuario::count();
        
        // Filtrar registros
        $query = Usuario::query();

        if ($search) {
            $query->where('correo', 'like', '%' . $search . '%')
                  ->orWhere('estado', 'like', '%' . $search . '%');
        }

        $totalRecordwithFilter = $query->count(); // Total con filtro

        // Obtener registros filtrados y paginados
        $records = $query->orderBy('id', 'DESC')
                         ->offset($start)
                         ->limit($length)
                         ->get();

        $data = [];
        foreach ($records as $record) {
            $data[] = [
                'id' => $record->id,
                'correo' => $record->correo,
                'estado' => $record->estado,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
                
            ];
        }

        // Respuesta JSON
        return response()->json([
            "draw" => intval($request->input('draw', 1)), // Valor por defecto 1 si no se proporciona
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data,
        ]);
    }
}
