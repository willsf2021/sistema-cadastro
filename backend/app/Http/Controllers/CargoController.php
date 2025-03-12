<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    // Retorna todos os cargos como JSON
    public function index()
    {
        return response()->json(Cargo::all());
    }

    // Cria um novo cargo
    public function store(Request $request)
    {
        // Validação simples
        $request->validate([
            'nome_cargo' => 'required|string|max:255',
        ]);

        // Cria o cargo
        $cargo = Cargo::create($request->all());
        return response()->json($cargo, 201);  // Retorna o cargo recém-criado com status 201
    }

    // Retorna um cargo específico como JSON
    public function show(Cargo $cargo)
    {
        return response()->json($cargo);
    }

    
    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nome_cargo' => 'required|string|max:255',
        ]);

        $cargo->update($request->all());
        return response()->json($cargo);  
    }

    
    public function destroy(Cargo $cargo)
    {
        $cargo->delete();
        return response()->json(['message' => 'Cargo deletado com sucesso']);
    }
}