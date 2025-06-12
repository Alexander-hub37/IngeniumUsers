<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PersonalEmpresa;
use Illuminate\Support\Facades\Auth;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::with('personalEmpresa')->get();
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Campos de usuario
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'      => 'required|in:admin,docente,b2c,b2b,contabilidad,marketing',

            // Campos de datos personales
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'nombres'          => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'dni'              => 'required|string|max:20|unique:personal_empresa',
            'fecha_nacimiento'   => 'nullable|date',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        $user->personalEmpresa()->create([
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'nombres'          => $request->nombres,
            'telefono'         => $request->telefono,
            'dni'              => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data'    => $user->load('personalEmpresa')
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'sometimes|nullable|string|min:6',
            'role'      => 'sometimes|in:admin,docente,b2c,b2b,contabilidad,marketing',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario->fill($request->only(['name', 'email', 'role']));

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }


    public function updateDatosPersonales(Request $request)
    {
        $user = Auth::user();
        $personal = $user->personalEmpresa;

        if (!$personal) {
            return response()->json(['error' => 'No se encontró información personal.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'apellido_paterno'   => 'nullable|string|max:100',
            'apellido_materno'   => 'nullable|string|max:100',
            'nombres'            => 'nullable|string|max:100',
            'telefono'           => 'nullable|string|max:20',
            'direccion'          => 'nullable|string|max:255',
            'fecha_nacimiento'   => 'nullable|date',
            'foto'               => 'nullable|image|max:2048',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors'  => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            $personal->foto = $fotoPath;
        }

       
        $personal->fill($request->only([
            'apellido_paterno',
            'apellido_materno',
            'nombres',
            'telefono',
            'direccion',
            'fecha_nacimiento',
        ]));

        $personal->save();

        return response()->json([
            'message' => 'Datos personales actualizados correctamente',
            'data'    => $personal
        ]);
    }

}
