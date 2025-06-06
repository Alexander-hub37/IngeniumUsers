<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PerfilController extends Controller
{
    public function actualizarFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        // Eliminar foto anterior si existe
        if ($user->foto) {
            Storage::disk('private')->delete('perfil_fotos/' . $user->foto);
        }

        // Guardar nueva foto en almacenamiento privado
        $filename = $request->file('foto')->store('perfil_fotos', 'private');
        $user->foto = basename($filename);
        $user->save();

        return response()->json([
            'message' => 'Foto de perfil actualizada correctamente',
        ]);
    }

    public function verFoto()
    {
        $user = Auth::user();

        if (!$user->foto || !Storage::disk('private')->exists('perfil_fotos/' . $user->foto)) {
            return response()->json(['error' => 'Foto no encontrada'], 404);
        }

        return Storage::disk('private')->response('perfil_fotos/' . $user->foto);
    }
}
