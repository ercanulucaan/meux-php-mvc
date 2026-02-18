<?php

namespace App\Controllers\Auth;

use Core\Controller;
use Core\Request;
use Core\Session;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        if (Session::has('user_id')) {
            redirect('admin.dashboard');
        }
        return $this->view('auth.login');
    }

    public function store()
    {
        $email = Request::post('email');
        $password = Request::post('password');

        $user = User::where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role']);

            redirect('admin.dashboard');
        }

        return $this->view('auth.login', ['error' => 'Geçersiz e-posta veya şifre!']);
    }
}
