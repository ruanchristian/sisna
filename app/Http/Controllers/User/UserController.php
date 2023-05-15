<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function index() {
        $users = User::all();
        
        return view('user.users-index', compact('users'));
    }
    
    public function create() {
        return view('user.create-user');
    }

    public function store(StoreUpdateUserRequest $request) {
        $user = $request->all();
        $user['password'] = bcrypt($request->password);
        
        User::create($user);
        
        return back()->with('message', 'Usuário: <b>'.$user['name'].'</b> foi criado com sucesso!');
    }

    public function update(StoreUpdateUserRequest $request, int $id) {
        if (!$user = User::find($id)) return to_route('user.index');

        $data = $request->except(['_method', '_token', 'password']);
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        return to_route('user.index')->with('success', 'Usuário: <b>'.$request->name.'</b> foi editado com sucesso!');
    }

    public function destroy(int $id) {
        if (Auth::user()->id === $id || !$user = User::find($id)) {
            return response(null, 404);
        }
        $user->destroy($id);

        return to_route('user.index');
    }

    public function getUserById(int $id) {
        return User::find($id) ?? to_route('user.index')->with('error_msg', 'Usuário não encontrado no banco de dados.');
    }

    public function checkPassword(Request $request) {
        if (Hash::check($request->senha, Auth::user()->password)) {
            return response(['success' => 'Salvando alterações...']);
        }
        return response(['fail' => 'Sua senha está incorreta! Tente novamente'], 404);
    }
}