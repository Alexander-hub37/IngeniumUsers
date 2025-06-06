<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatoProfesional;
use App\Models\Docente;

class DatoProfesionalController extends Controller
{
    public function index($docenteId)
    {
        $datos = DatoProfesional::where('docente_id', $docenteId)->get();

        return response()->json([
            'data' => $datos
        ]);
    }

    public function store(Request $request, $docenteId)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $docente = Docente::findOrFail($docenteId);

        $dato = new DatoProfesional($request->only([
            'titulo',
            'institucion',
            'fecha_inicio',
            'fecha_termino'
        ]));

        $dato->docente_id = $docente->id;
        $dato->save();

        return response()->json([
            'message' => 'Dato profesional creado correctamente',
            'data' => $dato
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $dato = DatoProfesional::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $dato->update($request->only([
            'titulo',
            'institucion',
            'fecha_inicio',
            'fecha_termino'
        ]));

        return response()->json([
            'message' => 'Dato profesional actualizado correctamente',
            'data' => $dato
        ]);
    }

    public function destroy($id)
    {
        $dato = DatoProfesional::findOrFail($id);
        $dato->delete();

        return response()->json([
            'message' => 'Dato profesional eliminado correctamente'
        ]);
    }

}
