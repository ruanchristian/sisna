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

        $resultado = $r->groupBy('origin')->map(function ($res) {
            return $res->groupBy('is_classified')
                ->map(function ($classifiedResults) {
                    return $classifiedResults->groupBy('course_id');
            });
        });

        $publica = collect();
        $particular = collect();
        $publicaClass = collect();
        $particularClass = collect();

        $resultado->each(function ($res, $origin) use (&$publica, &$particular, &$publicaClass, &$particularClass) {
            if (strpos($origin, 'PUBLICA') !== false || strpos($origin, 'PCD') !== false) {
                $publica->put($origin, $res);
                $publicaClass = $publicaClass->merge($res->get(0, collect()));
            } else {
                $particular->put($origin, $res);
                $particularClass = $particularClass->merge($res->get(0, collect()));
            }
        });

        $publicaClassificaveis = [
            'PCD' => $this->getByOrigin($publicaClass, 'PCD'),
            'PUBLICA-AMPLA' => $this->getByOrigin($publicaClass, 'PUBLICA-AMPLA'),
            'PUBLICA-PROX-EEEP' => $this->getByOrigin($publicaClass, 'PUBLICA-PROX-EEEP'),
        ];

        $particularClassificaveis = [
            'PRIVATE-AMPLA' => $this->getByOrigin($particularClass, 'PRIVATE-AMPLA'),
            'PRIVATE-PROX-EEEP' => $this->getByOrigin($particularClass, 'PRIVATE-PROX-EEEP'),
        ];

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
            'PUBLICA-PROX-EEEP' => $config->vagas_publica_prox,
            'PRIVATE-AMPLA' => $config->vagas_private_ampla,
            'PRIVATE-PROX-EEEP' => $config->vagas_private_prox,
        ];

        foreach ($courses as $id_curso) {
            $std = $process->students()->where('curso_id', $id_curso);

            foreach ($desempate as $categoria => $ordem) {
                $std->orderBy($categoria, $ordem);
            }
            $std = $std->get();

            $topStudents = collect($origens)->flatMap(function ($vagas, $origem) use ($std) {
                return $std->where('origem', $origem)->take($vagas);
            });

            // Alunos classificados
            foreach ($topStudents as $student_class) {
                Result::create([
                    'student_id' => $student_class->id,
                    'process_id' => $processo_id,
                    'is_classified' => 1,
                    'origin' => $student_class->origem,
                    'course_id' => $student_class->curso_id
                ]);
            }

            // Classificavéis
            $classificaveis = $std->diff($topStudents);
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

    function getByOrigin($collection, $category) {
        return $collection->flatMap(function ($classifiedResults) use ($category) {
            return $classifiedResults->filter(function ($curso_r) use ($category) {
                return $curso_r['origin'] === $category;
            })->flatten();
        });
    }
}