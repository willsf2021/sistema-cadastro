<?php

namespace App\Http\Controllers;

use App\Models\HistoricoCargo;
use App\Models\Cargo;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class HistoricoCargoController extends Controller
{
    // Exibe todos os históricos de cargos
    public function index()
    {
        // Carrega todos os registros de histórico de cargos com suas relações
        $historicoCargos = HistoricoCargo::with(['pessoa', 'cargo'])->get();
        return response()->json($historicoCargos);
    }

    // Exibe um histórico de cargo específico
    public function show(HistoricoCargo $historicoCargo)
    {
        // Retorna o histórico de cargo com as relações de pessoa e cargo
        return response()->json($historicoCargo->load(['pessoa', 'cargo']));
    }

    // Cria um novo histórico de cargo para uma pessoa
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'cargo_id' => 'required|exists:cargos,id',
            'data_inicio' => 'required|date',
        ]);

        // Cria o histórico de cargo
        $historicoCargo = HistoricoCargo::create([
            'pessoa_id' => $request->pessoa_id,
            'cargo_id' => $request->cargo_id,
            'data_inicio' => $request->data_inicio,
        ]);

        return response()->json($historicoCargo, 201);
    }

    // Atualiza um histórico de cargo existente
    public function update(Request $request, HistoricoCargo $historicoCargo)
    {
        // Validação dos dados
        $request->validate([
            'cargo_id' => 'required|exists:cargos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date',
        ]);

        // Atualiza o histórico de cargo
        $historicoCargo->update([
            'cargo_id' => $request->cargo_id,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
        ]);

        return response()->json($historicoCargo);
    }

    // Deleta um histórico de cargo
    public function destroy(HistoricoCargo $historicoCargo)
    {
        $historicoCargo->delete();
        return response()->json(['message' => 'Histórico de cargo deletado com sucesso']);
    }
}
