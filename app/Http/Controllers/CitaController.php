<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Citas;
use App\Models\Cliente;
use App\Models\Buque;

class CitaController extends Controller {
    public function index() {
        $task = Citas::with(['buques', 'zonas']) -> get();
        return $task;
    }
    /**
     * Funcion que recoge las citas filtradas por cada cliente.
     */
    public function getCitasCliente($id) {
        $cliente = Cliente::findOrFail($id);
        $task = Citas::where('id_cliente', $cliente -> id)
        -> with(['buques', 'zonas'])
        -> get();
        return $task;
    }

    public function store(Request $request) {
       $validatedData = $request->validate([
            'tipo' => 'string',
            'fecha_pedida' => 'date_format:Y-m-d|after:today',
            'fecha_asignada' => 'date_format:Y-m-d',
            'hora' => 'date_format:H:i',
            'estado' => 'string',
            'id_administrativo' => 'int',
            'id_cliente' => 'int',
            'id_buque' => 'int',
            'id_zona' => 'int',
        ]);

        try {
            // Crear y guardar la tarea con asignación masiva
            $task = Citas::create($validatedData);

            return response()->json([
                'message' => 'Cita creada con éxito.',
                'task' => $task,
            ], 201); // Código HTTP 201: Creado

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al crear la cita.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request) {
        $task = Citas::findOrFail($request->id);
        return $task;
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'id' => 'int',
            'tipo' => 'string',
            'fecha_pedida' => 'date_format:Y-m-d',
            'fecha_asignada' => 'date_format:Y-m-d',
            'hora' => 'date_format:H:i:s',
            'estado' => 'nullable|string|in:En revision,Completada',
            'id_administrativo' => 'int',
            'id_cliente' => 'int',
            'id_buque' => 'int',
            'id_zona' => 'int',
        ]);

        try {
            $task = Citas::findOrFail($validatedData["id"]);

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

    public function destroy(Request $request) {
        $task = Citas::destroy($request->id);  //task tienen el id que se ha borrado

        return response()->json([
            "message" => "Cita con id =" . $task . " ha sido borrado con éxito"
        ], 201);
        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
    }

    /**
     * Funcion que recoge los vehiculos de la empresa a la que pertenece el cliente.
     * (Igual que con la Api de Transportes por cliente, solo que esta es para devolver a la vista).
     */
    public function getBuques(Request $request) {
        $user = Auth::user();
        $cliente = Cliente::findOrFail( $user -> id );
        $empresaId = $cliente -> id_empresa;

        $task = Buque::where('id_empresa', $empresaId) -> get();

        return view('Client.pedirCitasView', ['buques' => $task]);
    }

    /**
     * Funcion que guarda los datos de la pedida de la cita. (Los datos que se le permiten mandar al cliente).
     */
    public function storePedida(Request $request) {
        $cita = $request->validate([
            'tipo' => 'string',
            'fecha' => 'date_format:Y-m-d|after:today',
            'buque' => 'int',
        ]);

        try {
            $cliente = Auth::user();

            // Crear y guardar la peticion de cita.
            Citas::create([
                "id" => null,
                "tipo" => $cita['tipo'],
                "fecha_pedida" => $cita['fecha'],
                "fecha_asignada" => null,
                "hora" => null,
                "estado" => "En revision",
                "id_administrativo" => null,
                "id_cliente" => $cliente -> id,
                "id_buque" => $cita['buque'],
                "id_zona" => null,
            ]);

            return redirect() -> route('exitoCliente') -> with([
                'cabecera' => "Pedir cita",
                'mensaje' => "¡Cita pedida con exito!"
            ]);

            return response()->json([
                'message' => 'Cita creada con éxito.',
                'task' => $task,
            ], 201); // Código HTTP 201: Creado

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al crear la cita.',
                'error' => $e->getMessage(),
            ], 500);
        }
     }
}
