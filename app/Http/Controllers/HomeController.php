<?php

namespace App\Http\Controllers;

use App\Models\{Course, SelectiveProcess, Student, User};
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $origens = [
            'PUBLICA-AMPLA'     => ['label' => 'Pública - Ampla concorrência', 'color' => '#007bff'],
            'PUBLICA-PROX-EEEP' => ['label' => 'Pública - Residente próximo',       'color' => '#17a2b8'],
            'PRIVATE-AMPLA'     => ['label' => 'Privada - Ampla concorrência', 'color' => '#fd7e14'],
            'PRIVATE-PROX-EEEP' => ['label' => 'Privada - Residente próximo',       'color' => '#ffc107'],
            'PCD'               => ['label' => 'PCD',                          'color' => '#6f42c1'],
        ];
        $chavesOrigem = array_keys($origens);

        $coursesCount = Course::count();
        $processesCount = SelectiveProcess::count();
        $adminsCount = User::where('type', 'administrador')->count();
        $studentsCount = Student::count();
        $ativos  = SelectiveProcess::where('estado', 1)->get();
        $processos = SelectiveProcess::withCount('students')->orderBy('ano')->get();

        $cursos = Course::withCount('students')->orderByDesc('students_count')->get();
        $topCursos   = $cursos->take(5);
        $cursosPorId = $cursos->keyBy('id');

        // Agregado base: inscrições e soma das médias por processo, curso e origem
        $linhas = Student::select(
                'processo_id',
                'curso_id',
                'origem',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(media_final) as soma')
            )
            ->groupBy('processo_id', 'curso_id', 'origem')
            ->get();

        // Monta os dados dos gráficos filtráveis a partir de um conjunto de linhas
        $montar = function ($rows) use ($cursosPorId, $chavesOrigem) {
            $porCurso = $rows->groupBy('curso_id')->map(function ($g, $id) use ($cursosPorId, $chavesOrigem) {
                $total = (int) $g->sum('total');

                return [
                    'name'    => $cursosPorId[$id]->nome ?? 'Desconhecido',
                    'color'   => $cursosPorId[$id]->cor_curso ?? '#6c757d',
                    'total'   => $total,
                    'media'   => $total ? round($g->sum('soma') / $total, 2) : 0,
                    'origens' => collect($chavesOrigem)
                        ->map(fn ($k) => (int) $g->where('origem', $k)->sum('total'))
                        ->all(),
                ];
            })->sortByDesc('total')->values()->all();

            // Por categoria: qtd
            $porOrigem = collect($chavesOrigem)
                ->map(fn ($k) => (int) $rows->where('origem', $k)->sum('total'))
                ->all();

            // Por categoria: média final
            $mediaOrigem = collect($chavesOrigem)->map(function ($k) use ($rows) {
                $r     = $rows->where('origem', $k);
                $total = (int) $r->sum('total');

                return $total ? round($r->sum('soma') / $total, 2) : 0;
            })->all();

            return [
                'cursos'  => $porCurso,
                'origens' => $porOrigem,
                'medias'  => $mediaOrigem,
            ];
        };

        $dados = $linhas->groupBy('processo_id')->map($montar)->all();
        $dados['all'] = $montar($linhas);

        $origemPorProcesso = $processos->map(fn ($p) => [
            'ano' => $p->ano,
            'origens' => collect($chavesOrigem)
                ->map(fn ($k) => (int) $linhas->where('processo_id', $p->id)->where('origem', $k)->sum('total'))
                ->all(),
        ])->values();

        // Evolução ano a ano com variação percentual
        $evolucao = $processos->values()->map(function ($p, $i) use ($processos) {
            $anterior = $i > 0 ? $processos[$i-1]->students_count : null;

            return [
                'ano'      => $p->ano,
                'total'    => $p->students_count,
                'estado'   => (bool) $p->estado,
                'variacao' => $anterior
                    ? round((($p->students_count - $anterior) / $anterior) * 100, 1)
                    : null,
            ];
        });
        $mediaPorProcesso = $processesCount ? round($studentsCount / $processesCount, 1) : 0;
        $mediaFinalGeral = round((float) Student::avg('media_final'), 2);
        $anoMaisCompetitivo = $processos->sortByDesc('students_count')->first();
        $cursoMaisInscritos = $cursos->first();

        return view('home', compact(
            'coursesCount',
            'processesCount',
            'adminsCount',
            'studentsCount',
            'ativos',
            'processos',
            'topCursos',
            'origens',
            'dados',
            'origemPorProcesso',
            'evolucao',
            'mediaPorProcesso',
            'mediaFinalGeral',
            'anoMaisCompetitivo',
            'cursoMaisInscritos'
        ));
    }
}