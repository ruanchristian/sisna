<?php

namespace App\Http\Controllers\SpecialConfig;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpecialConfigController extends Controller {
    
    public function index() {
        return view('configs-sisna.configs-index');
    }
}
