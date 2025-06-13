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
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;


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
            'foto' => 'nullable|image|max:2048', 
            'firma' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:4096', 
            
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $docente = new Docente($request->only([
            'nombres', 'apellidos', 'dni', 'fecha_nacimiento',
            'telefono', 'direccion'
        ]));

        if ($request->hasFile('foto')) {
            $docente->foto = basename($request->file('foto')->store('docentes/fotos', 'private'));
        }

        if ($request->hasFile('firma')) {
            $docente->firma = basename($request->file('firma')->store('docentes/firmas', 'private'));
        }

        if ($request->hasFile('cv')) {
            $docente->cv = basename($request->file('cv')->store('docentes/cvs', 'private'));
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
            'foto' => 'nullable|image|max:2048', 
            'firma' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:4096', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $docente->fill($request->only([
            'nombres', 'apellidos', 'dni', 'fecha_nacimiento',
            'telefono', 'direccion'
        ]));

        if ($request->hasFile('foto')) {
            if ($docente->foto) {
                \Storage::disk('private')->delete('docentes/fotos/' . $docente->foto);
            }

            $docente->foto = basename($request->file('foto')->store('docentes/fotos', 'private'));
        }

        if ($request->hasFile('firma')) {
            if ($docente->firma) {
                \Storage::disk('private')->delete('docentes/firmas/' . $docente->firma);
            }

            $docente->firma = basename($request->file('firma')->store('docentes/firmas', 'private'));
        }

        if ($request->hasFile('cv')) {
            if ($docente->cv) {
                \Storage::disk('private')->delete('docentes/cvs/' . $docente->cv);
            }

            $docente->cv = basename($request->file('cv')->store('docentes/cvs', 'private'));
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

    public function obtenerDocentesCompletos()
    {
        $docentes = Docente::with([
            'capacitaciones',
            'experienciasLaborales',
            'datosProfesionales',
            'datosIngenium'
        ])->get();

        return response()->json([
            'data' => $docentes
        ]);
    }


    public function storeCombinado(Request $request)
    {
        $validator = Validator::make($request->all(), [
 
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:admin,user,docente',

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

        DB::beginTransaction();

        try {
           
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'docente',
            ]);

            
            $docente = new Docente([
                'user_id' => $user->id,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'dni' => $request->dni,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            
            if ($request->hasFile('foto')) {
                $docente->foto = basename($request->file('foto')->store('docentes/fotos', 'private'));
            }

            if ($request->hasFile('firma')) {
                $docente->firma = basename($request->file('firma')->store('docentes/firmas', 'private'));
            }

            if ($request->hasFile('cv')) {
                $docente->cv = basename($request->file('cv')->store('docentes/cvs', 'private'));
            }

            $docente->save();

            DB::commit();

            return response()->json([
                'message' => 'Usuario y docente creados correctamente'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear usuario y docente',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
