<?php

namespace App\Models;

use Core\Model;

class File extends Model
{
    protected static $table = 'files';
    protected static $fillable = ['name', 'path', 'url', 'mime_type', 'size'];
}