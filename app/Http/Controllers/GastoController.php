<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with('vehiculo')->get();
        return view('gastos.index', compact('gastos'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::all();
        return view('gastos.create', compact('vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
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
        $vehiculos = Vehiculo::all();
        return view('gastos.edit', compact('gasto', 'vehiculos'));
    }
    public function show($id)
    {
        $gasto = Gasto::with('vehiculo')->findOrFail($id);
        return view('gastos.show', compact('gasto'));
    }
    public function update(Request $request, $id)
    {
        $gasto = Gasto::findOrFail($id);

        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
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
