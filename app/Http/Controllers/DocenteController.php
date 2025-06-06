<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Docente;
use App\Models\Capacitacion;
use App\Models\DatoIngenium;
use App\Models\ExperienciaLaboral;
use App\Models\DatoProfesional;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::all();
        return response()->json($docentes);
    }

    public function show($id)
    {
        $docente = Docente::find($id);

        if (!$docente) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }

        return response()->json($docente);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'required|string|max:20|unique:docentes,dni',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'firma' => 'nullable|image|max:2048',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $docente = new Docente($request->only([
            'nombres', 'apellidos', 'dni', 'fecha_nacimiento',
            'telefono', 'direccion'
        ]));

        if ($request->hasFile('firma')) {
            $docente->firma = basename($request->file('firma')->store('docentes/firmas', 'private'));
        }

        $docente->save();

        return response()->json([
            'message' => 'Docente creado correctamente',
            'data' => $docente
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $docente = Docente::find($id);

        if (!$docente) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombres' => 'sometimes|required|string|max:100',
            'apellidos' => 'sometimes|required|string|max:100',
            'dni' => 'sometimes|required|string|max:20|unique:docentes,dni,' . $docente->id,
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'firma' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $docente->fill($request->only([
            'nombres', 'apellidos', 'dni', 'fecha_nacimiento',
            'telefono', 'direccion'
        ]));

        if ($request->hasFile('firma')) {
            if ($docente->firma) {
                \Storage::disk('private')->delete('docentes/firmas/' . $docente->firma);
            }

            $docente->firma = basename($request->file('firma')->store('docentes/firmas', 'private'));
        }

        $docente->save();

        return response()->json([
            'message' => 'Docente actualizado correctamente',
            'data' => $docente
        ]);
    }

    public function destroy($id)
    {
        $docente = Docente::find($id);

        if (!$docente) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }

        $docente->delete();

        return response()->json(['message' => 'Docente eliminado correctamente']);
    }

    public function obtenerDocenteCompleto($id)
    {
        $docente = Docente::with([
            'capacitaciones',
            'experienciasLaborales',
            'datosProfesionales',
            'datosIngenium'
        ])->findOrFail($id);

        return response()->json([
            'data' => $docente
        ]);
    }

}
