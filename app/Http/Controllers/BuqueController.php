<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Buque;
use App\Models\Cliente;
use App\Models\Empresa;

use Illuminate\Support\Facades\Log;

use Yajra\DataTables\Facades\DataTables;

class BuqueController extends Controller {
    public function index() {
        $task = Buque::with('empresas') -> get();
        return $task;
    }    
    
    public function visualizarTransportes() {
        $transportes = Buque::with('empresas')
            -> select(['nombre', 'tipo', 'id_empresa', 'estado', 'id']);

        return DataTables::of($transportes)
            ->addColumn('empresa', function ($row) {
                return $row->empresas->nombre ?? 'No asignada';
            })
            ->filterColumn('empresa', function($query, $keyword) {
                $query->whereHas('empresas', function ($q) use ($keyword) {
                    $q->where('nombre', 'like', "%{$keyword}%");
                });
            })
            ->make(true);
    }

    public function getEmpresas() {
        $empresas = Empresa::select('id', 'nombre') -> get();
        return view('Administrativo.Transportes.verTransportes', compact('empresas'));
    }
    /**
     * Funcion que recoge los transportes filtrados por la empresa a la que pertenece el cliente que va a verlos.
     */
    public function getBuquesCliente($id) {
        $cliente = Cliente::findOrFail($id);
        $task = Buque::where('id_empresa', $cliente -> id_empresa)
        -> where('estado', 'activo')
        -> with('empresas')
        -> get();
        return $task;
    }

    public function show(Request $request) {
        $task = Buque::findOrFail($request->id);
        return $task;
    }

    public function storeVehiculo(Request $request) {
        $vehicle = $request->validate([
            'nombre' => 'string',
            'tipo' => 'string',
            'empresa' => 'string',
        ]);

        try {
            $administrativo = Auth::user();

            $tipo = strtolower($vehicle['tipo']);

            // Crear y guardar la peticion de cita.
            $buque = Buque::create([
                "id" => null,
                "nombre" => $vehicle['nombre'],
                "tipo" => $tipo,
                "id_administrativo" => $administrativo -> id,
                "id_empresa" => $vehicle['empresa'],
            ]);

            return redirect() -> route('exito') -> with([
                'cabecera' => "Registrar vahiculo",
                'mensaje' => "¡Vehiculo " . $vehicle['nombre'] . " creado con exito!"
            ]);

            return response()->json([
                'message' => 'Vehiculo creado con éxito.',
                'task' => $task,
            ], 201); // Código HTTP 201: Creado

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al crear el Vehiculo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request) {
        \Log::debug('Datos recibidos en update(): ', $request->all());

        $validatedData = $request->validate([
            'id' => 'int',
            'nombre' => 'string',
            'tipo' => 'string',
            'estado' => 'string',
            'id_administrativo' => 'int',
            'id_empresa' => 'int',
        ]);

        try {
            $task = Buque::findOrFail($validatedData["id"]);

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
