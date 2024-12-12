<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\RolesUsuario;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AuthController extends Controller
{ 
   // Reglas de validación para login
   protected $reglasLogin = [
    'email' => 'required|email',
    'password' => 'required|min:6',
];

public function login(Request $request)
{
    try {
        // Validación de las credenciales
        $validated = $request->validate($this->reglasLogin);

        // Obtener los datos del formulario
        $correo = $request->input('email');
        $password = $request->input('password');

        // Verificación de usuario en la base de datos
        $datosUsuario = Usuario::where('correo', $correo)->first();

        if ($datosUsuario == null) {
            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'El correo no se encuentra registrado en el sistema.',
                'data' => null
            ], 404); // Usuario no encontrado
        }

        // Comparar la contraseña (debe ser una comparación segura con hash)
        // if (!password_verify($password, $datosUsuario['password'])) {
        //     return response()->json([
        //         'codigoRespuesta' => 1,
        //         'mensaje' => 'La contraseña que ingresaste es errónea.',
        //         'data' => null
        //     ], 401); // Error de contraseña
        // }

        // Obtener el rol y las vistas
        $idUsuario = $datosUsuario['id'];
        $idPersona = $datosUsuario['id_persona'];

        // Consultar roles del usuario
        $dataAllUserRoles = DB::table('roles_usuario')
            ->join('roles', 'roles.id', '=', 'roles_usuario.id_rol')
            ->where('roles_usuario.id_usuario', $idUsuario)
            ->get();

        // Consultar rol activo
        $dataActiveRol = DB::table('roles_usuario')
            ->join('roles', 'roles.id', '=', 'roles_usuario.id_rol')
            ->where('roles_usuario.id_usuario', $idUsuario)
            ->where('roles_usuario.estado', 'activo')
            ->first();

        if (empty($dataActiveRol)) {
            return response()->json([
                'codigoRespuesta' => 1,
                'mensaje' => 'No tienes un rol activo.',
                'data' => null
            ], 403); // Rol no activo
        }

        $idRol = $dataActiveRol->id_rol;
        $idRolUsuario = $dataActiveRol->id;
        $nombreRol = $dataActiveRol->nombre;

        // Obtener los datos de la persona
        $datosPersona = Persona::where('id', $idPersona)->first();

        // Consultar vistas disponibles para el rol
        // $dataVistas = DB::table('vistas')
        //     //->select('vistas.nombre') // Asegúrate de incluir 'nombre' aquí.
        //     ->join('permisos', 'permisos.id_vista', '=', 'vistas.id')
        //     ->join('roles', 'roles.id', '=', 'permisos.id_rol')
        //     ->where('roles.id', $idRol)
        //     ->where('permisos.permiso', 'read')
        //     ->get();
        $dataVistas = DB::table('vistas')
    ->select(
        'vistas.id as id_vista',
        'vistas.nombre as nombrevista', // Seleccionamos el campo adicional
        'vistas.descripcion',
        'vistas.ruta',
        'vistas.icono',
        'vistas.estado',
        'vistas.created_at',
        'vistas.updated_at',
        'permisos.id_rol',
        'permisos.permiso'
    )
    ->join('permisos', 'permisos.id_vista', '=', 'vistas.id')
    ->join('roles', 'roles.id', '=', 'permisos.id_rol')
    ->where('roles.id', $idRol)
    ->where('permisos.permiso', 'read')
    ->get();


        // Preparar los datos de la sesión
        $datosSesion = [
            "id_rol" => $idRol,
            "id_rol_usuario" => $idRolUsuario,
            "nombre_rol" => $nombreRol,
            "roles" => $dataAllUserRoles,
            "data_vistas" => $dataVistas,
            "dato_user" => $datosUsuario,
            "dato_persona" => $datosPersona,
        ];

        // Respuesta exitosa con los datos del usuario y sesión
        return response()->json([
            'codigoRespuesta' => 0,
            'mensaje' => 'Inicio de sesión exitoso.',
            'data' => $datosSesion
        ], 200); // Respuesta exitosa

    } catch (\Illuminate\Validation\ValidationException $e) {
        // En caso de fallo de validación
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    }
}


    public function login2(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $correo = $request->input('email');
        $password = $request->input('password'); // Se asume que la contraseña recibida es en texto claro

        // Buscar el usuario en la base de datos
        $datosUsuario = users::where('correo', $correo)->first();

        // Verificar si el correo no está registrado
        if (!$datosUsuario) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'El correo no se encuentra registrado en el sistema.',
                'fechaConsta' => Carbon::now()->format('d/M/Y'),
                'data' => null
            ], 401); // 401 Unauthorized
        }

        // Verificar la contraseña recibida
        if (!Hash::check($password, $datosUsuario->password)) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'La contraseña que ingresaste es errónea.',
                'fechaConsta' => Carbon::now()->format('d/M/Y'),
                'data' => null
            ], 401); // 401 Unauthorized
        }

        // Obtener los roles del usuario
        $allUserRoles = DB::table('roles_usuario')
            ->join('roles', 'roles.id', '=', 'roles_usuario.id_rol')
            ->where('roles_usuario.id_usuario', $datosUsuario->id)
            ->get();

        // Obtener el rol activo
        $activeRole = DB::table('roles_usuario')
            ->join('roles', 'roles.id', '=', 'roles_usuario.id_rol')
            ->where('roles_usuario.id_usuario', $datosUsuario->id)
            ->where('roles_usuario.estado', 'activo')
            ->first();

        if (!$activeRole) {
            return response()->json([
                'codigoRespuesta' => 0,
                'mensaje' => 'El usuario no tiene un rol activo.',
                'fechaConsta' => Carbon::now()->format('d/M/Y'),
                'data' => null
            ], 401); // 401 Unauthorized
        }

        // Obtener las vistas relacionadas al rol
        $vistas = DB::table('vistas')
            ->join('permisos', 'permisos.id_vista', '=', 'vistas.id')
            ->join('roles', 'roles.id', '=', 'permisos.id_rol')
            ->where('roles.id', $activeRole->id_rol)
            ->where('permisos.permiso', 'read')
            ->get();

        // Preparar los datos de sesión
        $datosSesion = [
            'id_rol' => $activeRole->id_rol,
            'id_rol_usuario' => $activeRole->id,
            'nombre_rol' => $activeRole->nombre,
            'roles' => $allUserRoles,
            'data_vistas' => $vistas,
            'dato_user' => $datosUsuario,
            'dato_persona' => $datosUsuario->persona, // Asumiendo que 'persona' es una relación
        ];

        return response()->json([
            'codigoRespuesta' => 1,
            'mensaje' => 'Login exitoso',
            'fechaConsta' => Carbon::now()->format('d/M/Y'),
            'data' => $datosSesion
        ], 200); // 200 OK
    }
}
