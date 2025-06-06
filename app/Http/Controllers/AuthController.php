<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'usuario' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'foto_url' => $user->foto ? url("/storage/users/{$user->foto}") : null,
            ]
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Sesión cerrada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            return response()->json([
                'message' => 'Usuario autenticado',
                'user' => [
                    'id' => $user->id,
                    'usuario' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'foto_url' => $user->foto ? url("/storage/users/{$user->foto}") : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudo obtener el usuario',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
