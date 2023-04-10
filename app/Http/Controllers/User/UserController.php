<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Models\User;

class UserController extends Controller {

    public function index() {
        $users = User::get();
        
        return view('user.users-index', compact('users'));
    }
    
    public function create() {
        return view('user.create-user');
    }

    public function store(StoreUpdateUserRequest $request) {
        $user = $request->all();
        $user['password'] = bcrypt($request->password);
        
        User::create($user);
        
        return redirect()->back()->with('message', 'Usuário: <b>'.$user['name'].'</b> foi criado com sucesso!');
    }
}
