<?php

namespace Core;

class Request
{
    /**
     * Tüm GET ve POST verilerini temizleyerek döner.
     */
    public static function all()
    {
        $data = array_merge($_GET, $_POST);
        return static::sanitize($data);
    }

    /**
     * Belirli bir anahtara ait veriyi döner.
     */
    public static function input($key, $default = null)
    {
        $all = static::all();
        return $all[$key] ?? $default;
    }

    /**
     * Sadece GET verilerini döner.
     */
    public static function get($key = null, $default = null)
    {
        $data = static::sanitize($_GET);
        if ($key === null)
            return $data;
        return $data[$key] ?? $default;
    }

    /**
     * Sadece POST verilerini döner.
     */
    public static function post($key = null, $default = null)
    {
        $data = static::sanitize($_POST);
        if ($key === null)
            return $data;
        return $data[$key] ?? $default;
    }

    /**
     * İstek metodunu döner (GET, POST vb.).
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * İsteğin URI bilgisini döner.
     */
    public static function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Verileri XSS ve boşluk temizliğinden geçirir.
     */
    protected static function sanitize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = static::sanitize($value);
            }
        } else {
            $data = trim($data);
            $data = htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}
