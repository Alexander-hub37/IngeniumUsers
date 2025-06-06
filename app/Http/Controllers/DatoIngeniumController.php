<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatoIngenium;
use App\Models\Docente;

class DatoIngeniumController extends Controller
{
    public function index($docenteId)
    {
        // Obtener todos los datos con Ingenium asociados a un docente
        $datos = DatoIngenium::where('docente_id', $docenteId)->get();

        return response()->json([
            'data' => $datos
        ]);
    }

    public function store(Request $request, $docenteId)
    {
        // Validar los datos
        $request->validate([
            'curso' => 'required|string|max:255',
            'calificacion' => 'required|numeric|min:0|max:20',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string|max:50',
        ]);

        // Verificar que el docente existe
        $docente = Docente::findOrFail($docenteId);

        // Crear el dato con Ingenium
        $dato = new DatoIngenium($request->only([
            'curso',
            'calificacion',
            'fecha_inicio',
            'fecha_termino',
            'estado',
        ]));

        // Asociar el dato con Ingenium al docente
        $dato->docente_id = $docente->id;
        $dato->save();

        return response()->json([
            'message' => 'Dato con Ingenium creado correctamente',
            'data' => $dato
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Buscar el dato con Ingenium
        $dato = DatoIngenium::findOrFail($id);

        // Validar los datos
        $request->validate([
            'curso' => 'required|string|max:255',
            'calificacion' => 'required|numeric|min:0|max:20',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string|max:50',
        ]);

        // Actualizar el dato con Ingenium
        $dato->update($request->only([
            'curso',
            'calificacion',
            'fecha_inicio',
            'fecha_termino',
            'estado',
        ]));

        return response()->json([
            'message' => 'Dato con Ingenium actualizado correctamente',
            'data' => $dato
        ]);
    }

    public function destroy($id)
    {
        // Buscar el dato con Ingenium
        $dato = DatoIngenium::findOrFail($id);
        $dato->delete();

        return response()->json([
            'message' => 'Dato con Ingenium eliminado correctamente'
        ]);
    }
}
