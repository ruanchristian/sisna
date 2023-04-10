<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\SelectiveProcess;
use App\Models\User;
use Illuminate\Http\Request;

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
        $users = User::all();
        $courses = Course::all();
        $processes = SelectiveProcess::all();
        $admin_count = User::where('type', 'administrador')->count();

        return view('home', compact('courses', 'users', 'processes', 'admin_count'));
    }
}
