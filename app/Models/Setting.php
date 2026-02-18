<?php

namespace App\Models;

use Core\Model;

class Setting extends Model
{
    protected static $table = 'settings';
    protected static $fillable = ['key', 'value', 'group'];

    /**
     * Key'e göre ayar değerini getirir.
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }
}
