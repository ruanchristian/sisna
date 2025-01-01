<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Result;
use App\Models\SelectiveProcess;

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
        $courses = explode('-', $process->cursos);

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
            $std = $process->students()->where('curso_id', $id_curso);
            // std contém todos os alunos daquele respectivo curso

            foreach ($desempate as $categoria => $ordem) $std->orderBy($categoria, $ordem);
            
            $std = $std->get(); // agora, std contém tds os alunos ordenados com base nos critérios de desempate (media_final DESC, data_nasc ASC e etc...)
            $aprov = collect(); // array que vai armazenar os alunos aprovados

            // array que controla a qtd de vagas sobrando de cada categoria. [categoria => vagas_sobrando]
            $auxiliar = $origens;

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
                    // Seleciona o k primeiros alunos disponíveis que ainda não tinham sido aprovados (priorizando os que estão no topo da lista).
                    $extra = $std->diff($aprov)->take($k);
                    $aprov = $aprov->merge($extra); // os alunos que ocuparam essas novas vagas são adicionados no array principal dos aprovados.
                }
            }
            
            // Salva alunos classificados
            foreach ($aprov as $student) {
                Result::create([
                    'student_id' => $student->id,
                    'process_id' => $processo_id,
                    'is_classified' => 1, // flag (1 = aprovado, 0 = classificável)
                    'origin' => $student->origem,
                    'course_id' => $student->curso_id
                ]);
            }

            // Classificavéis
            $classificaveis = $std->diff($aprov); 
            foreach ($classificaveis as $s) {
                Result::create([
                    'student_id' => $s->id,
                    'process_id' => $processo_id,
                    'is_classified' => 0,
                    'origin' => $s->origem,
                    'course_id' => $s->curso_id
                ]);
            }
        }
    }
}