<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Result;
use App\Models\SelectiveProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ResultController extends Controller {
    
    public function index(int $id) {
        $process = SelectiveProcess::with(['results.student' ])->findOrFail($id);
        $r = $process->results;
        $ano = $process->ano;
        $cursos = Course::all();

        // CLASSIFICADOS — PÚBLICA
        $publica = $r->where('is_classified', 1)
            ->filter(fn($aluno) => in_array($aluno->origin, [
                'PCD',
                'PUBLICA-AMPLA',
                'PUBLICA-PROX-EEEP'
            ]))
            ->groupBy('course_id')->map(fn($g) => $g->groupBy('origin'));

        // CLASSIFICADOS — PARTICULAR
        $particular = $r->where('is_classified', 1)
            ->filter(fn($aluno) => in_array($aluno->origin, [
                'PRIVATE-AMPLA',
                'PRIVATE-PROX-EEEP'
            ]))
            ->groupBy('course_id')->map(fn($g) => $g->groupBy('origin'));

        // CLASSIFICÁVEIS — PÚBLICA
        $publicaClassificaveis = $r->where('is_classified', 0)
            ->filter(fn($aluno) => in_array($aluno->origin, [
                'PCD',
                'PUBLICA-AMPLA',
                'PUBLICA-PROX-EEEP'
            ]))->groupBy('course_id');

        // CLASSIFICÁVEIS — PARTICULAR
        $particularClassificaveis = $r->where('is_classified', 0)
            ->filter(fn($aluno) => in_array($aluno->origin, [
                'PRIVATE-AMPLA',
                'PRIVATE-PROX-EEEP'
            ]))->groupBy('course_id');

        $token = Str::uuid()->toString();

        Cache::put("resultado_{$token}", [
            'publica' => $publica,
            'particular' => $particular,
            'publicaClassificaveis' => $publicaClassificaveis,
            'particularClassificaveis' => $particularClassificaveis,
            'cursos' => $cursos
        ], now()->addMinutes(20));

        return view('result.result-index',
            compact(
                'token',
                'publica',
                'particular',
                'publicaClassificaveis',
                'particularClassificaveis',
                'cursos',
                'ano'
            )
        );
    }

    public function rsa($processo_id) {
        $process = SelectiveProcess::findOrFail($processo_id);
        $courses = explode('-', $process->cursos); // "1-2-3-4" -> [1,2,3,4]

        $config = $process->config;
        $desempate = json_decode($config->ordem_desempate, true);

        $origens = [
            'PCD' => $config->vagas_pcd,
            'PUBLICA-AMPLA' => $config->vagas_publica_ampla,
            'PRIVATE-AMPLA' => $config->vagas_private_ampla,
            'PUBLICA-PROX-EEEP' => $config->vagas_publica_prox,
            'PRIVATE-PROX-EEEP' => $config->vagas_private_prox,
        ];
        foreach ($courses as $id_curso) {
            $std = $process->students()->where('curso_id', $id_curso)
                ->orderByRaw(implode(',', array_map(fn($cat, $ord) => "$cat $ord", array_keys($desempate), $desempate)))
                ->get();
            // std contém tds os alunos do curso ordenados com base nos critérios de desempate (media_final DESC, data_nasc ASC e etc...)

            $aprov = collect(); // array que vai armazenar os alunos aprovados de cada curso
            $auxiliar = $origens; // array auxiliar que controla a qtd de vagas sobrando de cada categoria. [categoria => vagas_sobrando]

            // Alocação dos alunos aprovados nas suas respectivas categorias com base na qtd de vagas disponíveis
            // (PCD, PUBLICA AMPLA, RESIDENTES PUB, RESIDENTES PRIV...)
            foreach ($origens as $origem => $vagas) {
                $selected = $std->where('origem', $origem)->take($vagas);
                $aprov = $aprov->merge($selected);
                $auxiliar[$origem] -= $selected->count();
            }

            // Distribuir vagas remanescentes (caso existam)
            foreach ($auxiliar as $origem => $k) {
                if ($k > 0) { // se houver vagas não preenchidas...
                    if ($origem === 'PCD') {
                        // 3.3 Se as vagas para estudantes com deficiência não forem preenchidas, as que sobrarem serão ocupadas
                        // por estudantes de escola pública, seguindo a ordem de classificação geral

                        // primeiro busca nos residentes próximos da escola pública
                        $publica_prox = $std->where('origem', 'PUBLICA-PROX-EEEP')->diff($aprov)->take($k);
                        $aprov = $aprov->merge($publica_prox);
                        $k -= $publica_prox->count();

                        // se ainda assim sobrar vagas, vai buscar na ampla da escola pública
                        if ($k > 0) {
                            $publica_ampla = $std->where('origem', 'PUBLICA-AMPLA')->diff($aprov)->take($k);
                            $aprov = $aprov->merge($publica_ampla);
                            $k -= $publica_ampla->count();
                        }
                        continue;
                    }

                    // Seleciona os k primeiros alunos disponíveis que ainda não tinham sido aprovados (priorizando os que estão no topo da lista).
                    $extra = $std->diff($aprov)->take($k);
                    $aprov = $aprov->merge($extra); // os alunos que ocuparam essas novas vagas são adicionados no array principal dos aprovados.
                }
            }
            $classificaveis = $std->diff($aprov);

            DB::transaction(function () use ($aprov, $classificaveis, $processo_id) {
                foreach ($aprov as $student) {
                    Result::insert([
                        'student_id' => $student->id,
                        'process_id' => $processo_id,
                        'is_classified' => 1, // flag (1 = aprovado, 0 = classificável)
                        'origin' => $student->origem,
                        'course_id' => $student->curso_id
                    ]);
                }

                foreach ($classificaveis as $s) {
                    Result::insert([
                        'student_id' => $s->id,
                        'process_id' => $processo_id,
                        'is_classified' => 0,
                        'origin' => $s->origem,
                        'course_id' => $s->curso_id
                    ]);
                }
            });
        }
    }

    public function gerarPdf(Request $req) {
        $ano = $req->ano;
        $token = $req->token;
        $data = Cache::get("resultado_{$token}");
        
        if(!$data) abort(404, 'Dados expirados. Recarregue a página.');

        $categorias = [
            'PCD',
            'PUBLICA-AMPLA',
            'PUBLICA-PROX-EEEP',
            'PRIVATE-AMPLA',
            'PRIVATE-PROX-EEEP'
        ];

        $cursos = $data['cursos'];
        $publica = $data['publica'];
        $particular = $data['particular'];
        $pub_classfv = $data['publicaClassificaveis'];
        $priv_classfv = $data['particularClassificaveis'];

        $classificados = [];

        foreach ($cursos as $curso) {
            $cursoId = $curso->id;

            foreach ($categorias as $cat) {
                $classificados[$cursoId][$cat] = match (true) {
                    $cat === 'PCD' => collect($publica[$cursoId]['PCD'] ?? [])->merge($particular[$cursoId]['PCD'] ?? []),
                    str_starts_with($cat, 'PUBLICA') => collect($publica[$cursoId][$cat] ?? []),
                    str_starts_with($cat, 'PRIVATE') => collect($particular[$cursoId][$cat] ?? []),
                    default => collect()
                };
            }
        }

        $pdf_final = Pdf::loadView('result.pdf', [
            'ano' => $ano,
            'cursos' => $cursos,
            'classificados' => $classificados,
            'publica_classfv' => $pub_classfv,
            'private_classfv' => $priv_classfv,
            'edital' => $req->input('editall'),
            'res' => $req->input('resultado'),
            'day' => $req->input('day'),
            'month' => $req->input('month'),
            'year' => $req->input('yearr')
        ])->setPaper('a4', 'portrait');

        return $pdf_final->download("Resultado-EEEP-DR-JOSÉ-ALVES-DA-SILVEIRA {$ano}.pdf");
    }
}
