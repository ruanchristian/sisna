<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    public function index()
    {
        $courses = Course::all();

        return view('course.course-index', compact('courses'));
    }

    public function store(CourseRequest $request)
    {
        Course::create($request->validated());

        return back()->with('message', 'Curso criado com sucesso.');
    }

    public function update(Request $request, int $id)
    {
        if (!$course = Course::find($id)) return back()->with('error_msg', 'Problema ao achar curso...');

        $data = $request->all();

        try {
            $validator = Validator::make($data, [
                'nome' => 'required|min:3|max:20|unique:courses'
            ]);

            if ($validator->fails()) throw new \Exception($validator->errors()->first());

            $oldName = $course->nome;

            $course->update($data);
            return back()->with('success', $oldName . ' alterado para: <b>' . $course->nome . '</b>');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getCourse(int $id)
    {
        return Course::find($id) ?? response()->json(null, 404);
    }
}
