<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Zona;
use App\Models\Patio;

class ZonaController extends Controller {
    public function index() {
        $zonas = Zona::all();
        return response()->json($zonas);
    }
    // Funcion para recoger las zonas de la BD.
    public function getZonas() {
        $zonas = Zona::all();
        return view('Administrativo.gestionarCitas', compact('zonas'));
    }

    public function show(Request $request)
    {
        $task = Zona::findOrFail($request->id);
        return $task;
    }

    public function guardarZona(Request $request){

        $zona = $request -> validate([
            'nombre' => 'required|string|max:255',
            'X' => 'required|numeric',
            'Y' => 'required|numeric',
            'Z' => 'required|numeric',
            'id_gestor' => 'required|integer|exists:gestor,id',
            'id_patio' => 'required|integer|exists:patio,id',
        ]);

        $patio = Patio::findOrFail($zona['id_patio']);

        if ($patio) {
            $zona['ubicacion'] = $patio->nombre;
        } else {
            $zona['ubicacion'] = null;
        }

        $zona['capacidad'] = $zona['X'] * $zona['Y'] * $zona['Z'];


        try {
            Zona::create($zona);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la zona.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return view('Gestor.crearPatio');
    }

    public function verZona() {
         $zonas = Zona::select(['nombre', 'X', 'Y', 'Z']);
        return datatables()->of($zonas)->make(true);
    }

}
