<?php

namespace App\models;

use Core\Model;

class User extends Model
{
    protected static $table = 'users';
    protected static $fillable = ['name', 'username', 'email', 'password', 'role', 'status'];

    public static function active()
    {
        return static::where('status', 1)->get();
    }
}