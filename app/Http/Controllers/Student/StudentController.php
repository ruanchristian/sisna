<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Course;
use App\Models\SelectiveProcess;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller {
    
    public function index(int $processId) {
        try {
            $process = SelectiveProcess::findOrFail($processId);
        } catch (ModelNotFoundException $e) {
            return to_route('process.index')->with('error_msg', $e->getMessage());
        }

        if ($process->estado == 0) return to_route('process.index')
                                          ->with('error_msg', 'Este processo está inativo. Ative-o se quiser cadastrar/editar novos participantes.');

        $courses = collect(explode('-', $process->cursos))->map(function ($course_id) {
            return Course::find($course_id);
        });

        return view('students.student-index', compact('process', 'courses'));
    }

    public function lotes($id) {
        $ano = SelectiveProcess::findOrFail($id)->ano;
        $cursos = Course::all();

        $pcd = $this->getByCategory($id, 'PCD');
        $publicaAmpla = $this->getByCategory($id, 'PUBLICA-AMPLA');
        $publicaProximos = $this->getByCategory($id, 'PUBLICA-PROX-EEEP');
        $privAmpla = $this->getByCategory($id, 'PRIVATE-EEEP');
        $privProximos = $this->getByCategory($id, 'PRIVATE-PROX-EEEP');


        return view('students.lotes', 
            compact(
                'ano',
                'cursos',
                'pcd',
                'publicaAmpla',
                'publicaProximos',
                'privAmpla',
                'privProximos'
            ));
    }

    public function edit(SelectiveProcess $process, Student $student) {
        $courses = collect(explode('-', $process->cursos))->map(function ($course_id) {
            return Course::find($course_id);
        });

        return view('students.student-index', compact('process', 'courses', 'student'));
    }

    public function store(StudentRequest $request, SelectiveProcess $process) {
        $data = $request->validated();
        $data['nome'] = mb_strtoupper($request->nome);

        $process->students()->create($data);

        return back()->with('success', 'Participante cadastrado com sucesso.');
    }

    public function update(StudentRequest $request, Student $student) {
        $student->update($request->validated());

        return to_route('student.visualization', $student->process->id)->with('success', "Participante <b>$student->id</b> foi editado com sucesso");
    }

    public function getByCategory(int $id, string $category) {
        $alunos = Student::where('processo_id', $id)
            ->where('origem', 'LIKE', $category . '%')
            ->get();

        return $alunos->chunk(20); // Divide os alunos em lotes de 20   
    }

    public function viewStudents(int $processId) {
        try {
            $process = SelectiveProcess::findOrFail($processId);
        } catch (ModelNotFoundException $e) {
            return to_route('process.index')->with('error_msg', $e->getMessage());
        }

        if ($process->estado == 0) return to_route('process.index')
                                          ->with('error_msg', 'Este processo está inativo. Ative-o se quiser cadastrar/editar novos participantes.');

        $students = $process->students;

        return view('students.student-viewer', compact('process', 'students'));
    }
}