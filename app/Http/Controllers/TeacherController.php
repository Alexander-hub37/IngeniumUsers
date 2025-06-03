<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all(); 
        return response()->json($teachers);
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
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:teachers',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048', 
            'firma' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:4096', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $teacher = new Teacher($request->except(['foto', 'firma', 'cv']));

        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('docentes/fotos', 'private');
            $teacher->foto = basename($ruta);
        }

        if ($request->hasFile('firma')) {
            $ruta = $request->file('firma')->store('docentes/firmas', 'private');
            $teacher->firma = basename($ruta);
        }

        if ($request->hasFile('cv')) {
            $ruta = $request->file('cv')->store('docentes/cvs', 'private');
            $teacher->cv = basename($ruta);
        }

        $teacher->save();

        return response()->json([
            'message' => 'Docente creado correctamente',
            'data' => [
                'id' => $teacher->id,
                'nombre' => $teacher->nombre,
                'apellidos' => $teacher->apellidos,
                'dni' => $teacher->dni,
                'email' => $teacher->email,
                'telefono' => $teacher->telefono,
                'foto_url' => $teacher->foto ? url("/api/docentes/archivo/foto/{$teacher->foto}") : null,
                'firma_url' => $teacher->firma ? url("/api/docentes/archivo/firma/{$teacher->firma}") : null,
                'cv_url' => $teacher->cv ? url("/api/docentes/archivo/cv/{$teacher->cv}") : null,
            ]
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
        $teacher = Teacher::findOrFail($id);
        return response()->json($teacher);
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
        $teacher = Teacher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:teachers,dni,' . $teacher->id,
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'firma' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Actualiza campos simples
        $teacher->fill($request->except(['foto', 'firma', 'cv']));

        // FOTO
        if ($request->hasFile('foto')) {
            if ($teacher->foto && Storage::disk('private')->exists("docentes/fotos/{$teacher->foto}")) {
                Storage::disk('private')->delete("docentes/fotos/{$teacher->foto}");
            }
            $ruta = $request->file('foto')->store('docentes/fotos', 'private');
            $teacher->foto = basename($ruta);
        }

        // FIRMA
        if ($request->hasFile('firma')) {
            if ($teacher->firma && Storage::disk('private')->exists("docentes/firmas/{$teacher->firma}")) {
                Storage::disk('private')->delete("docentes/firmas/{$teacher->firma}");
            }
            $ruta = $request->file('firma')->store('docentes/firmas', 'private');
            $teacher->firma = basename($ruta);
        }

        // CV
        if ($request->hasFile('cv')) {
            if ($teacher->cv && Storage::disk('private')->exists("docentes/cvs/{$teacher->cv}")) {
                Storage::disk('private')->delete("docentes/cvs/{$teacher->cv}");
            }
            $ruta = $request->file('cv')->store('docentes/cvs', 'private');
            $teacher->cv = basename($ruta);
        }

        $teacher->save();

        return response()->json([
            'message' => 'Docente actualizado correctamente',
            'data' => [
                'id' => $teacher->id,
                'nombre' => $teacher->nombre,
                'apellidos' => $teacher->apellidos,
                'dni' => $teacher->dni,
                'email' => $teacher->email,
                'telefono' => $teacher->telefono,
                'foto_url' => $teacher->foto ? url("/api/docentes/archivo/foto/" . basename($teacher->foto)) : null,
                'firma_url' => $teacher->firma ? url("/api/docentes/archivo/firma/" . basename($teacher->firma)) : null,
                'cv_url' => $teacher->cv ? url("/api/docentes/archivo/cv/" . basename($teacher->cv)) : null,
            ]
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
        $teacher = Teacher::findOrFail($id);

        $teacher->delete(); 

        return response()->json([
            'message' => 'Docente eliminado correctamente',
        ]);
    }

    public function verArchivo($tipo, $filename)
    {
        $tiposPermitidos = ['foto', 'firma', 'cv'];

        if (!in_array($tipo, $tiposPermitidos)) {
            return response()->json(['error' => 'Tipo de archivo no permitido'], 400);
        }

        $subcarpetas = [
            'foto' => 'docentes/fotos',
            'firma' => 'docentes/firmas',
            'cv' => 'docentes/cvs',
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
