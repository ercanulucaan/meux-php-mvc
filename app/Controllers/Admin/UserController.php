<?php
/**
 * UserController - Admin User Management
 * Handles listing, creating, updating and deleting users.
 */

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\User;
use Core\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::all();
        return $this->view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return $this->view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store()
    {
        $data = Request::all();

        // Validation
        if (empty($data['name']) || empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            flash('error', 'Lütfen tüm zorunlu alanları doldurun.');
            return back();
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['status'] = isset($data['status']) ? (int) $data['status'] : 1;
        $data['role'] = $data['role'] ?? 'user';

        User::create($data);

        flash('success', 'Kullanıcı başarıyla oluşturuldu.');
        return redirect('admin.users');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            flash('error', 'Kullanıcı bulunamadı.');
            return redirect('admin.users');
        }

        return $this->view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update($id)
    {
        $user = User::find($id);
        if (!$user) {
            flash('error', 'Kullanıcı bulunamadı.');
            return redirect('admin.users');
        }

        $data = Request::all();

        // Password update logic
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $data['status'] = isset($data['status']) ? (int) $data['status'] : 0;

        User::update($id, $data);

        flash('success', 'Kullanıcı başarıyla güncellendi.');
        return redirect('admin.users');
    }

    /**
     * Remove the specified user from storage.
     */
    public function delete($id)
    {
        User::destroy($id);
        flash('success', 'Kullanıcı başarıyla silindi.');
        return redirect('admin.users');
    }
}
