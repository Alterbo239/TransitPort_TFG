<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Models\Empresa;

class EmpresaController extends Controller {
    /**
     * Funcion que recoge las empresas pero convirtiendo la lista a un array donde la "key" es el id de la empresa.
     */
    public function getEmpresas() {
        $empresas = Empresa::all() -> keyBy('id');

        return view('Administrativo.crearTransporte', ['empresas' => $empresas]);
    }

    public function index() {
        $empresas = Empresa::all();
        return $empresas;
    }
    /**
     * Funcion que recoge las diferentes ciudades que hay asociadas a las empresas.
     */
    public function getCiudades() {
        $empresas = Empresa::distinct() -> pluck('ciudad');
        return $empresas;
    }

    public function store(Request $request) {
        // Validamos los datos.
        $validator = Validator::make($request -> all(), [
            'nombre' => 'string',
            'ciudad' => 'string',
            'cif' => 'regex:/^[0-9]{8}[A-Za-z]$/',
            'email' => 'string',
            'codigo_postal' => 'regex:/^\d{5}$/',
        ]);

        // Si falla devolvemos un error personalizado.
        if ($validator -> fails()) {
            return redirect() -> back()
                -> withErrors($validator)
                -> withErrors(['form' => "Introduzca datos validos en el formulario."])
                -> withInput();
        }

        //Por ultimo, si no falla agregamos el id del gestor para guardar los datos.
        $empresa = $validator -> validated();

        $empresa['id_gestor'] = Auth::user() -> id;

        try {
            Empresa::create($empresa);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la empresa.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return redirect() -> route('exito') -> with([
            'cabecera' => "Crear empresa",
            'mensaje' => "Empresa creada con éxito!"
        ]);
    }

    public function show(Request $request) {
        $empresas = Empresa::findOrFail($request -> id);
        return $empresas;
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'id' => 'int',
            'nombre' => 'string',
            'ciudad' => 'string',
            'codigo_postal' => 'string',
            'cif' => 'string',
            'email' => 'string',
            'id_gestor' => 'int',
        ]);

        try {
            $task = Empresa::findOrFail($validatedData["id"]);

            // Usar fill() en lugar de update() para mayor control
            $task->fill($validatedData);

        if ($task->isDirty()) { // Verifica si hay cambios antes de guardar
            $task->save();
        }

            return response()->json([
                'message' => 'Cita actualizada con éxito en la base de datos.',
                'task' => $validatedData,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al actualizar la cita.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
