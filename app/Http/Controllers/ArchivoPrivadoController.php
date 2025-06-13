<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ArchivoPrivadoController extends Controller
{
    public function mostrar($tipo, $filename)
    {
        $subcarpetas = [
            'foto'          => 'docentes/fotos',
            'firma'         => 'docentes/firmas',
            'cv'            => 'docentes/cvs',
            'recomendacion' => 'recomendaciones',
            
        ];

        if (!array_key_exists($tipo, $subcarpetas)) {
            return response()->json(['error' => 'Tipo de archivo no permitido'], 400);
        }

        $ruta = $subcarpetas[$tipo] . '/' . $filename;

        if (!Storage::disk('private')->exists($ruta)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        
        if ($tipo === 'cv' || str_ends_with($filename, '.pdf')) {
            return Storage::disk('private')->download($ruta);
        }

        return Storage::disk('private')->response($ruta);
    }
}
