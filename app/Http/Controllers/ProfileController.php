<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->telefono = $request->telefono;
        $profile->direccion = $request->direccion;
        $profile->fecha_nacimiento = $request->fecha_nacimiento;

        if ($request->hasFile('foto')) {
            $profile->foto = basename($request->file('foto')->store('perfil/fotos', 'private'));
        }

        if ($request->hasFile('cv')) {
            $profile->cv = basename($request->file('cv')->store('perfil/cvs', 'private'));
        }

        $profile->save();

        return response()->json([
            'message' => 'Perfil creado correctamente',
            'data' => $this->mapProfileResponse($profile)
        ], 201);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
    
        if (!$profile) {
            Log::warning("Perfil no encontrado para el usuario ID: {$user->id}");
            return response()->json(['error' => 'Perfil no encontrado'], 404);
        }
    
        Log::info('Datos recibidos para actualizar el perfil:', $request->all());
    
        // Actualizar datos simples
        $profile->telefono = $request->input('telefono', $profile->telefono);
        $profile->direccion = $request->input('direccion', $profile->direccion);
        $profile->fecha_nacimiento = $request->input('fecha_nacimiento', $profile->fecha_nacimiento);
    
        
        if ($request->hasFile('foto')) {
            if ($profile->foto) {
                Storage::disk('private')->delete('perfil/fotos/' . $profile->foto);
                Log::info("Foto anterior eliminada: {$profile->foto}");
            }
    
            $filename = $request->file('foto')->store('perfil/fotos', 'private');
            $profile->foto = basename($filename);
            Log::info("Nueva foto subida: {$profile->foto}");
        }
    
        
        if ($request->hasFile('cv')) {
            if ($profile->cv) {
                Storage::disk('private')->delete('perfil/cvs/' . $profile->cv);
                Log::info("CV anterior eliminado: {$profile->cv}");
            }
    
            $filename = $request->file('cv')->store('perfil/cvs', 'private');
            $profile->cv = basename($filename);
            Log::info("Nuevo CV subido: {$profile->cv}");
        }
    
        $profile->save();
        Log::info("Perfil actualizado correctamente para el usuario ID: {$user->id}");
    
        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'data' => [
                'telefono' => $profile->telefono,
                'direccion' => $profile->direccion,
                'fecha_nacimiento' => $profile->fecha_nacimiento,
                'foto_url' => $profile->foto ? url("/api/perfil/archivo/foto/{$profile->foto}") : null,
                'cv_url' => $profile->cv ? url("/api/perfil/archivo/cv/{$profile->cv}") : null,
            ]
        ]);
    }


    private function mapProfileResponse(Profile $profile)
    {
        return [
            'id' => $profile->id,
            'telefono' => $profile->telefono,
            'direccion' => $profile->direccion,
            'fecha_nacimiento' => $profile->fecha_nacimiento,
            'foto_url' => $profile->foto ? url("/api/perfil/archivo/foto/{$profile->foto}") : null,
            'cv_url' => $profile->cv ? url("/api/perfil/archivo/cv/{$profile->cv}") : null,
        ];
    }

    public function verArchivo($tipo, $filename)
    {
        $tiposPermitidos = ['foto', 'cv'];

        if (!in_array($tipo, $tiposPermitidos)) {
            return response()->json(['error' => 'Tipo de archivo no permitido'], 400);
        }

        $subcarpetas = [
            'foto' => 'perfil/fotos',
            'cv' => 'perfil/cvs',
        ];

        $ruta = $subcarpetas[$tipo] . '/' . $filename;

        if (!Storage::disk('private')->exists($ruta)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        if ($tipo === 'cv') {
            return Storage::disk('private')->download($ruta);
        }

        return Storage::disk('private')->response($ruta);
    }

}
