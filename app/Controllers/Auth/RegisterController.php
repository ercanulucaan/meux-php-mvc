<?php

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        if (Session::has('user_id')) {
            redirect('admin.dashboard');
        }
        return $this->view('auth.register');
    }

    public function store()
    {
        $data = [
            'name' => Request::post('name'),
            'username' => Request::post('username'),
            'email' => Request::post('email'),
            'password' => password_hash(Request::post('password'), PASSWORD_BCRYPT),
            'role' => 'user',
            'status' => 1
        ];

        if (User::create($data)) {
            redirect('auth.login');
        }

        return $this->view('auth.register', ['error' => 'Kayıt sırasında bir hata oluştu!']);
    }

}
