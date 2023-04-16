<?php

namespace App\Http\Controllers\SelectiveProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcessRequest;
use App\Models\Course;
use App\Models\SelectiveProcess;

class SelectiveProcessController extends Controller {
    
    public function index() {
        $processos = SelectiveProcess::orderBy('ano', 'DESC')->get();
        $cursos = Course::all();

        return view('process.process-index', compact('processos', 'cursos'));
    }

    public function store(StoreProcessRequest $request) {
        SelectiveProcess::create($request->all());

        return redirect()->back();
    }
}
