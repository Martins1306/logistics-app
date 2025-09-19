<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Vehiculo;
use App\Models\Chofer; // ← Importado
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with('vehiculo', 'chofer')->get();
        $choferes = Chofer::orderBy('nombre')->get();
    
        return view('gastos.index', compact('gastos', 'choferes'));
    }
    public function create()
    {
        $vehiculos = Vehiculo::orderBy('patente')->get();
        $choferes = Chofer::orderBy('nombre')->get(); // ← Para el select
        return view('gastos.create', compact('vehiculos', 'choferes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'chofer_id' => 'nullable|exists:choferes,id', // ← Nuevo campo opcional
            'tipo' => 'required|in:combustible,mantenimiento,repuestos,lavado,seguro',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
        ]);

        Gasto::create($request->all());

        return redirect()->route('gastos.index')->with('success', '✅ Gasto registrado correctamente.');
    }

    public function edit($id)
    {
        $gasto = Gasto::findOrFail($id);
        $vehiculos = Vehiculo::orderBy('patente')->get();
        $choferes = Chofer::orderBy('nombre')->get();
        return view('gastos.edit', compact('gasto', 'vehiculos', 'choferes'));
    }

    public function show($id)
    {
        $gasto = Gasto::with('vehiculo', 'chofer')->findOrFail($id); // ← Con chofer
        return view('gastos.show', compact('gasto'));
    }

    public function update(Request $request, $id)
    {
        $gasto = Gasto::findOrFail($id);

        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'chofer_id' => 'nullable|exists:choferes,id',
            'tipo' => 'required|in:combustible,mantenimiento,repuestos,lavado,seguro',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
        ]);

        $gasto->update($request->all());

        return redirect()->route('gastos.index')->with('success', '✅ Gasto actualizado.');
    }

    public function destroy($id)
    {
        $gasto = Gasto::findOrFail($id);
        $gasto->delete();

        return redirect()->route('gastos.index')->with('success', '🗑️ Gasto eliminado.');
    }
}