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

        $courses = collect(explode('-', $process->cursos))->map(function ($course_id) {
            return Course::find($course_id);
        });

        return view('students.student-index', compact('process', 'courses'));
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

        return back();
    }

    public function update(StudentRequest $request, Student $student) {
        $student->update($request->validated());

        return to_route('student.visualization', $student->process->id);
    }

    public function viewStudents(int $processId) {
        try {
            $process = SelectiveProcess::findOrFail($processId);
        } catch (ModelNotFoundException $e) {
            return to_route('process.index')->with('error_msg', $e->getMessage());
        }

        $students = $process->students;

        return view('students.student-viewer', compact('process', 'students'));
    }
}
