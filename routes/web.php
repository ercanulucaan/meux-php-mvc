<?php
use Core\Router;
use Core\Session;

// Guests Controller
use App\Controllers\Guests\HomeController;

// Auth Controllers
use App\Controllers\Auth\RegisterController;
use App\Controllers\Auth\LoginController;
use App\Middlewares\Auth;

// Admin Controllers
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\UserController;


// Guests Routes
Router::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Router::group(['prefix' => 'auth'], function () {
    Router::get('/login', [LoginController::class, 'index'])->name('auth.login');
    Router::post('/login', [LoginController::class, 'store'])->name('auth.login.store');
    Router::get('/register', [RegisterController::class, 'index'])->name('auth.register');
    Router::post('/register', [RegisterController::class, 'store'])->name('auth.register.store');
    Router::get('/logout', function () {
        Session::destroy();
        redirect('auth.login');
    })->middleware([Auth::class])->name('auth.logout');
});

// Admin Routes
Router::group(['prefix' => 'admin', 'middleware' => [Auth::class]], function () {
    Router::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Users
    Router::get('/users', [UserController::class, 'index'])->name('admin.users');
    Router::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Router::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Router::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Router::post('/users/{id}/update', [UserController::class, 'update'])->name('admin.users.update');
    Router::post('/users/{id}/delete', [UserController::class, 'delete'])->name('admin.users.delete');
});