<?php

namespace App\Http\Controllers;

use App\Models\CargoPessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CargoPessoaController extends Controller
{
    public function index(Request $request)
    {
        return CargoPessoa::all();
    }

    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'pessoa_id' => 'required|exists:pessoas,id',
            'cargo_id' => 'required|exists:cargos,id',
            'data_inicio' => 'required|date|before_or_equal:today',
            'data_fim' => 'nullable|date|after:data_inicio|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação nos dados fornecidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verifica se a pessoa já está ocupando o mesmo cargo em um período que se cruza ou tem intervalo em comum
        $cargoAtivo = CargoPessoa::where('pessoa_id', $request->pessoa_id)
            ->where('cargo_id', $request->cargo_id)
            ->where(function ($query) use ($request) {
                $query->whereNull('data_fim')
                      ->orWhere('data_fim', '>=', $request->data_inicio);
            })
            ->where(function ($query) use ($request) {
                $query->where('data_inicio', '<=', $request->data_fim ?? now());
            })
            ->exists();

        if ($cargoAtivo) {
            return response()->json([
                'message' => 'A pessoa já ocupa o cargo especificado no período informado, ou o período informado se sobrepõe ao vínculo existente.',
            ], 400);
        }

       
        $cargoPessoa = CargoPessoa::create($request->all());

        return response()->json($cargoPessoa, 201);
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'pessoa_id' => 'sometimes|required|exists:pessoas,id',
            'cargo_id' => 'sometimes|required|exists:cargos,id',
            'data_inicio' => 'sometimes|required|date|before_or_equal:today',
            'data_fim' => 'nullable|date|after:data_inicio|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação nos dados fornecidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

    
        $cargoPessoa = CargoPessoa::findOrFail($id);

        // Verifica se a pessoa já está ocupando o mesmo cargo em um período que se cruza ou tem intervalo em comum (exceto o próprio vínculo)
        $cargoAtivo = CargoPessoa::where('pessoa_id', $request->pessoa_id ?? $cargoPessoa->pessoa_id)
            ->where('cargo_id', $request->cargo_id ?? $cargoPessoa->cargo_id)
            ->where('id', '!=', $id) // Ignora o próprio vínculo
            ->where(function ($query) use ($request, $cargoPessoa) {
                $query->whereNull('data_fim')
                      ->orWhere('data_fim', '>=', $request->data_inicio ?? $cargoPessoa->data_inicio);
            })
            ->where(function ($query) use ($request, $cargoPessoa) {
                $query->where('data_inicio', '<=', $request->data_fim ?? now());
            })
            ->exists();

        if ($cargoAtivo) {
            return response()->json([
                'message' => 'A pessoa já ocupa o cargo especificado no período informado, ou o período informado se sobrepõe ao vínculo existente.',
            ], 400);
        }

        // Atualiza o vínculo
        $cargoPessoa->update($request->all());

        return response()->json($cargoPessoa);
    }

    public function destroy($id)
    {
        $cargoPessoa = CargoPessoa::find($id);

        if (!$cargoPessoa) {
            return response()->json([
                'message' => 'Vínculo não encontrado.',
            ], 404);
        }

        $cargoPessoa->delete();

        return response()->noContent();
    }

    public function historico($pessoa_id)
    {
        // Valida se a pessoa existe
        if (!\App\Models\Pessoa::where('id', $pessoa_id)->exists()) {
            return response()->json([
                'message' => 'Pessoa não encontrada.',
            ], 404);
        }

        return CargoPessoa::where('pessoa_id', $pessoa_id)->get();
    }
}