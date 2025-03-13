<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoPessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CargoController extends Controller
{
    public function index()
    {
     
        
        return Cargo::all();
    }

    public function store(Request $request)
    {
 
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255|unique:cargos,nome',
            
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $cargo = Cargo::create($validator->validated());

        return response()->json($cargo, 201);
    }

    public function show($id)
    {
    
        if (!is_numeric($id)) {
            return response()->json(['error' => 'ID inválido'], 400);
        }

 
        $cargo = Cargo::find($id);

        if (!$cargo) {
            return response()->json(['error' => 'Cargo não encontrado'], 404);
        }

        return response()->json($cargo);
    }

    public function update(Request $request, $id)
    {

        if (!is_numeric($id)) {
            return response()->json(['error' => 'ID inválido'], 400);
        }

 
        $cargo = Cargo::find($id);

        if (!$cargo) {
            return response()->json(['error' => 'Cargo não encontrado'], 404);
        }


        $validator = Validator::make($request->all(), [
            'nome' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('cargos', 'nome')->ignore($cargo->id),
            ],
            'descricao' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

    
        $cargo->update($validator->validated());

        return response()->json($cargo);
    }

    public function destroy($id)
    {
   
        if (!is_numeric($id)) {
            return response()->json(['error' => 'ID inválido'], 400);
        }

  
        $cargo = Cargo::find($id);

        if (!$cargo) {
            return response()->json(['error' => 'Cargo não encontrado'], 404);
        }

    
        $vinculado = CargoPessoa::where('cargo_id', $id)->exists();

        if ($vinculado) {
            return response()->json(['error' => 'Este cargo está vinculado a uma ou mais pessoas e não pode ser excluído.'], 400);
        }

        
        $cargo->delete();

        return response()->noContent();
    }

    public function verificarVinculos($id)
    {
     
        if (!is_numeric($id)) {
            return response()->json(['error' => 'ID inválido'], 400);
        }

       
        $vinculado = CargoPessoa::where('cargo_id', $id)->exists();

        return response()->json(['vinculado' => $vinculado]);
    }
}
