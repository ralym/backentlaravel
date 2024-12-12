<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InfoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatriculacionControler;
use App\Http\Controllers\EducadoraControler;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\InventarioController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/info', [InfoController::class, 'info']);
Route::post('login', [AuthController::class, 'login']);

//persona
Route::post('/personas/normal', [PersonaController::class, 'buscarnormal']);
Route::post('/personas/buscar', [PersonaController::class, 'buscar']);
Route::post('/personas/buscar-ninos', [PersonaController::class, 'buscarNinos']);

Route::post('/matriculacion', [MatriculacionControler::class, 'guardarMatriculacion']);
Route::delete('/matriculacion/{id_nino}', [MatriculacionControler::class, 'eliminarMatriculacion']);


//usuarios

Route::get('/usuarios', [UsuarioController::class, 'fetchUsers']);
//Route::get('/usuarios', [UsuarioController::class, 'index']);
//Route::post('/usuarios', [UsuarioController::class, 'store']);
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
Route::get('/permissions', [PermissionController::class, 'getPermissions']);

//matriculacion
Route::get('/matriculas', [MatriculacionControler::class, 'getMatriculas']);
Route::get('/matriculas/tutor/{id_nino}', [MatriculacionControler::class, 'obtenerTutor']);
Route::get('/matriculas/horarios/{id_nino}', [MatriculacionControler::class, 'obtenerHorarios']);
Route::get('/matriculacion/{id_nino}', [MatriculacionControler::class, 'obtenerMatriculacion']);
Route::post('/matriculas/horarios/curso-aula', [MatriculacionControler::class, 'obtenerHorarioPorCursoYAula']);
Route::get('/matriculas/horarios/aula/{id_aula}', [MatriculacionControler::class, 'obtenerHorarioPorAula']);

//educadora
Route::get('/educadoras', [EducadoraControler::class, 'getEducadoras']);
// Obtener una educadora específica
Route::get('/educadoras/{id}', [EducadoraControler::class, 'getEducadora']);
// Crear una nueva educadora
Route::post('/educadoras/nuevo', [EducadoraControler::class, 'createEducadora']);
// Actualizar una educadora existente
Route::put('/educadoras/actualiza/{id}', [EducadoraControler::class, 'updateEducadora']);
// Eliminar una educadora
Route::delete('/educadoras/elimina/{id}', [EducadoraControler::class, 'deleteEducadora']);


//curso
Route::prefix('cursos')->group(function () {
    Route::get('/', [CursoController::class, 'getCursos']);       // Listar cursos con paginación
    Route::get('/{id}', [CursoController::class, 'getCurso']);    // Obtener un curso por ID
    Route::post('/', [CursoController::class, 'createCurso']);    // Crear un nuevo curso
    Route::put('/{id}', [CursoController::class, 'updateCurso']); // Actualizar un curso existente
    Route::delete('/{id}', [CursoController::class, 'deleteCurso']); // Eliminar un curso
});

// Rutas de Piso
Route::get('/pisos', [PisoController::class, 'index']); // Obtener todos los pisos
Route::get('/pisos/{id}', [PisoController::class, 'show']); // Obtener un piso por ID
Route::post('/pisos', [PisoController::class, 'store']); // Crear un nuevo piso
Route::put('/pisos/{id}', [PisoController::class, 'update']); // Actualizar un piso
Route::delete('/pisos/{id}', [PisoController::class, 'destroy']); // Eliminar un piso

// Rutas de Aula
Route::get('/aulas/piso/{idPiso}', [AulaController::class, 'getByPiso']); // Obtener aulas por ID de piso
Route::get('/aulas/{id}', [AulaController::class, 'show']); // Obtener un aula por ID
Route::post('/aulas', [AulaController::class, 'store']); // Crear un aula
Route::put('/aulas/{id}', [AulaController::class, 'update']); // Actualizar un aula
Route::delete('/aulas/{id}', [AulaController::class, 'destroy']); // Eliminar un aula


//inventario
Route::get('/inventario', [InventarioController::class, 'index']); // Obtener todos los productos
Route::post('/inventario', [InventarioController::class, 'store']); // Crear un nuevo producto
Route::get('/inventario/{id}', [InventarioController::class, 'show']); // Obtener un producto específico
Route::put('/inventario/{id}', [InventarioController::class, 'update']); // Actualizar un producto
Route::delete('/inventario/{id}', [InventarioController::class, 'destroy']); // Eliminar un producto
Route::get('/inventario/aula/{id_aula}', [InventarioController::class, 'getInventarioPorAula']); // Obtener productos por aula
Route::put('/inventario/aula/{id_aula}/{id}', [InventarioController::class, 'actualizarPorAula']); // Actualizar producto por aula e id

//gestion documental
Route::prefix('gestion-documental')->group(function () {
    Route::post('/subir', [GestionDocumentalController::class, 'subirDocumento']);
    Route::get('/listar', [GestionDocumentalController::class, 'listarDocumentos']);
    Route::put('/cambiar-estado/{id}', [GestionDocumentalController::class, 'cambiarEstado']);
});





Route::get('/api/documentation', function () {
    return view('swagger.index');
});

Route::get('/prueba', function () {
    return response()->json(['mensaje' => 'Conexión exitosa']);
});  