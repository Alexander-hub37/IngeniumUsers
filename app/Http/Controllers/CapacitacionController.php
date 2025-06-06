<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Capacitacion;
use App\Models\Docente;

class CapacitacionController extends Controller
{
    public function index($docenteId)
    {
        // Obtener todas las capacitaciones asociadas a un docente
        $capacitaciones = Capacitacion::where('docente_id', $docenteId)->get();

        return response()->json([
            'data' => $capacitaciones
        ]);
    }

    public function store(Request $request, $docenteId)
    {
        // Validar los datos
        $request->validate([
            'curso' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        // Verificar que el docente existe
        $docente = Docente::findOrFail($docenteId);

        // Crear la capacitación
        $capacitacion = new Capacitacion($request->only([
            'curso',
            'institucion',
            'fecha_inicio',
            'fecha_termino',
        ]));

        // Asociar la capacitación al docente
        $capacitacion->docente_id = $docente->id;
        $capacitacion->save();

        return response()->json([
            'message' => 'Capacitación creada correctamente',
            'data' => $capacitacion
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Buscar la capacitación
        $capacitacion = Capacitacion::findOrFail($id);

        // Validar los datos
        $request->validate([
            'curso' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_inicio',
            
        ]);

        // Actualizar la capacitación
        $capacitacion->update($request->only([
            'curso',
            'institucion',
            'fecha_inicio',
            'fecha_termino',
        ]));

        return response()->json([
            'message' => 'Capacitación actualizada correctamente',
            'data' => $capacitacion
        ]);
    }

    public function destroy($id)
    {
        // Buscar la capacitación
        $capacitacion = Capacitacion::findOrFail($id);
        $capacitacion->delete();

        return response()->json([
            'message' => 'Capacitación eliminada correctamente'
        ]);
    }

}
