<?php

namespace App\Http\Controllers;

use App\Models\{
    Course,
    SelectiveProcess,
    User
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
        $coursesCount = Course::all()->count();
        $processesCount = SelectiveProcess::all()->count();
        $adminsCount = User::where('type', 'administrador')->count();

        return view('home', compact('coursesCount', 'processesCount', 'adminsCount'));
    }
}
