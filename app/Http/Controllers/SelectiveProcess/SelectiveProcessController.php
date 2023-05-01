<?php

namespace App\Http\Controllers\SelectiveProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcessRequest;
use App\Models\Course;
use App\Models\SelectiveProcess;
use Illuminate\Http\Request;

class SelectiveProcessController extends Controller
{

    public function index()
    {
        $processos = SelectiveProcess::orderByDesc('ano')->get();
        $cursos = Course::all();

        return view('process.process-index', compact('processos', 'cursos'));
    }

    public function store(StoreProcessRequest $request)
    {
        if (!is_countable($request->cursos) || count($request->cursos) != 4) {
            return back()->withErrors(['cursos' => "O processo seletivo precisa oferecer 4 cursos."]);
        }
        $processo = $request->all();
        $processo['cursos'] = implode('-', $request->cursos);

        SelectiveProcess::create($processo);

        return back();
    }

    public function updateState(Request $request, int $id)
    {
        if (!$process = SelectiveProcess::find($id)) {
            return response()->json(null, 404);
        }
        $process->update($request->only('estado'));
        return response()->json([
            'ok' => 'Processo alterado para: <b>' . (($request->estado == 1) ? 'EM ANDAMENTO' : 'ENCERRADO' . '</b>')
        ]);
    }

    // Função de teste
    public static function showResult($bestStudents, $origins): void {
        foreach ($origins as $origem => $vagas) {
            $students = $bestStudents->where('origem', $origem);

            echo view('alunos.index', compact('students', 'origem'));
        }
    }
}
