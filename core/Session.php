<?php

namespace Core;

class Session
{
    /**
     * Session'ı başlatır.
     */
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Değer atar.
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Değeri getirir.
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Değerin olup olmadığını kontrol eder.
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Değeri siler.
     */
    public static function remove($key)
    {
        if (static::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Session'ı tamamen temizler.
     */
    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Tek seferlik (flash) mesaj atar.
     */
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Tek seferlik mesajı getirir ve siler.
     */
    public static function getFlash($key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}
