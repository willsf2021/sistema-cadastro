<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoPessoa;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        return Cargo::all();
    }

    public function store(Request $request)
    {
        return Cargo::create($request->all());
    }

    public function show($id)
    {
        return Cargo::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);
        $cargo->update($request->all());
        return $cargo;
    }

    public function destroy($id)
    {
        Cargo::destroy($id);
        return response()->noContent();
    }
    public function verificarVinculos($id)
{
    $vinculado = CargoPessoa::where('cargo_id', $id)->exists();
    return response()->json(['vinculado' => $vinculado]);
}
}