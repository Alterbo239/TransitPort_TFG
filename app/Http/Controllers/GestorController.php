<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gestor;
use App\Models\Empresa;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log;

class GestorController extends Controller {

    public function index(Request $request) {
        $task = User::all();
        return $task;

    }

    public function show(Request $request)
    {
        $task = Gestor::findOrFail($request->id);
        return $task;
    }

    public function update(Request $request)
    {
       $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => 'string',
            'password' => 'string',
            'cargo' => 'string',
            'estado' => 'string',
            'email' => 'string',
        ]);

        try {
            $task = Gestor::findOrFail($request["id"]);
            $task->update($validatedData);

            return response()->json([
                'message' => 'Gestor actualizado con éxito.',
                'task' => $task,
            ], 200);
            //Esta función actualizará la tarea que hayamos seleccionado

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al actualizar el Gestor.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function destroy(Request $request)
    {
        $task = Gestor::findOrFail($request->id);
        $task->delete();

        return response()->json([
            "message" => "Usuario con id =" . $request->id . " ha sido borrado con éxito"
        ], 201);
        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
    }

    //me lleva a la vista para crear usuarios  nuevos
    public function crearUsuario(){
        $empresas = Empresa::all();

        return view('Gestor.crearUsuario', compact('empresas'));

    }

    //almacena los datos del formulario en crear usuario
    public function guardarUsuario(Request $request){

        $user = $request -> validate([
            'name' => 'string',
            'usuario' => 'string',
            'email' => 'email',
            'telefono' => 'string',
            'ciudad' => 'string',
            'codigoPostal' => 'string',
            'password' => 'string',
            'cargo' => 'string|in:gestor,administrativo,operador,cliente',
        ]);

        $cliente = $request -> validate([
            'empresa' => 'string',
            'autonomo' => 'nullable|boolean',
        ]);

        try {
            $user['password'] = bcrypt($user['password']);
            $userModel = User::create($user);

            if ($user['cargo'] === 'cliente') {
                $id_gestor = Auth::user() -> id;

                $autonomo = $cliente['autonomo'] ?? 0;

                Cliente::create([
                    'id' => $userModel -> id,
                    'nombre' => $userModel -> name,
                    'usuario' => $userModel -> usuario,
                    'password' => $userModel -> password,
                    'cargo' => $userModel -> cargo,
                    'autonomo' => $autonomo,
                    'id_empresa' => $cliente['empresa'],
                    'id_gestor' => $id_gestor,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el usuario.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return redirect() -> route('exito') -> with([
            'cabecera' => "Crear usuario",
            'mensaje' => "Usuario creado con éxito!"
        ]);

    }

    //almacena los datos del formulario en crear usuario
    public function storeUsuario(Request $request){
        $user = $request -> validate([
            'name' => 'string',
            'usuario' => 'string',
            'email' => 'email',
            'telefono' => 'string',
            'ciudad' => 'string',
            'codigoPostal' => 'string',
            'password' => 'string',
            'cargo' => 'string|in:gestor,administrativo,operador,cliente',
        ]);

        $cliente = $request -> validate([
            'id_gestor' => 'integer',
            'empresa' => 'string',
            'autonomo' => 'nullable|boolean',
        ]);

        try {
            $user['password'] = bcrypt($user['password']);
            $userModel = User::create($user);

            if ($user['cargo'] === 'cliente') {
                $autonomo = $cliente['autonomo'] ?? 0;

                Cliente::create([
                    'id' => $userModel -> id,
                    'nombre' => $userModel -> name,
                    'usuario' => $userModel -> usuario,
                    'password' => $userModel -> password,
                    'cargo' => $userModel -> cargo,
                    'autonomo' => $autonomo,
                    'id_empresa' => $cliente['empresa'],
                    'id_gestor' => $cliente['id_gestor'],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                Log::error('Error en storeUsuario: ' . $e->getMessage()),
                'message' => 'Error al crear el usuario.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    }


