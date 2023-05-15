<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Course;
use App\Models\SelectiveProcess;
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

    public function store(StudentRequest $request, SelectiveProcess $process) {
        $process->students()->create($request->validated());

        return back();
    }
}
