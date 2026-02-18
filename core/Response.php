<?php

namespace Core;

class Response
{
    /**
     * HTTP durum kodunu ayarlar.
     */
    public static function status($code)
    {
        http_response_code($code);
        return new static;
    }

    /**
     * Veriyi JSON formatında döndürür.
     */
    public static function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Farklı bir URL'ye yönlendirme yapar.
     */
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Önceki sayfaya yönlendirir.
     */
    public static function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        static::redirect($referer);
    }

    /**
     * Özel bir header ekler.
     */
    public static function header($name, $value)
    {
        header("$name: $value");
        return new static;
    }
}
