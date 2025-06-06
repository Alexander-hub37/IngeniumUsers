<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExperienciaLaboral;
use App\Models\Docente;

class ExperienciaLaboralController extends Controller
{
    public function index($docenteId)
    {
        // Obtener todas las experiencias laborales asociadas a un docente
        $experiencias = ExperienciaLaboral::where('docente_id', $docenteId)->get();

        return response()->json([
            'data' => $experiencias
        ]);
    }

    public function store(Request $request, $docenteId)
    {
        // Validar los datos
        $request->validate([
            'empresa' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
        
        ]);

        // Verificar que el docente existe
        $docente = Docente::findOrFail($docenteId);

        // Crear la experiencia laboral
        $experiencia = new ExperienciaLaboral($request->only([
            'empresa',
            'cargo',
            'fecha_inicio',
            'fecha_termino',
        ]));

        // Asociar la experiencia al docente
        $experiencia->docente_id = $docente->id;
        $experiencia->save();

        return response()->json([
            'message' => 'Experiencia laboral creada correctamente',
            'data' => $experiencia
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Buscar la experiencia laboral
        $experiencia = ExperienciaLaboral::findOrFail($id);

        // Validar los datos
        $request->validate([
            'empresa' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        // Actualizar la experiencia laboral
        $experiencia->update($request->only([
            'empresa',
            'cargo',
            'fecha_inicio',
            'fecha_termino',
        ]));

        return response()->json([
            'message' => 'Experiencia laboral actualizada correctamente',
            'data' => $experiencia
        ]);
    }

    public function destroy($id)
    {
        // Buscar la experiencia laboral
        $experiencia = ExperienciaLaboral::findOrFail($id);
        $experiencia->delete();

        return response()->json([
            'message' => 'Experiencia laboral eliminada correctamente'
        ]);
    }
}
