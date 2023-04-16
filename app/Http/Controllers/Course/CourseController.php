<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;

class CourseController extends Controller {

    public function index() {
        return view('course.course-index');
    }
}
