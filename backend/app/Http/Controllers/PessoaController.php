<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PessoaController extends Controller
{
    public function index()
    {
        return Pessoa::with('cargos')->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',  
            'email' => 'required|email|max:255', 
        ]);

     
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

   
        return Pessoa::create($request->all());
    }

    public function show($id)
    {
        return Pessoa::with('cargos')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',  
            'email' => 'required|email|max:255', 
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

       
        $pessoa = Pessoa::findOrFail($id);
        $pessoa->update($request->all());
        return $pessoa;
    }

    public function destroy($id)
    {
        Pessoa::destroy($id);
        return response()->noContent();
    }
}
