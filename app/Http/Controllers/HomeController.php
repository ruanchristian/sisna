<?php

namespace App\Http\Controllers;

use App\Models\{
    Course,
    SelectiveProcess
};

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $courses_count = Course::all()->count();
        $processes_count = SelectiveProcess::all()->count();

        return view('home', compact('courses_count', 'processes_count'));
    }
}
