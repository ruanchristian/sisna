<?php

namespace App\Http\Controllers\SpecialConfig;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialConfigRequest;
use App\Models\SelectiveProcess;
use App\Services\Selection\RedistributionGraph;

class SpecialConfigController extends Controller {
    
    public function index(int $id) {
        if (!$process = SelectiveProcess::find($id)) {
            return redirect()->route('process.index')->with('error_msg', 'Não foi possível abrir as configurações desse processo.');
        }
        $ordem = json_decode($process->config->ordem_desempate, true);
        $grafo = RedistributionGraph::fromConfig($process->config->redistribuicao);
        $redistribuicao = [
            'edges' => $grafo->toArray(),
            'default' => RedistributionGraph::DEFAULT_EDGES,
            'categories' => RedistributionGraph::CATEGORIES,
            'labels' => RedistributionGraph::LABELS,
            'geral' => RedistributionGraph::GERAL,
        ];

        return view('configs-sisna.configs-index', compact('process', 'ordem', 'redistribuicao'));
    }

    public function update(SpecialConfigRequest $request, SelectiveProcess $process) {
        $totalVagas = collect($request->except(['ordem_desempate', 'redistribuicao', '_method', '_token']))->sum();

        if ($totalVagas !== 45) {
            return back()->withErrors(['msg' => 'A soma das vagas ofertadas deve ser igual a 45!']);
        }

        $data = $request->validated();
        $data['redistribuicao'] = RedistributionGraph::fromConfig(json_decode($data['redistribuicao'], true))->toArray();

        // update() direto na relação não aplica o cast 'array' do model
        $process->config->update($data);
        return back()->with('message', 'As mudanças foram aplicadas com sucesso.');
    }
}
