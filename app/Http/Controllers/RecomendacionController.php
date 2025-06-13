<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Recomendacion;
use App\Models\User;

class RecomendacionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_requerimiento' => 'required|string|max:255',
            'descripcion'          => 'nullable|string',
            'archivo'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'area_destino'         => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors'  => $validator->errors()
            ], 422);
        }

        $archivoPath = null;
        if ($request->hasFile('archivo')) {
            $archivoPath = $request->file('archivo')->store('recomendaciones', 'public');
        }

        $recomendacion = Recomendacion::create([
            'user_id'              => Auth::id(),
            'nombre_requerimiento' => $request->nombre_requerimiento,
            'descripcion'          => $request->descripcion,
            'archivo'              => $archivoPath,
            'area_destino'         => $request->area_destino,
            'estado'               => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Recomendación enviada correctamente',
            'data'    => $recomendacion
        ], 201);
    }

    
    public function misRecomendaciones()
    {
        $recomendaciones = Recomendacion::where('user_id', Auth::id())->latest()->get();
        return response()->json($recomendaciones);
    }

    
    public function todas()
    {
        $recomendaciones = Recomendacion::with('user')->latest()->get();
        return response()->json($recomendaciones);
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'calificacion' => 'required|integer|min:0|max:5',
        ]);

        $recomendacion = Recomendacion::find($id);

        if (!$recomendacion) {
            return response()->json(['error' => 'Recomendación no encontrada'], 404);
        }

        $recomendacion->estado = $request->estado;
        $recomendacion->calificacion = $request->calificacion;
        $recomendacion->save();

        return response()->json([
            'message' => 'Recomendacion actualizado correctamente',
            'data'    => $recomendacion
        ]);
    }

}
