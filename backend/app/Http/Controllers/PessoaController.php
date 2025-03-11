<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Cargo;
use App\Models\HistoricoCargo;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    // Exibe todas as pessoas com seu último cargo
    public function index()
    {
        // Carrega todas as pessoas e seus históricos de cargos
        $pessoas = Pessoa::with('historicoCargos')->get();
        return response()->json($pessoas);
    }

    // Exibe os detalhes de uma pessoa, incluindo seu último cargo
    public function show(Pessoa $pessoa)
    {
        // Obtém o último cargo da pessoa, com base na data de início e fim
        $ultimoCargo = $pessoa->historicoCargos()->latest()->first();
        return response()->json([
            'pessoa' => $pessoa,
            'ultimo_cargo' => $ultimoCargo
        ]);
    }

    // Cria uma nova pessoa
    public function store(Request $request)
    {
        // Validação dos dados da pessoa
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:pessoas,email',
        ]);

        // Cria a pessoa
        $pessoa = Pessoa::create($request->all());

        // Se o request contiver dados sobre o cargo, vincula o cargo à pessoa
        if ($request->has('cargo_id')) {
            $pessoa->historicoCargos()->create([
                'cargo_id' => $request->cargo_id,
                'data_inicio' => now(),
            ]);
        }

        return response()->json($pessoa, 201);
    }

    // Atualiza os dados de uma pessoa
    public function update(Request $request, Pessoa $pessoa)
    {
        // Validação dos dados da pessoa
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // Atualiza os dados da pessoa
        $pessoa->update($request->all());

        // Se houver troca de cargo, atualize o histórico
        if ($request->has('cargo_id')) {
            // Finaliza o cargo anterior, se houver
            $ultimoCargo = $pessoa->historicoCargos()->latest()->first();
            if ($ultimoCargo) {
                $ultimoCargo->update(['data_fim' => now()]);
            }

            // Adiciona um novo cargo à pessoa
            $pessoa->historicoCargos()->create([
                'cargo_id' => $request->cargo_id,
                'data_inicio' => now(),
            ]);
        }

        return response()->json($pessoa);
    }

    // Deleta uma pessoa
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();
        return response()->json(['message' => 'Pessoa deletada com sucesso']);
    }

    // Consulta o histórico de cargos de uma pessoa específica
    public function historicoCargos(Pessoa $pessoa)
    {
        // Carrega o histórico de cargos da pessoa
        $historicoCargos = $pessoa->historicoCargos;
        return response()->json($historicoCargos);
    }
}

