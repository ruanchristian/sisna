<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Result;
use App\Models\SelectiveProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller {
    
    public function index(int $id) {
        $r = Result::where('process_id', $id)->get();
        $ano = SelectiveProcess::find($id)->ano;
        $cursos = Course::all();

        // filtragem de alunos aprovados ou classficáveis através da flag (is_classified) e agrupa pelo seu respectivo curso
        $publica = $r->where('is_classified', 1)
            ->filter(function ($aluno) {
                return in_array($aluno->origin, ['PCD', 'PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP']);
            })->groupBy('course_id')->map(function($group) {
                return $group->groupBy('origin');
        });

        $particular = $r->where('is_classified', 1)
        ->filter(function ($aluno) {
            return in_array($aluno->origin, ['PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP']);
        })->groupBy('course_id')->map(function($group) {
            return $group->groupBy('origin');
        });

        $publicaClassificaveis = $r->where('is_classified', 0)
            ->filter(function ($aluno) {
                return in_array($aluno->origin, ['PCD', 'PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP']);
        })->groupBy('course_id');

        $particularClassificaveis = $r->where('is_classified', 0)
            ->filter(function ($aluno) {
                return in_array($aluno->origin, ['PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP']);
        })->groupBy('course_id');

        return view('result.result-index',
         compact(
            'publica', 
            'particular', 
            'publicaClassificaveis',
            'particularClassificaveis',
            'cursos', 
            'ano'
        ));
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
            foreach($auxiliar as $origem => $k) {
                if($k > 0) { // se houver vagas não preenchidas...
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
        $ano = $req->input('ano');
        $cursos = collect(unserialize(base64_decode($req->input('cursos'))));
        $publica = collect(unserialize(base64_decode($req->input('publica'))));
        $particular = collect(unserialize(base64_decode($req->input('particular'))));
        $pub_classfv = collect(unserialize(base64_decode($req->input('publicaClassificaveis'))));
        $priv_classfv = collect(unserialize(base64_decode($req->input('particularClassificaveis'))));

        $categorias = [
            'PCD',
            'PUBLICA-AMPLA',
            'PUBLICA-PROX-EEEP',
            'PRIVATE-AMPLA',
            'PRIVATE-PROX-EEEP',
        ];
    
        $classificados = [];

        foreach ($cursos as $curso) {
            $cursoId = $curso->id;
    
            foreach ($categorias as $cat) {
                $classificados[$cursoId][$cat] = collect();
    
                if ($cat === 'PCD') {
                    $classificados[$cursoId][$cat] = 
                        ($publica[$cursoId][$cat] ?? collect())->merge($particular[$cursoId][$cat] ?? collect());
                } elseif (str_starts_with($cat, 'PUBLICA')) {
                    $classificados[$cursoId][$cat] = $publica[$cursoId][$cat] ?? collect();
                } elseif (str_starts_with($cat, 'PRIVATE')) {
                    $classificados[$cursoId][$cat] = $particular[$cursoId][$cat] ?? collect();
                }
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