<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Asegúrate de incluir esta línea
use Illuminate\Support\Facades\App; // Asegúrate de incluir esta línea para obtener la versión de Laravel

class InfoController extends Controller
{
    public function info()
    {
        // Obtener información de la conexión a la base de datos
        $dbConnection = config('database.default'); // Obtiene el tipo de conexión (mysql, pgsql, etc.)
        $dbName = DB::connection()->getDatabaseName(); // Obtiene el nombre de la base de datos

        // Crear la respuesta
        $response = [
            'laravel_version' => app()->version(),
            'database' => [
                'connection' => $dbConnection,
                'database_name' => $dbName,
            ],
        ];

        return response()->json($response);
    }
}
