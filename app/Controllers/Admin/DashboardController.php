<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            // Diğer istatistikler buraya...
        ];

        return $this->view('admin.dashboard', compact('stats'));
    }
}