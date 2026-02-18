<?php

namespace App\Controllers\Guests;

use Core\Controller;
use Core\Response;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('guests.home', [
            'name' => 'Anasayfa',
            'items' => ['PHP', 'MVC', 'Blade', 'Logic']
        ]);
    }

    public function testJson()
    {
        Response::json(['status' => 'success', 'message' => 'Controller is working!']);
    }
}
