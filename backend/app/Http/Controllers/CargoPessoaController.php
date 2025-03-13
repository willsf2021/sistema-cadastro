<?php

namespace App\Http\Controllers;

use App\Models\CargoPessoa;
use Illuminate\Http\Request;

class CargoPessoaController extends Controller
{
    public function index(Request $request)
    {
        return CargoPessoa::all();
    }
    public function store(Request $request)
    {
        return CargoPessoa::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $cargoPessoa = CargoPessoa::findOrFail($id);
        $cargoPessoa->update($request->all());
        return $cargoPessoa;
    }

    public function destroy($pessoa_id)
    {
      
        $cargoPessoa = CargoPessoa::where('pessoa_id', $pessoa_id)->first();
    

        if (!$cargoPessoa) {
            return response()->json([
                'message' => 'Vínculo não encontrado para a pessoa informada.',
            ], 404);
        }

        $cargoPessoa->delete();
    
      
        return response()->noContent();
    }

    public function historico($pessoa_id)
    {
        return CargoPessoa::where('pessoa_id', $pessoa_id)->get();
    }
    
}