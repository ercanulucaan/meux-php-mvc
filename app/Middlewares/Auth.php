<?php

namespace App\Middlewares;
use Core\Session;
class Auth
{
    public function handle()
    {
        if (!Session::has('user_id')) {
            redirect('home');
        }
        return true;
    }
}
