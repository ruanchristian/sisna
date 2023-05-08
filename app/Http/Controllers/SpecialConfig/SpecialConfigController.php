<?php

namespace App\Http\Controllers\SpecialConfig;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialConfigRequest;
use App\Models\SelectiveProcess;

class SpecialConfigController extends Controller {
    
    public function index(int $id) {
        if (!$process = SelectiveProcess::find($id)) {
            return redirect()->route('process.index')->with('error_msg', 'Não foi possível abrir as configurações desse processo.');
        }
        $ordem = json_decode($process->config->ordem_desempate, true);
        
        return view('configs-sisna.configs-index', compact('process', 'ordem'));
    }

    public function update(SpecialConfigRequest $request, SelectiveProcess $process) {
        $totalVagas = collect($request->except(['ordem_desempate', '_method', '_token']))->sum();

        if ($totalVagas !== 45) {
            return back()->withErrors(['msg' => 'A soma das vagas ofertadas deve ser igual a 45!']);
        }

        $process->config()->update($request->validated());
        return back()->with('message', 'As mudanças foram aplicadas com sucesso.');
    }
}
