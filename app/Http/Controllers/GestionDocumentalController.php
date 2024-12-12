<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestionDocumentalController extends Controller
{
      // Subir un documento
      public function subirDocumento(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|in:CV,Actividad Semestral,Actividad Mensual',
            'archivo' => 'required|base64_file',
        ]);

        // Decodificar archivo base64
        $archivoBase64 = $request->input('archivo');
        $nombreArchivo = 'documento_' . time() . '.pdf'; // Cambia la extensión según el tipo esperado

        GestionDocumental::create([
            'id_educadora' => auth()->id(), // Suponiendo autenticación, ajusta según tu sistema
            'tipo_documento' => $request->input('tipo_documento'),
            'nombre_archivo' => $nombreArchivo,
            'archivo_base64' => $archivoBase64,
            'estado' => 'Activo', // Estado inicial
        ]);

        return response()->json(['message' => 'Documento subido exitosamente.'], 201);
    }
      // Listar documentos
      public function obtenerDocumentos()
      {
          $documentos = GestionDocumental::where('id_educadora', auth()->id())->get();
  
          return response()->json($documentos);
      }
  
      // Cambiar estado de un documento
      public function cambiarEstadoDocumento(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Aprobado,Rechazado',
            'motivo_rechazo' => 'required_if:estado,Rechazado',
        ]);

        $documento = GestionDocumental::findOrFail($id);
        $documento->update([
            'estado' => $request->input('estado'),
            'motivo_rechazo' => $request->input('motivo_rechazo', null),
        ]);

        return response()->json(['message' => 'Estado del documento actualizado.']);
    }
}
